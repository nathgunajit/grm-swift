<?php

namespace App\Http\Controllers;

use App\Http\Controllers\OtpController;
use App\Http\Requests\StoreGrievanceRequest;
use App\Models\Beel;
use App\Models\Grievance;
use App\Models\GrievanceCategory;
use App\Services\GrievanceNotifier;
use App\Services\GrievanceService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class GrievanceController extends Controller
{
    public function __construct(
        private GrievanceService $service,
        private GrievanceNotifier $notifier,
    ) {
    }

    public function create()
    {
        return view('public.submit', [
            'categories' => GrievanceCategory::where('is_active', true)->orderBy('code')->get(),
            'beels' => Beel::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(StoreGrievanceRequest $request)
    {
        $data = $request->validated();

        // OTP gate (demo): a non-anonymous submission must have its mobile verified.
        if (! $request->boolean('is_anonymous') && ! empty($data['mobile'])
            && ! OtpController::isVerified($request, $data['mobile'])) {
            return back()->withInput()->withErrors(['mobile' => 'Please verify your mobile number with the OTP before submitting.']);
        }

        $category = GrievanceCategory::find($data['category_id']);
        $beel = ! empty($data['beel_id']) ? Beel::find($data['beel_id']) : null;

        $trackingId = $this->service->generateTrackingId();

        $grievance = Grievance::create([
            'tracking_id' => $trackingId,
            'acknowledgment_no' => $this->service->generateAcknowledgmentNo($trackingId),
            'mode_of_receipt' => $data['mode_of_receipt'],
            'category_id' => $category->id,
            'name' => $request->boolean('is_anonymous') ? null : ($data['name'] ?? null),
            'gender' => $data['gender'] ?? null,
            'age' => $data['age'] ?? null,
            'caste' => $data['caste'] ?? null,
            'mobile' => $data['mobile'] ?? null,
            'email' => $data['email'] ?? null,
            'address' => $data['address'] ?? null,
            'place_village' => $data['place_village'],
            'beel_id' => $beel?->id,
            'district_id' => $beel?->district_id,
            'description' => $data['description'],
            'is_anonymous' => $request->boolean('is_anonymous'),
            'is_confidential' => $request->boolean('is_confidential'),
            'is_sensitive' => $category->is_sensitive,
            'status' => 'registered',
            'current_level' => 1,
            'due_at' => $this->service->dueDateForLevel(1),
        ]);

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $path = $file->store('grievances/'.$grievance->id, 'public');
                $grievance->documents()->create([
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime' => $file->getClientMimeType(),
                ]);
            }
        }

        $this->service->logAction($grievance, 'registered', null, 'Grievance registered online.', null, 1);
        $this->service->logAction($grievance, 'acknowledged', null, 'Acknowledgment issued: '.$grievance->acknowledgment_no, 1, 1);

        // Email + SMS the complainant their Tracking ID; alert the relevant officers' bell.
        $this->notifier->registered($grievance->fresh(['category', 'district']));

        return redirect()->route('grievance.submitted', $grievance->tracking_id);
    }

    public function submitted(string $trackingId)
    {
        $grievance = Grievance::where('tracking_id', $trackingId)->firstOrFail();

        return view('public.submitted', compact('grievance'));
    }

    public function trackForm()
    {
        return view('public.track');
    }

    public function track(Request $request)
    {
        $request->validate(['query' => ['required', 'string']]);
        $q = trim($request->input('query'));

        $grievance = Grievance::with(['category', 'beel', 'actions.user', 'feedback', 'documents'])
            ->where('tracking_id', $q)
            ->orWhere('acknowledgment_no', $q)
            ->orWhere('mobile', $q)
            ->first();

        if (! $grievance) {
            return back()->withInput()->with('error', 'No grievance found for that Tracking ID, Acknowledgment No, or mobile number.');
        }

        return view('public.track', compact('grievance'));
    }

    public function acknowledgmentPdf(string $trackingId)
    {
        $grievance = Grievance::with(['category', 'beel'])->where('tracking_id', $trackingId)->firstOrFail();
        $pdf = Pdf::loadView('pdf.acknowledgment', compact('grievance'));

        return $pdf->download('Acknowledgment-'.$grievance->tracking_id.'.pdf');
    }

    public function resolutionPdf(string $trackingId)
    {
        $grievance = Grievance::with(['category', 'beel'])->where('tracking_id', $trackingId)->firstOrFail();
        abort_unless(in_array($grievance->status, ['resolved', 'closed'], true), 404);
        $pdf = Pdf::loadView('pdf.resolution', compact('grievance'));

        return $pdf->download('Resolution-'.$grievance->tracking_id.'.pdf');
    }

    public function feedback(Request $request, string $trackingId)
    {
        $grievance = Grievance::where('tracking_id', $trackingId)->firstOrFail();
        abort_unless(in_array($grievance->status, ['resolved', 'closed'], true), 404);

        $data = $request->validate([
            'informed' => ['required', 'boolean'],
            'heard_respectfully' => ['required', 'boolean'],
            'response_time_ok' => ['required', 'boolean'],
            'satisfaction' => ['required', 'in:fully,partly,not'],
            'transparency' => ['required', 'in:good,average,poor'],
            'official_behavior' => ['required', 'in:good,average,poor'],
            'feel_safe' => ['required', 'boolean'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'comments' => ['nullable', 'string', 'max:1000'],
        ]);

        $grievance->feedback()->updateOrCreate(['grievance_id' => $grievance->id], $data);

        if ($grievance->status === 'resolved') {
            $grievance->update(['status' => 'closed']);
            $this->service->logAction($grievance, 'closed', null, 'Closed after complainant feedback.', $grievance->current_level, $grievance->current_level);
        }

        $this->notifier->actionTaken($grievance->fresh(['district']), 'Feedback received & grievance closed',
            'The complainant submitted feedback ('.$data['satisfaction'].' satisfied).', false, 'check');

        return redirect()->route('track')->with('success', 'Thank you — your feedback has been recorded and the grievance is now closed.');
    }

    public function reopen(Request $request, string $trackingId)
    {
        $grievance = Grievance::where('tracking_id', $trackingId)->firstOrFail();
        abort_unless(in_array($grievance->status, ['resolved', 'closed'], true), 404);

        $request->validate(['reason' => ['required', 'string', 'min:5', 'max:1000']]);

        // Reopen and escalate to the next level if not satisfied (per manual).
        $escalated = $this->service->escalate($grievance, null, 'Reopened by complainant: '.$request->input('reason'));

        if (! $escalated) {
            $grievance->update(['status' => 'reopened', 'resolved_at' => null]);
            $this->service->logAction($grievance, 'reopened', null, 'Reopened by complainant: '.$request->input('reason'), $grievance->current_level, $grievance->current_level);
        }

        $this->notifier->actionTaken($grievance->fresh(['district']), 'Grievance reopened by complainant',
            'Forwarded for further review at '.$grievance->fresh()->levelLabel().'.', false, 'refresh');

        return redirect()->route('track')->with('success', 'Your grievance has been reopened and forwarded for further review.');
    }
}

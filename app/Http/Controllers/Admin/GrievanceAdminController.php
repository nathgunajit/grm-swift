<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGrievanceRequest;
use App\Models\Beel;
use App\Models\Grievance;
use App\Models\GrievanceCategory;
use App\Services\GrievanceService;
use Illuminate\Http\Request;

class GrievanceAdminController extends Controller
{
    public function __construct(private GrievanceService $service)
    {
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $query = Grievance::query()->visibleTo($user)->with(['category', 'beel']);

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        if ($level = $request->input('level')) {
            $query->where('current_level', $level);
        }
        if ($request->boolean('overdue')) {
            $query->whereNotIn('status', ['resolved', 'closed'])->whereNotNull('due_at')->where('due_at', '<', now());
        }
        if ($search = $request->input('search')) {
            $query->where(fn ($q) => $q->where('tracking_id', 'like', "%$search%")
                ->orWhere('name', 'like', "%$search%")
                ->orWhere('mobile', 'like', "%$search%"));
        }

        $grievances = $query->latest()->paginate(15)->withQueryString();

        return view('admin.grievances.index', compact('grievances'));
    }

    public function show(Request $request, Grievance $grievance)
    {
        $this->authorizeView($request, $grievance);
        $grievance->load(['category', 'beel', 'district', 'documents', 'actions.user', 'feedback']);

        return view('admin.grievances.show', compact('grievance'));
    }

    public function create()
    {
        return view('admin.grievances.create', [
            'categories' => GrievanceCategory::where('is_active', true)->orderBy('code')->get(),
            'beels' => Beel::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(StoreGrievanceRequest $request)
    {
        $data = $request->validated();
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
            'registered_by' => $request->user()->id,
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

        $this->service->logAction($grievance, 'registered', $request->user()->id, 'Manually registered ('.$data['mode_of_receipt'].').', null, 1);
        $this->service->logAction($grievance, 'acknowledged', $request->user()->id, 'Acknowledgment issued: '.$grievance->acknowledgment_no, 1, 1);

        return redirect()->route('admin.grievances.show', $grievance)->with('success', 'Grievance registered. Tracking ID: '.$grievance->tracking_id);
    }

    public function review(Request $request, Grievance $grievance)
    {
        $this->authorizeView($request, $grievance);
        $request->validate(['remarks' => ['nullable', 'string', 'max:1000']]);

        if ($grievance->status === 'registered') {
            $grievance->update(['status' => 'under_review']);
        }
        $this->service->logAction($grievance, 'reviewed', $request->user()->id, $request->input('remarks') ?: 'Marked under review.', $grievance->current_level, $grievance->current_level);

        return back()->with('success', 'Grievance marked under review.');
    }

    public function comment(Request $request, Grievance $grievance)
    {
        $this->authorizeView($request, $grievance);
        $request->validate(['remarks' => ['required', 'string', 'max:1000']]);
        $this->service->logAction($grievance, 'commented', $request->user()->id, $request->input('remarks'), $grievance->current_level, $grievance->current_level);

        return back()->with('success', 'Comment added to the timeline.');
    }

    public function escalate(Request $request, Grievance $grievance)
    {
        $this->authorizeView($request, $grievance);
        $request->validate(['remarks' => ['nullable', 'string', 'max:1000']]);

        $ok = $this->service->escalate($grievance, $request->user()->id, $request->input('remarks') ?: 'Escalated to next level.');

        return back()->with($ok ? 'success' : 'error',
            $ok ? 'Grievance escalated to '.$grievance->fresh()->levelLabel().'.' : 'Already at the highest level (PIU); cannot escalate further.');
    }

    public function resolve(Request $request, Grievance $grievance)
    {
        $this->authorizeView($request, $grievance);
        $data = $request->validate(['resolution' => ['required', 'string', 'min:5', 'max:2000']]);

        $this->service->resolve($grievance, $data['resolution'], $request->user()->id);

        return back()->with('success', 'Grievance resolved. The complainant can view the resolution and provide feedback.');
    }

    private function authorizeView(Request $request, Grievance $grievance): void
    {
        $visible = Grievance::query()->visibleTo($request->user())->whereKey($grievance->id)->exists();
        abort_unless($visible, 403, 'This grievance is outside your jurisdiction.');
    }
}

@extends('layouts.public')
@section('title', 'Register Complaint — SWIFT GRM Portal')

@section('content')
<div class="mx-auto max-w-4xl px-4 py-10"
     x-data="grievanceForm()">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-2"><x-icon name="pencil" class="w-6 h-6 text-brand-600" /> Register a Grievance / Suggestion</h1>
        <p class="text-slate-500 mt-1">The SWIFT Project welcomes complaints, suggestions, queries and comments. There is no fee. You may submit anonymously.</p>
    </div>

    @if ($errors->any())
        <div class="mb-5 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 text-sm">
            <ul class="list-disc list-inside space-y-0.5">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('grievance.store') }}" enctype="multipart/form-data" class="card card-pad space-y-6">
        @csrf
        <input type="hidden" name="mobile" :value="mobile">

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="label">Mode of Receipt <span class="text-rose-500">*</span></label>
                <select name="mode_of_receipt" class="input" required>
                    @foreach (['online'=>'Online','verbal'=>'Verbal','written'=>'Written','phone'=>'Phone','drop-box'=>'Drop Box','meeting'=>'Meeting'] as $v=>$l)
                        <option value="{{ $v }}" @selected(old('mode_of_receipt')===$v)>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label">Category <span class="text-rose-500">*</span></label>
                <select name="category_id" class="input" required>
                    <option value="">-- Select category --</option>
                    @foreach ($categories as $c)<option value="{{ $c->id }}" @selected(old('category_id')==$c->id)>{{ $c->code }}. {{ $c->name }}</option>@endforeach
                </select>
            </div>
        </div>

        <label class="flex items-center gap-3 rounded-lg bg-slate-50 px-4 py-3 cursor-pointer">
            <input type="checkbox" name="is_anonymous" value="1" x-model="anonymous" @checked(old('is_anonymous')) class="h-4 w-4 rounded text-brand-600 focus:ring-brand-500">
            <span class="text-sm text-slate-700">Submit anonymously <span class="text-slate-400">(personal details &amp; OTP become optional)</span></span>
        </label>

        <div class="grid gap-4 sm:grid-cols-3">
            <div>
                <label class="label">Name <span x-show="!anonymous" class="text-rose-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" class="input">
            </div>
            <div>
                <label class="label">Gender</label>
                <select name="gender" class="input">
                    <option value="">--</option>
                    @foreach (['Male','Female','Other'] as $g)<option @selected(old('gender')===$g)>{{ $g }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="label">Age</label>
                <input type="number" name="age" value="{{ old('age') }}" class="input" min="1" max="120">
            </div>
        </div>

        {{-- Mobile + OTP (demo) --}}
        <div x-show="!anonymous" class="rounded-xl border border-slate-200 p-4">
            <div class="grid gap-4 sm:grid-cols-3 items-end">
                <div class="sm:col-span-2">
                    <label class="label">Mobile <span class="text-rose-500">*</span></label>
                    <div class="flex gap-2">
                        <input type="text" x-model="mobile" :disabled="verified" class="input" placeholder="10-digit mobile" maxlength="10">
                        <button type="button" @click="sendOtp()" :disabled="verified || sending"
                                class="btn btn-outline whitespace-nowrap" x-show="!verified">
                            <span x-show="!sending">Send OTP</span><span x-show="sending">Sending…</span>
                        </button>
                        <span x-show="verified" x-cloak class="btn btn-success whitespace-nowrap"><x-icon name="check" class="w-4 h-4" /> Verified</span>
                    </div>
                </div>
                <div x-show="otpSent && !verified" x-cloak>
                    <label class="label">Enter OTP</label>
                    <div class="flex gap-2">
                        <input type="text" x-model="otp" class="input" placeholder="6-digit" maxlength="6">
                        <button type="button" @click="verifyOtp()" class="btn btn-primary">Verify</button>
                    </div>
                </div>
            </div>
            <p x-show="message" x-cloak class="mt-2 text-xs" :class="verified ? 'text-emerald-600' : 'text-slate-500'" x-text="message"></p>
            <p x-show="demoOtp" x-cloak class="mt-1 text-xs text-amber-600">Demo OTP (no SMS sent): <span class="font-bold" x-text="demoOtp"></span></p>
        </div>

        <div class="grid gap-4 sm:grid-cols-3">
            <div>
                <label class="label">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="input">
            </div>
            <div class="sm:col-span-2">
                <label class="label">Communication Address <span x-show="!anonymous" class="text-rose-500">*</span></label>
                <input type="text" name="address" value="{{ old('address') }}" class="input">
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-3">
            <div>
                <label class="label">Caste / Category</label>
                <input type="text" name="caste" value="{{ old('caste') }}" class="input">
            </div>
            <div>
                <label class="label">Place / Village <span class="text-rose-500">*</span></label>
                <input type="text" name="place_village" value="{{ old('place_village') }}" class="input" required>
            </div>
            <div>
                <label class="label">Beel <span class="text-slate-400 text-xs">(optional)</span></label>
                <select name="beel_id" class="input">
                    <option value="">-- Select Beel --</option>
                    @foreach ($beels as $b)<option value="{{ $b->id }}" @selected(old('beel_id')==$b->id)>{{ $b->name }}</option>@endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="label">Description of Complaint / Suggestion <span class="text-rose-500">*</span></label>
            <textarea name="description" class="input" rows="4" required placeholder="Please provide who, what, where and how.">{{ old('description') }}</textarea>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 items-center">
            <div>
                <label class="label">Supporting Documents</label>
                <input type="file" name="documents[]" class="input" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                <p class="mt-1 text-xs text-slate-400">Optional. PDF / image / doc, up to 5 MB each.</p>
            </div>
            <label class="flex items-center gap-3 mt-5">
                <input type="checkbox" name="is_confidential" value="1" @checked(old('is_confidential')) class="h-4 w-4 rounded text-brand-600 focus:ring-brand-500">
                <span class="text-sm text-slate-700">Mark as Confidential</span>
            </label>
        </div>

        <div class="flex justify-end border-t border-slate-100 pt-5">
            <button type="submit" class="btn btn-accent px-6 py-3 text-base"><x-icon name="send" class="w-5 h-5" /> Submit Grievance</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function grievanceForm() {
    return {
        anonymous: {{ old('is_anonymous') ? 'true' : 'false' }},
        mobile: @js(old('mobile', '')),
        otp: '', otpSent: false, verified: false, sending: false,
        message: '', demoOtp: '',
        async sendOtp() {
            if (!/^[6-9]\d{9}$/.test(this.mobile)) { this.message = 'Enter a valid 10-digit mobile number.'; return; }
            this.sending = true; this.message = '';
            try {
                const res = await fetch('{{ route('otp.send') }}', {
                    method: 'POST',
                    headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'},
                    body: JSON.stringify({ mobile: this.mobile })
                });
                const data = await res.json();
                if (res.ok) { this.otpSent = true; this.message = data.message; this.demoOtp = data.demo_otp || ''; }
                else { this.message = (data.errors?.mobile?.[0]) || data.message || 'Could not send OTP.'; }
            } catch (e) { this.message = 'Network error while sending OTP.'; }
            this.sending = false;
        },
        async verifyOtp() {
            try {
                const res = await fetch('{{ route('otp.verify') }}', {
                    method: 'POST',
                    headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'},
                    body: JSON.stringify({ mobile: this.mobile, otp: this.otp })
                });
                const data = await res.json();
                if (res.ok) { this.verified = true; this.message = data.message; this.demoOtp = ''; }
                else { this.message = data.message || 'Verification failed.'; }
            } catch (e) { this.message = 'Network error while verifying OTP.'; }
        }
    }
}
</script>
@endpush
@endsection

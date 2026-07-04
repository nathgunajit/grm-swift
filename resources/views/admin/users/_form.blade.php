<div class="row g-3">
    <div class="col-md-4"><label class="form-label">EMPID</label><input name="empid" value="{{ old('empid', $user->empid ?? '') }}" class="form-control"></div>
    <div class="col-md-4"><label class="form-label">Name <span class="text-danger">*</span></label><input name="name" value="{{ old('name', $user->name ?? '') }}" class="form-control" required></div>
    <div class="col-md-4"><label class="form-label">Designation</label><input name="designation" value="{{ old('designation', $user->designation ?? '') }}" class="form-control"></div>

    <div class="col-md-4"><label class="form-label">Email <span class="text-danger">*</span></label><input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="form-control" required></div>
    <div class="col-md-4"><label class="form-label">Mobile</label><input name="mobile" value="{{ old('mobile', $user->mobile ?? '') }}" class="form-control" placeholder="10-digit"></div>
    <div class="col-md-4"><label class="form-label">User Type / Role <span class="text-danger">*</span></label>
        <select name="user_type_id" class="form-select" required>
            <option value="">-- Select --</option>
            @foreach ($userTypes as $t)<option value="{{ $t->id }}" @selected(old('user_type_id', $user->user_type_id ?? '')==$t->id)>{{ $t->name }}</option>@endforeach
        </select></div>

    <div class="col-md-4"><label class="form-label">District</label>
        <select name="district_id" class="form-select"><option value="">--</option>@foreach ($districts as $d)<option value="{{ $d->id }}" @selected(old('district_id', $user->district_id ?? '')==$d->id)>{{ $d->name }}</option>@endforeach</select></div>
    <div class="col-md-4"><label class="form-label">CPIU</label>
        <select name="cpiu_id" class="form-select"><option value="">--</option>@foreach ($cpius as $c)<option value="{{ $c->id }}" @selected(old('cpiu_id', $user->cpiu_id ?? '')==$c->id)>{{ $c->name }}</option>@endforeach</select></div>
    <div class="col-md-4"><label class="form-label">Beel (if Beel Animator)</label>
        <select name="beel_id" class="form-select"><option value="">--</option>@foreach ($beels as $b)<option value="{{ $b->id }}" @selected(old('beel_id', $user->beel_id ?? '')==$b->id)>{{ $b->name }}</option>@endforeach</select></div>

    <div class="col-md-8"><label class="form-label">Office Address</label><textarea name="office_address" class="form-control" rows="1">{{ old('office_address', $user->office_address ?? '') }}</textarea></div>
    <div class="col-md-4"><label class="form-label">Password @if(!isset($user))<span class="text-danger">*</span>@else<span class="small text-muted">(leave blank to keep)</span>@endif</label>
        <input type="password" name="password" class="form-control" {{ isset($user) ? '' : 'required' }}></div>

    @isset($user)
    <div class="col-12"><div class="form-check"><input class="form-check-input" type="checkbox" name="is_active" value="1" id="act" @checked($user->is_active)><label class="form-check-label" for="act">Active</label></div></div>
    @else
    <div class="col-md-4"><label class="form-label">Assign Date</label><input type="date" name="assign_date" value="{{ old('assign_date', date('Y-m-d')) }}" class="form-control"></div>
    @endisset
</div>

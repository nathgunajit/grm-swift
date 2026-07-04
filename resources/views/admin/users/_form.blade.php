<div class="grid gap-4 sm:grid-cols-3">
    <div><label class="label">EMPID</label><input name="empid" value="{{ old('empid', $user->empid ?? '') }}" class="input"></div>
    <div><label class="label">Name <span class="text-rose-500">*</span></label><input name="name" value="{{ old('name', $user->name ?? '') }}" class="input" required></div>
    <div><label class="label">Designation</label><input name="designation" value="{{ old('designation', $user->designation ?? '') }}" class="input"></div>

    <div><label class="label">Email <span class="text-rose-500">*</span></label><input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="input" required></div>
    <div><label class="label">Mobile</label><input name="mobile" value="{{ old('mobile', $user->mobile ?? '') }}" class="input" placeholder="10-digit"></div>
    <div><label class="label">User Type / Role <span class="text-rose-500">*</span></label>
        <select name="user_type_id" class="input" required><option value="">-- Select --</option>@foreach ($userTypes as $t)<option value="{{ $t->id }}" @selected(old('user_type_id', $user->user_type_id ?? '')==$t->id)>{{ $t->name }}</option>@endforeach</select></div>

    <div><label class="label">District</label><select name="district_id" class="input"><option value="">--</option>@foreach ($districts as $d)<option value="{{ $d->id }}" @selected(old('district_id', $user->district_id ?? '')==$d->id)>{{ $d->name }}</option>@endforeach</select></div>
    <div><label class="label">CPIU</label><select name="cpiu_id" class="input"><option value="">--</option>@foreach ($cpius as $c)<option value="{{ $c->id }}" @selected(old('cpiu_id', $user->cpiu_id ?? '')==$c->id)>{{ $c->name }}</option>@endforeach</select></div>
    <div><label class="label">Beel (if Beel Animator)</label><select name="beel_id" class="input"><option value="">--</option>@foreach ($beels as $b)<option value="{{ $b->id }}" @selected(old('beel_id', $user->beel_id ?? '')==$b->id)>{{ $b->name }}</option>@endforeach</select></div>

    <div class="sm:col-span-2"><label class="label">Office Address</label><input name="office_address" value="{{ old('office_address', $user->office_address ?? '') }}" class="input"></div>
    <div><label class="label">Password @if(!isset($user))<span class="text-rose-500">*</span>@else<span class="text-xs text-slate-400">(blank = keep)</span>@endif</label>
        <input type="password" name="password" class="input" {{ isset($user) ? '' : 'required' }}></div>

    @isset($user)
        <label class="flex items-center gap-2 text-sm sm:col-span-3"><input type="checkbox" name="is_active" value="1" @checked($user->is_active) class="h-4 w-4 rounded text-brand-600"> Active</label>
    @else
        <div><label class="label">Assign Date</label><input type="date" name="assign_date" value="{{ old('assign_date', date('Y-m-d')) }}" class="input"></div>
    @endisset
</div>

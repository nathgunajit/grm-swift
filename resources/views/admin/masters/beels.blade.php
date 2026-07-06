@extends('layouts.admin')
@section('title', 'Beels')
@section('heading', 'Beels')

@section('content')
@if ($errors->any())<div class="mb-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 px-4 py-2 text-sm">{{ $errors->first() }}</div>@endif
<div class="grid gap-5 lg:grid-cols-3">
    <div class="card card-pad">
        <h3 class="font-semibold text-slate-800 mb-3">Add Beel</h3>
        <form method="POST" action="{{ route('admin.beels.store') }}" class="space-y-3">
            @csrf
            <div><label class="label">Name</label><input name="name" class="input" required></div>
            <div><label class="label">District</label><select name="district_id" class="input"><option value="">--</option>@foreach ($districts as $d)<option value="{{ $d->id }}">{{ $d->name }}</option>@endforeach</select></div>
            <div><label class="label">Block</label><select name="block_id" class="input"><option value="">--</option>@foreach ($blocks as $b)<option value="{{ $b->id }}">{{ $b->name }}</option>@endforeach</select></div>
            <div><label class="label">CPIU</label><select name="cpiu_id" class="input"><option value="">--</option>@foreach ($cpius as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach</select></div>
            <div class="grid grid-cols-2 gap-2">
                <div><label class="label">Latitude</label><input name="latitude" type="number" step="any" class="input" placeholder="26.1197"></div>
                <div><label class="label">Longitude</label><input name="longitude" type="number" step="any" class="input" placeholder="91.6533"></div>
            </div>
            <button class="btn btn-primary btn-sm w-full"><x-icon name="plus" class="w-4 h-4" /> Add</button>
        </form>
    </div>
    <div class="lg:col-span-2">
        @foreach ($beels as $b)
            <form id="up-{{ $b->id }}" method="POST" action="{{ route('admin.beels.update', $b) }}">@csrf @method('PUT')</form>
            <form id="del-{{ $b->id }}" method="POST" action="{{ route('admin.beels.destroy', $b) }}" onsubmit="return confirm('Delete this beel?')">@csrf @method('DELETE')</form>
        @endforeach
        <div class="card overflow-x-auto">
            <table class="table-grm">
                <thead><tr><th>Name</th><th>District</th><th>Block</th><th>CPIU</th><th>Latitude</th><th>Longitude</th><th>Map</th><th></th></tr></thead>
                <tbody>
                    @foreach ($beels as $b)
                        <tr>
                            <td><input form="up-{{ $b->id }}" name="name" value="{{ $b->name }}" class="input min-w-32"></td>
                            <td><select form="up-{{ $b->id }}" name="district_id" class="input"><option value="">--</option>@foreach ($districts as $d)<option value="{{ $d->id }}" @selected($b->district_id==$d->id)>{{ $d->name }}</option>@endforeach</select></td>
                            <td><select form="up-{{ $b->id }}" name="block_id" class="input"><option value="">--</option>@foreach ($blocks as $bl)<option value="{{ $bl->id }}" @selected($b->block_id==$bl->id)>{{ $bl->name }}</option>@endforeach</select></td>
                            <td><select form="up-{{ $b->id }}" name="cpiu_id" class="input"><option value="">--</option>@foreach ($cpius as $c)<option value="{{ $c->id }}" @selected($b->cpiu_id==$c->id)>{{ $c->name }}</option>@endforeach</select></td>
                            <td><input form="up-{{ $b->id }}" name="latitude" value="{{ $b->latitude }}" class="input w-24" placeholder="lat"></td>
                            <td><input form="up-{{ $b->id }}" name="longitude" value="{{ $b->longitude }}" class="input w-24" placeholder="lng"></td>
                            <td>
                                @if ($b->latitude && $b->longitude)
                                    <a href="https://www.google.com/maps?q={{ $b->latitude }},{{ $b->longitude }}" target="_blank" class="text-brand-600" title="View on map"><x-icon name="map-pin" class="w-5 h-5" /></a>
                                @else <span class="text-slate-300">—</span> @endif
                            </td>
                            <td class="whitespace-nowrap">
                                <button form="up-{{ $b->id }}" class="btn btn-sm btn-outline"><x-icon name="check" class="w-4 h-4" /></button>
                                <button form="del-{{ $b->id }}" class="btn btn-sm btn-danger"><x-icon name="trash" class="w-4 h-4" /></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $beels->links() }}</div>
    </div>
</div>
@endsection

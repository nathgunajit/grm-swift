<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    @page { margin: 0; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color:#222; margin:0; }
    .page { padding: 28px 34px; position: relative; }
    .watermark {
        position: fixed; top: 42%; left: 8%; width: 84%;
        transform: rotate(-30deg); text-align: center;
        color: #0b5e4f; opacity: 0.06; font-size: 30px; font-weight: bold; line-height:1.4; z-index:0;
    }
    .content { position: relative; z-index: 1; }
    .logos { width:100%; border-collapse:collapse; margin-bottom:6px; }
    .logos td { text-align:center; vertical-align:middle; width:33%; }
    .logos img { height:48px; }
    .org { text-align:center; border-bottom:2px solid #0b5e4f; padding-bottom:8px; margin-bottom:14px; }
    .org h2 { margin:2px 0; color:#0b5e4f; font-size:15px; }
    .org p { margin:1px 0; font-size:10px; }
    h3 { text-align:center; margin:10px 0; text-transform:uppercase; letter-spacing:1px; color:#0b5e4f; }
    table.details { width:100%; border-collapse:collapse; margin-top:10px; }
    table.details td { padding:6px 8px; border:1px solid #cfd8d5; vertical-align:top; }
    td.label { background:#eef5f2; width:35%; font-weight:bold; }
    .resolution { margin-top:14px; padding:12px; background:#f0f8f5; border:1px solid #b8ddd2; }
    .foot { margin-top:26px; font-size:11px; }
</style>
</head>
<body>
<div class="watermark">Assam Sustainable Wetland and Integrated Fisheries Transformation (SWIFT) Project.</div>
<div class="page">
<div class="content">
    <table class="logos">
        <tr>
            <td><img src="{{ public_path('images/arias-logo.png') }}" alt="ARIAS"></td>
            <td><img src="{{ public_path('images/swift-logo.png') }}" alt="SWIFT" style="height:56px;"></td>
            <td><img src="{{ public_path('images/assam-govt-logo.png') }}" alt="Govt of Assam"></td>
        </tr>
    </table>
    <div class="org">
        <h2>ARIAS Society — SWIFT Project</h2>
        <p>Assam Sustainable Wetland and Integrated Fisheries Transformation (SWIFT) Project</p>
        <p>Grievance Redressal Mechanism (GRM)</p>
    </div>

    <h3>Grievance Resolution Record</h3>

    <table class="details">
        <tr><td class="label">Grievance / Tracking ID</td><td>{{ $grievance->tracking_id }}</td></tr>
        <tr><td class="label">Complainant</td><td>{{ $grievance->is_anonymous ? 'Anonymous' : ($grievance->name ?? '—') }}</td></tr>
        <tr><td class="label">Village / Beel</td><td>{{ $grievance->place_village }} @if($grievance->beel) / {{ $grievance->beel->name }} @endif</td></tr>
        <tr><td class="label">Category</td><td>{{ $grievance->category?->name ?? '—' }}</td></tr>
        <tr><td class="label">Resolved at Level</td><td>{{ $grievance->levelLabel() }}</td></tr>
        <tr><td class="label">Date of Resolution</td><td>{{ optional($grievance->resolved_at)->format('d M Y') ?? '—' }}</td></tr>
        <tr><td class="label">Summary of Grievance</td><td>{{ $grievance->description }}</td></tr>
    </table>

    <div class="resolution">
        <strong>Decision / Resolution:</strong>
        <p style="margin:6px 0 0;">{{ $grievance->resolution ?? 'Resolution recorded.' }}</p>
    </div>

    <div class="foot">
        <p>This decision has been communicated to the complainant. If dissatisfied, the complainant may reopen the grievance for escalation to the next level, or approach the ADB Accountability Mechanism.</p>
        <p>Signatures of GRC Members: 1. ______________ 2. ______________ 3. ______________</p>
        <p style="text-align:right;color:#777;">Generated on {{ now()->format('d M Y, h:i A') }} — system-generated record.</p>
    </div>
</div>
</div>
</body>
</html>

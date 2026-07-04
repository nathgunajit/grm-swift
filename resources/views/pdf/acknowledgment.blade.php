<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color:#222; }
    .header { text-align:center; border-bottom:2px solid #0b5e4f; padding-bottom:8px; margin-bottom:14px; }
    .header h2 { margin:0; color:#0b5e4f; }
    .header p { margin:2px 0; font-size:11px; }
    h3 { text-align:center; margin:10px 0; text-transform:uppercase; letter-spacing:1px; }
    table { width:100%; border-collapse:collapse; margin-top:10px; }
    td { padding:6px 8px; border:1px solid #cfd8d5; vertical-align:top; }
    td.label { background:#eef5f2; width:35%; font-weight:bold; }
    .note { margin-top:14px; padding:10px; background:#eef5f2; border-left:3px solid #0b5e4f; font-size:11px; }
    .foot { margin-top:30px; font-size:11px; }
    .track { font-size:16px; color:#0b5e4f; font-weight:bold; }
</style>
</head>
<body>
    <div class="header">
        <h2>ARIAS Society — SWIFT Project</h2>
        <p>Assam Sustainable Wetland and Integrated Fisheries Transformation (SWIFT) Project</p>
        <p>Grievance Redressal Mechanism (GRM)</p>
    </div>

    <h3>Grievance Acknowledgment Slip</h3>

    <table>
        <tr><td class="label">Grievance / Tracking ID</td><td class="track">{{ $grievance->tracking_id }}</td></tr>
        <tr><td class="label">Acknowledgment No.</td><td>{{ $grievance->acknowledgment_no }}</td></tr>
        <tr><td class="label">Date of Receipt</td><td>{{ $grievance->created_at->format('d M Y, h:i A') }}</td></tr>
        <tr><td class="label">Received From</td><td>{{ $grievance->is_anonymous ? 'Anonymous' : ($grievance->name ?? '—') }}</td></tr>
        <tr><td class="label">Village / Beel</td><td>{{ $grievance->place_village }} @if($grievance->beel) / {{ $grievance->beel->name }} @endif</td></tr>
        <tr><td class="label">Category</td><td>{{ $grievance->category?->name ?? '—' }}</td></tr>
        <tr><td class="label">Mode of Receipt</td><td>{{ ucfirst($grievance->mode_of_receipt) }}</td></tr>
        <tr><td class="label">Brief Subject</td><td>{{ \Illuminate\Support\Str::limit($grievance->description, 400) }}</td></tr>
    </table>

    <div class="note">
        Your grievance has been registered and will be reviewed under the SWIFT Grievance Redressal Mechanism.
        <br><strong>Expected time for initial decision: within 7 days (field level).</strong>
        <br>Please retain this slip. You may track the status at the project GRM portal using your Tracking ID or mobile number.
    </div>

    <div class="foot">
        <p>Signature of Receiving Officer: ______________________________</p>
        <p>Name &amp; Designation: ______________________________</p>
        <p style="text-align:right;color:#777;">Generated on {{ now()->format('d M Y, h:i A') }} — system-generated acknowledgment.</p>
    </div>
</body>
</html>

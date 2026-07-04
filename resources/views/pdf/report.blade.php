<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color:#222; }
    .logos { width:100%; border-collapse:collapse; margin-bottom:6px; }
    .logos td { text-align:center; vertical-align:middle; width:33%; }
    .logos img { height:42px; }
    .header { text-align:center; border-bottom:2px solid #0b5e4f; padding-bottom:8px; margin-bottom:14px; }
    .header h2 { margin:0; color:#0b5e4f; }
    h4 { color:#0b5e4f; margin:16px 0 6px; border-bottom:1px solid #cfd8d5; padding-bottom:3px; }
    table { width:100%; border-collapse:collapse; margin-top:6px; }
    td, th { padding:5px 8px; border:1px solid #cfd8d5; text-align:left; }
    th { background:#eef5f2; }
    .kpi td { width:25%; text-align:center; }
    .kpi .v { font-size:16px; color:#0b5e4f; font-weight:bold; }
</style>
</head>
<body>
    <table class="logos">
        <tr>
            <td><img src="{{ public_path('images/arias-logo.png') }}" alt="ARIAS"></td>
            <td><img src="{{ public_path('images/swift-logo.png') }}" alt="SWIFT" style="height:50px;"></td>
            <td><img src="{{ public_path('images/assam-govt-logo.png') }}" alt="Govt of Assam"></td>
        </tr>
    </table>
    <div class="header">
        <h2>SWIFT GRM — Monitoring Report</h2>
        <p>Assam Sustainable Wetland and Integrated Fisheries Transformation (SWIFT) Project</p>
        <p>Generated on {{ now()->format('d M Y, h:i A') }}</p>
    </div>

    <table class="kpi">
        <tr>
            <td><div class="v">{{ $total }}</div>Total</td>
            <td><div class="v">{{ $resolved }}</div>Resolved</td>
            <td><div class="v">{{ $slaRate }}%</div>Within SLA</td>
            <td><div class="v">{{ $avgDays }}</div>Avg. days</td>
        </tr>
    </table>

    <h4>By Status</h4>
    <table><tr><th>Status</th><th>Count</th></tr>
        @foreach ($byStatus as $k => $v)<tr><td>{{ $statusLabels[$k] ?? $k }}</td><td>{{ $v }}</td></tr>@endforeach
    </table>

    <h4>By Category</h4>
    <table><tr><th>Category</th><th>Count</th></tr>
        @foreach ($byCategory as $k => $v)<tr><td>{{ $k }}</td><td>{{ $v }}</td></tr>@endforeach
    </table>

    <h4>By Level</h4>
    <table><tr><th>Level</th><th>Count</th></tr>
        @foreach ($byLevel as $k => $v)<tr><td>Level {{ $k }}</td><td>{{ $v }}</td></tr>@endforeach
    </table>

    <h4>By District</h4>
    <table><tr><th>District</th><th>Count</th></tr>
        @foreach ($byDistrict as $k => $v)<tr><td>{{ $k }}</td><td>{{ $v }}</td></tr>@endforeach
    </table>

    <h4>Feedback — Satisfaction</h4>
    <table><tr><th>Satisfaction</th><th>Count</th></tr>
        @forelse ($satisfaction as $k => $v)<tr><td>{{ ucfirst($k) }}</td><td>{{ $v }}</td></tr>@empty<tr><td colspan="2">No feedback recorded.</td></tr>@endforelse
    </table>
</body>
</html>

<?php

namespace App\Http\Controllers;

use App\Models\Grievance;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'total' => Grievance::count(),
            'registered' => Grievance::where('status', 'registered')->count(),
            'under_review' => Grievance::where('status', 'under_review')->count(),
            'escalated' => Grievance::where('status', 'escalated')->count(),
            'resolved' => Grievance::whereIn('status', ['resolved', 'closed'])->count(),
        ];

        return view('public.home', compact('stats'));
    }

    public function process()
    {
        return view('public.process');
    }

    public function faq()
    {
        return view('public.faq');
    }

    public function resources()
    {
        return view('public.resources');
    }

    public function privacy()
    {
        return view('public.privacy');
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function downloadDoc(string $file)
    {
        $allowed = ['FINAL GRM Manual.pdf', 'GRM.docx', 'GRM Pages.xlsx'];
        abort_unless(in_array($file, $allowed, true), 404);
        $path = base_path('docs/'.$file);
        abort_unless(is_file($path), 404);

        return response()->download($path);
    }
}

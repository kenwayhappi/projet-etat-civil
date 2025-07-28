<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Act;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ActsExport;

class ReportController extends Controller
{
    public function statistics()
    {
        $stats = [
            'total_births' => Act::where('type', 'birth')->count(),
            'total_marriages' => Act::where('type', 'marriage')->count(),
            'total_deaths' => Act::where('type', 'death')->count(),
            'total_divorces' => Act::where('type', 'divorce')->count(),
            'pending' => Act::where('status', 'pending')->count(),
            'validated' => Act::where('status', 'validated')->count(),
            'rejected' => Act::where('status', 'rejected')->count(),
        ];

        return view('admin.reports.statistics', compact('stats'));
    }

    public function exports()
    {
        $acts = Act::with('center.department.region')->get();
        return view('admin.reports.exports', compact('acts'));
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new ActsExport, 'actes_' . now()->format('YmdHis') . '.xlsx');
    }
}

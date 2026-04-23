<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Visit;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPatients = Patient::count();
        $totalVisits = Visit::count();
        $monthlyVisits = Visit::whereMonth('tanggal_berobat', date('m'))
            ->whereYear('tanggal_berobat', date('Y'))
            ->count();

        // Chart data for last 12 months
        $months = [];
        $visitsData = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            $count = Visit::whereMonth('tanggal_berobat', $date->month)
                ->whereYear('tanggal_berobat', $date->year)
                ->count();
            $visitsData[] = $count;
        }

        $recentVisits = Visit::with('patient')
            ->orderBy('tanggal_berobat', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalPatients',
            'totalVisits',
            'monthlyVisits',
            'months',
            'visitsData',
            'recentVisits'
        ));
    }
}

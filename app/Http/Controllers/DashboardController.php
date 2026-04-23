<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Visit;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Data untuk dashboard
        $totalPatients = Patient::count();
        $totalVisits = Visit::count();
        $monthlyVisits = Visit::whereMonth('tanggal_berobat', date('m'))
            ->whereYear('tanggal_berobat', date('Y'))
            ->count();

        // Data untuk chart (12 bulan terakhir)
        $chartData = $this->getChartData();

        // Data untuk tabel
        $recentVisits = Visit::with('patient')
            ->orderBy('tanggal_berobat', 'desc')
            ->limit(5)
            ->get();

        $allPatients = Patient::orderBy('created_at', 'desc')->get();
        $allVisits = Visit::with('patient')
            ->orderBy('tanggal_berobat', 'desc')
            ->get();

        $oldVisits = Visit::with('patient')
            ->where('tanggal_berobat', '<', now()->subMonths(3))
            ->orderBy('tanggal_berobat', 'desc')
            ->get();

        // Get unique diagnoses for filter dropdown
        $uniqueDiagnoses = Visit::select('diagnostik')
            ->whereNotNull('diagnostik')
            ->where('diagnostik', '!=', '')
            ->distinct()
            ->pluck('diagnostik')
            ->toArray();

        return view('dashboard', compact(
            'totalPatients',
            'totalVisits',
            'monthlyVisits',
            'chartData',
            'recentVisits',
            'allPatients',
            'allVisits',
            'oldVisits',
            'uniqueDiagnoses'
        ));
    }

    private function getChartData()
    {
        $months = [];
        $visits = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');

            $count = Visit::whereMonth('tanggal_berobat', $date->month)
                ->whereYear('tanggal_berobat', $date->year)
                ->count();

            $visits[] = $count;
        }

        return [
            'months' => $months,
            'visits' => $visits
        ];
    }
}

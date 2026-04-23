<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Visit;
use Illuminate\Http\Request;

class VisitController extends Controller
{
    public function index(Request $request)
    {
        $query = Visit::with('patient');

        if ($request->filled('patient_name')) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->patient_name . '%');
            });
        }

        if ($request->filled('diagnosis')) {
            $query->where('diagnostik', 'like', '%' . $request->diagnosis . '%');
        }

        if ($request->filled('date_from')) {
            $query->whereDate('tanggal_berobat', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('tanggal_berobat', '<=', $request->date_to);
        }

        $visits = $query->orderBy('tanggal_berobat', 'desc')->get();

        if ($request->ajax()) {
            return response()->json(['visits' => $visits]);
        }

        return view('dashboard', compact('visits'));
    }

    // API method for filtering
    public function filter(Request $request)
    {
        $query = Visit::with('patient');

        if ($request->filled('patient_name')) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->patient_name . '%');
            });
        }

        if ($request->filled('diagnosis')) {
            $query->where('diagnostik', 'like', '%' . $request->diagnosis . '%');
        }

        if ($request->filled('date_from')) {
            $query->whereDate('tanggal_berobat', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('tanggal_berobat', '<=', $request->date_to);
        }

        $visits = $query->orderBy('tanggal_berobat', 'desc')->get();

        return response()->json(['visits' => $visits]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'tanggal_berobat' => 'required|date',
            'keluhan' => 'nullable|string',
            'anamesis' => 'nullable|string',
            'pemeriksaan_fisik' => 'nullable|string',
            'pemeriksaan_lab' => 'nullable|string',
            'diagnostik' => 'nullable|string',
            'terapi' => 'nullable|string',
            'riwayat_alergi' => 'nullable|string',
        ]);

        try {
            Visit::create($request->all());
            return redirect()->back()->with('success', 'Kunjungan berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan kunjungan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $visit = Visit::with('patient')->findOrFail($id);
        return response()->json($visit);
    }
}

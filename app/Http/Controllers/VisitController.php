<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class VisitController extends Controller
{
    public function index(Request $request)
    {
        $query = Visit::with('patient');

        // Filter by patient_id jika ada
        if ($request->has('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        $visits = $query->orderBy('tanggal_berobat', 'desc')->get();
        $patients = Patient::orderBy('nama')->get();

        return view('visits', compact('visits', 'patients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'tanggal_berobat' => 'required|date',
            'keluhan' => 'nullable',
            'anamesis' => 'nullable',
            'pemeriksaan_fisik' => 'nullable',
            'pemeriksaan_lab' => 'nullable',
            'diagnostik' => 'nullable',
            'terapi' => 'nullable',
            'riwayat_alergi' => 'nullable',
        ]);

        try {
            Visit::create($request->all());

            // Redirect back ke halaman sebelumnya atau ke detail pasien
            if ($request->has('from_patient')) {
                return redirect()->route('patients.show', $request->patient_id)
                    ->with('success', 'Kunjungan berhasil ditambahkan!');
            }

            return redirect()->route('visits')->with('success', 'Kunjungan berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan kunjungan!');
        }
    }

    public function show($id)
    {
        $visit = Visit::with('patient')->findOrFail($id);

        if (request()->ajax()) {
            return response()->json($visit);
        }

        return response()->json($visit);
    }

    public function filter(Request $request)
    {
        $query = Visit::with('patient');

        if ($request->patient_name) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->patient_name . '%');
            });
        }

        if ($request->diagnosis) {
            $query->where('diagnostik', 'like', '%' . $request->diagnosis . '%');
        }

        if ($request->date_from) {
            $query->whereDate('tanggal_berobat', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('tanggal_berobat', '<=', $request->date_to);
        }

        $visits = $query->orderBy('tanggal_berobat', 'desc')->get();
        return response()->json(['visits' => $visits]);
    }
}

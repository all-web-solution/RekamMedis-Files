<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::orderBy('created_at', 'desc')->get();
        return view('patients', compact('patients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|unique:patients|max:20',
            'nama' => 'required|max:255',
            'umur' => 'required|integer|min:0|max:150',
            'jenis_kelamin' => 'required',
            'tinggi' => 'nullable|numeric',
            'berat' => 'nullable|numeric',
        ]);

        try {
            Patient::create($request->all());
            return redirect()->route('patients')->with('success', 'Pasien berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan pasien!');
        }
    }

    public function show($id)
    {
        // Untuk AJAX request (modal)
        if (request()->ajax() && request()->has('modal')) {
            $patient = Patient::findOrFail($id);
            return response()->json($patient);
        }

        // Untuk halaman detail penuh
        $patient = Patient::findOrFail($id);
        $visits = Visit::where('patient_id', $id)
            ->orderBy('tanggal_berobat', 'desc')
            ->get();

        // Statistik kunjungan
        $totalVisits = $visits->count();
        $lastVisit = $visits->first();
        $firstVisit = $visits->last();

        // Kunjungan per tahun
        $visitsPerYear = $visits->groupBy(function ($visit) {
            return date('Y', strtotime($visit->tanggal_berobat));
        })->map(function ($yearVisits) {
            return $yearVisits->count();
        });

        // Diagnosa paling sering
        $commonDiagnosis = $visits->groupBy('diagnostik')
            ->map(function ($group) {
                return $group->count();
            })
            ->sortDesc()
            ->take(5);

        return view('patient-detail', compact(
            'patient',
            'visits',
            'totalVisits',
            'lastVisit',
            'firstVisit',
            'visitsPerYear',
            'commonDiagnosis'
        ));
    }

    public function edit($id)
    {
        $patient = Patient::findOrFail($id);
        return response()->json($patient);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nik' => 'required|max:20|unique:patients,nik,' . $id,
            'nama' => 'required|max:255',
            'umur' => 'required|integer|min:0|max:150',
            'jenis_kelamin' => 'required',
            'tinggi' => 'nullable|numeric',
            'berat' => 'nullable|numeric',
        ]);

        try {
            $patient = Patient::findOrFail($id);
            $patient->update($request->all());
            return redirect()->route('patients')->with('success', 'Pasien berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengupdate pasien!');
        }
    }

    public function destroy($id)
    {
        try {
            $patient = Patient::findOrFail($id);

            if ($patient->visits()->count() > 0) {
                return redirect()->route('patients')->with('error', 'Tidak bisa menghapus pasien yang memiliki riwayat kunjungan!');
            }

            $patient->delete();
            return redirect()->route('patients')->with('success', 'Pasien berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('patients')->with('error', 'Gagal menghapus pasien!');
        }
    }
}

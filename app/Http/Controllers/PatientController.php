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
        return response()->json($patients);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'umur' => 'required|integer|min:0|max:150',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'nik' => 'required|string|max:20|unique:patients,nik',
            'tinggi' => 'nullable|numeric|min:0|max:300',
            'berat' => 'nullable|numeric|min:0|max:500',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal!');
        }

        try {
            $patient = Patient::create($request->all());
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pasien berhasil ditambahkan!',
                    'patient' => $patient
                ]);
            }
            return redirect()->route('dashboard')
                ->with('success', 'Pasien berhasil ditambahkan!');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan pasien: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()
                ->with('error', 'Gagal menambahkan pasien: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'umur' => 'required|integer|min:0|max:150',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'nik' => 'required|string|max:20|unique:patients,nik,' . $id,
            'tinggi' => 'nullable|numeric|min:0|max:300',
            'berat' => 'nullable|numeric|min:0|max:500',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal!');
        }

        try {
            $patient->update($request->all());
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data pasien berhasil diperbarui!',
                    'patient' => $patient
                ]);
            }
            return redirect()->route('dashboard')
                ->with('success', 'Data pasien berhasil diperbarui!');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui pasien: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()
                ->with('error', 'Gagal memperbarui pasien: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $patient = Patient::findOrFail($id);

            if ($patient->visits()->count() > 0) {
                if (request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tidak dapat menghapus pasien karena memiliki riwayat kunjungan!'
                    ], 400);
                }
                return redirect()->back()
                    ->with('error', 'Tidak dapat menghapus pasien karena memiliki riwayat kunjungan!');
            }

            $patient->delete();
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pasien berhasil dihapus!'
                ]);
            }
            return redirect()->route('dashboard')
                ->with('success', 'Pasien berhasil dihapus!');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus pasien: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()
                ->with('error', 'Gagal menghapus pasien: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $patient = Patient::findOrFail($id);
        return response()->json($patient);
    }

    public function show($id)
    {
        $patient = Patient::findOrFail($id);
        $visits = Visit::where('patient_id', $id)
            ->orderBy('tanggal_berobat', 'desc')
            ->get();

        $totalVisits = $visits->count();
        $lastVisit = $visits->first();
        $firstVisit = $visits->last();

        $visitsPerYear = $visits->groupBy(function ($visit) {
            return date('Y', strtotime($visit->tanggal_berobat));
        })->map(function ($yearVisits) {
            return $yearVisits->count();
        });

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
}

<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PatientController extends Controller
{
    public function index(): View
    {
        $patients = Patient::orderByDesc('created_at')->get();

        return view('patients', compact('patients'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nik' => ['nullable', 'numeric', 'digits:16', 'unique:patients,nik'],
            'nama' => ['required', 'string', 'max:255'],
            'umur' => ['required', 'integer', 'min:0', 'max:150'],
            'jenis_kelamin' => ['required', 'string'],
            'tinggi' => ['nullable', 'numeric'],
            'berat' => ['nullable', 'numeric'],
        ]);

        try {
            Patient::create($validated);

            return redirect()
                ->route('patients')
                ->with('success', 'Pasien berhasil ditambahkan!');
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan pasien!');
        }
    }

    public function show(int $id)
    {
        if (request()->ajax() && request()->has('modal')) {
            $patient = Patient::findOrFail($id);

            return response()->json($patient);
        }

        $patient = Patient::findOrFail($id);

        $visits = Visit::where('patient_id', $patient->id)
            ->orderByDesc('tanggal_berobat')
            ->orderByDesc('id')
            ->get();

        $totalVisits = $visits->count();

        $visitsPerYear = $visits
            ->groupBy(fn($visit) => Carbon::parse($visit->tanggal_berobat)->format('Y'))
            ->map(fn($yearVisits) => $yearVisits->count());

        $commonDiagnosis = $visits
            ->filter(fn($visit) => filled($visit->diagnostik))
            ->groupBy('diagnostik')
            ->map(fn($group) => $group->count())
            ->sortDesc()
            ->take(5);

        return view('patient-detail', compact(
            'patient',
            'visits',
            'totalVisits',
            'visitsPerYear',
            'commonDiagnosis'
        ));
    }

    public function exportVisits(Patient $patient)
    {
        $fileName = 'riwayat-kunjungan-' . Str::slug($patient->nama) . '-' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($patient) {
            $handle = fopen('php://output', 'w');

            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'NIK',
                'Nama Pasien',
                'Umur',
                'Jenis Kelamin',
                'Tanggal Berobat',
                'Keluhan',
                'Anamesis',
                'Pemeriksaan Fisik',
                'Pemeriksaan Lab',
                'Diagnostik',
                'Terapi',
                'Riwayat Alergi',
            ]);

            Visit::where('patient_id', $patient->id)
                ->orderByDesc('tanggal_berobat')
                ->orderByDesc('id')
                ->chunk(500, function ($visits) use ($handle, $patient) {
                    foreach ($visits as $visit) {
                        fputcsv($handle, [
                            $patient->nik,
                            $patient->nama,
                            $patient->umur,
                            $patient->jenis_kelamin,
                            $visit->tanggal_berobat
                            ? Carbon::parse($visit->tanggal_berobat)->format('d/m/Y')
                            : '-',
                            $visit->keluhan,
                            $visit->anamesis,
                            $visit->pemeriksaan_fisik,
                            $visit->pemeriksaan_lab,
                            $visit->diagnostik,
                            $visit->terapi,
                            $visit->riwayat_alergi,
                        ]);
                    }
                });

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function edit(int $id)
    {
        $patient = Patient::findOrFail($id);

        return response()->json($patient);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'nik' => ['nullable', 'numeric', 'digits:16', 'unique:patients,nik,' . $id],
            'nama' => ['required', 'string', 'max:255'],
            'umur' => ['required', 'integer', 'min:0', 'max:150'],
            'jenis_kelamin' => ['required', 'string'],
            'tinggi' => ['nullable', 'numeric'],
            'berat' => ['nullable', 'numeric'],
        ]);

        try {
            $patient = Patient::findOrFail($id);
            $patient->update($validated);

            return redirect()
                ->route('patients.show', $patient->id)
                ->with('success', 'Data pasien berhasil diupdate!');
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate pasien!');
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        try {
            DB::transaction(function () use ($id) {
                $patient = Patient::findOrFail($id);

                Visit::where('patient_id', $patient->id)->delete();

                $patient->delete();
            });

            return redirect()
                ->route('patients')
                ->with('success', 'Pasien dan seluruh riwayat kunjungannya berhasil dihapus!');
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus pasien!');
        }
    }
}

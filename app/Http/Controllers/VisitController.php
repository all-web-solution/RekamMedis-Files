<?php

namespace App\Http\Controllers;

use App\Http\Requests\VisitFilterRequest;
use App\Models\Patient;
use App\Models\Visit;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VisitController extends Controller
{
    public function index(VisitFilterRequest $request): View
    {
        $filters = $request->validated();

        $visits = $this->filteredVisitQuery($filters)
            ->paginate(15)
            ->withQueryString();

        $patients = Patient::select('id', 'nama', 'nik')
            ->orderBy('nama')
            ->get();

        return view('visits', compact('visits', 'patients', 'filters'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'tanggal_berobat' => ['required', 'date'],
            'keluhan' => ['nullable', 'string'],
            'anamesis' => ['nullable', 'string'],
            'pemeriksaan_fisik' => ['nullable', 'string'],
            'pemeriksaan_lab' => ['nullable', 'string'],
            'diagnostik' => ['nullable', 'string'],
            'terapi' => ['nullable', 'string'],
            'riwayat_alergi' => ['nullable', 'string'],
            'from_patient' => ['nullable'],
        ]);

        Visit::create(collect($validated)->except('from_patient')->toArray());

        if ($request->filled('from_patient')) {
            return redirect()
                ->route('patients.show', $validated['patient_id'])
                ->with('success', 'Kunjungan berhasil ditambahkan!');
        }

        return redirect()
            ->route('visits')
            ->with('success', 'Kunjungan berhasil ditambahkan!');
    }

    public function show(int $id): JsonResponse
    {
        $visit = Visit::with('patient:id,nama,nik')->findOrFail($id);

        return response()->json($visit);
    }

    public function filter(VisitFilterRequest $request): JsonResponse
    {
        $visits = $this->filteredVisitQuery($request->validated())
            ->limit(100)
            ->get();

        return response()->json([
            'visits' => $visits,
        ]);
    }

    public function exportPdf(VisitFilterRequest $request)
    {
        $filters = $request->validated();
        $exportAll = $request->boolean('all');

        $query = $exportAll
            ? Visit::query()->with('patient:id,nama,nik')->orderByDesc('tanggal_berobat')->orderByDesc('id')
            : $this->filteredVisitQuery($filters);

        $visits = $query->get();

        $summary = [
            'total' => $visits->count(),
            'date_from' => $exportAll ? null : ($filters['date_from'] ?? null),
            'date_to' => $exportAll ? null : ($filters['date_to'] ?? null),
            'patient_name' => $exportAll ? null : ($filters['patient_name'] ?? null),
            'diagnosis' => $exportAll ? null : ($filters['diagnosis'] ?? null),
            'is_all' => $exportAll,
            'generated_at' => now(),
        ];

        $fileName = $exportAll
            ? 'seluruh-data-kunjungan-' . now()->format('Ymd_His') . '.pdf'
            : 'data-kunjungan-filter-' . now()->format('Ymd_His') . '.pdf';

        $pdf = Pdf::loadView('exports.visits-pdf', [
            'visits' => $visits,
            'summary' => $summary,
        ])->setPaper('a4', 'landscape');

        return $pdf->download($fileName);
    }

    private function filteredVisitQuery(array $filters): Builder
    {
        return Visit::query()
            ->with('patient:id,nama,nik')
            ->when($filters['patient_id'] ?? null, function (Builder $query, int $patientId) {
                $query->where('patient_id', $patientId);
            })
            ->when($filters['patient_name'] ?? null, function (Builder $query, string $keyword) {
                $query->whereHas('patient', function (Builder $patientQuery) use ($keyword) {
                    $patientQuery
                        ->where('nama', 'like', '%' . $keyword . '%')
                        ->orWhere('nik', 'like', '%' . $keyword . '%');
                });
            })
            ->when($filters['diagnosis'] ?? null, function (Builder $query, string $diagnosis) {
                $query->where('diagnostik', 'like', '%' . $diagnosis . '%');
            })
            ->when($filters['date_from'] ?? null, function (Builder $query, string $dateFrom) {
                $query->whereDate('tanggal_berobat', '>=', $dateFrom);
            })
            ->when($filters['date_to'] ?? null, function (Builder $query, string $dateTo) {
                $query->whereDate('tanggal_berobat', '<=', $dateTo);
            })
            ->orderByDesc('tanggal_berobat')
            ->orderByDesc('id');
    }
}

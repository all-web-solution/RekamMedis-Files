<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VisitFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'patient_id' => ['nullable', 'integer', 'exists:patients,id'],
            'patient_name' => ['nullable', 'string', 'max:100'],
            'diagnosis' => ['nullable', 'string', 'max:100'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ];
    }

    public function attributes(): array
    {
        return [
            'patient_id' => 'pasien',
            'patient_name' => 'nama pasien',
            'diagnosis' => 'diagnosis',
            'date_from' => 'tanggal awal',
            'date_to' => 'tanggal akhir',
        ];
    }
}

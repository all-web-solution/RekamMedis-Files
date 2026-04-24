<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DummyMedicalDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            foreach ($this->patients() as $patientData) {
                $patient = Patient::updateOrCreate(
                    ['nik' => $patientData['nik']],
                    $patientData
                );

                // Refresh hanya kunjungan milik pasien dummy ini agar tidak dobel saat seeder dijalankan ulang.
                Visit::where('patient_id', $patient->id)->delete();

                foreach ($this->makeVisitsForPatient($patient->id) as $visitData) {
                    Visit::create($visitData);
                }
            }
        });
    }

    private function patients(): array
    {
        return [
            [
                'nik' => '3374011203850001',
                'nama' => 'Budi Santoso',
                'umur' => 39,
                'jenis_kelamin' => 'Laki-laki',
                'tinggi' => 171,
                'berat' => 72,
            ],
            [
                'nik' => '3374021804920002',
                'nama' => 'Siti Aminah',
                'umur' => 34,
                'jenis_kelamin' => 'Perempuan',
                'tinggi' => 158,
                'berat' => 56,
            ],
            [
                'nik' => '3374032501790003',
                'nama' => 'Agus Prasetyo',
                'umur' => 47,
                'jenis_kelamin' => 'Laki-laki',
                'tinggi' => 168,
                'berat' => 76,
            ],
            [
                'nik' => '3374040905010004',
                'nama' => 'Dewi Lestari',
                'umur' => 25,
                'jenis_kelamin' => 'Perempuan',
                'tinggi' => 162,
                'berat' => 52,
            ],
            [
                'nik' => '3374053012670005',
                'nama' => 'Hadi Wijaya',
                'umur' => 58,
                'jenis_kelamin' => 'Laki-laki',
                'tinggi' => 165,
                'berat' => 69,
            ],
        ];
    }

    private function makeVisitsForPatient(int $patientId): array
    {
        $visitTemplates = [
            [
                'keluhan' => 'Demam sejak 2 hari, badan terasa lemas, dan nafsu makan menurun.',
                'anamesis' => 'Pasien mengeluhkan demam naik turun, tidak disertai sesak napas. Riwayat kontak dengan keluarga yang batuk pilek.',
                'pemeriksaan_fisik' => 'Keadaan umum cukup, suhu 38.2°C, nadi 88x/menit, faring hiperemis ringan.',
                'pemeriksaan_lab' => 'Tidak dilakukan pemeriksaan laboratorium.',
                'diagnostik' => 'Febris suspek infeksi saluran napas atas',
                'terapi' => 'Paracetamol 500 mg 3x1 bila demam, vitamin C 1x1, istirahat cukup, dan banyak minum air putih.',
                'riwayat_alergi' => 'Tidak ada alergi obat yang diketahui.',
            ],
            [
                'keluhan' => 'Batuk berdahak dan pilek selama 4 hari.',
                'anamesis' => 'Batuk lebih sering pada malam hari, dahak putih, tidak ada nyeri dada, tidak ada riwayat asma.',
                'pemeriksaan_fisik' => 'Ronkhi kasar minimal, tidak ditemukan wheezing, saturasi baik.',
                'pemeriksaan_lab' => 'Tidak dilakukan pemeriksaan laboratorium.',
                'diagnostik' => 'ISPA ringan',
                'terapi' => 'Ambroxol 30 mg 3x1, CTM 1x1 malam bila pilek, edukasi etika batuk dan konsumsi cairan hangat.',
                'riwayat_alergi' => 'Tidak ada.',
            ],
            [
                'keluhan' => 'Nyeri ulu hati, mual, dan perut terasa begah setelah makan terlambat.',
                'anamesis' => 'Keluhan sering muncul ketika telat makan dan setelah konsumsi makanan pedas. Tidak ada muntah darah.',
                'pemeriksaan_fisik' => 'Nyeri tekan epigastrium ringan, abdomen supel, bising usus normal.',
                'pemeriksaan_lab' => 'Tidak dilakukan pemeriksaan laboratorium.',
                'diagnostik' => 'Dispepsia',
                'terapi' => 'Antasida 3x1 sebelum makan, omeprazole 20 mg 1x1 pagi, hindari kopi, pedas, dan makan terlambat.',
                'riwayat_alergi' => 'Tidak ada alergi obat.',
            ],
            [
                'keluhan' => 'Pusing berputar saat bangun dari tidur dan terasa mual.',
                'anamesis' => 'Keluhan muncul mendadak sejak pagi, dipicu perubahan posisi kepala. Tidak ada kelemahan anggota gerak.',
                'pemeriksaan_fisik' => 'Tekanan darah 120/80 mmHg, nistagmus ringan, pemeriksaan neurologis dalam batas normal.',
                'pemeriksaan_lab' => 'Tidak dilakukan pemeriksaan laboratorium.',
                'diagnostik' => 'Vertigo perifer',
                'terapi' => 'Betahistine 6 mg 3x1, istirahat, hindari perubahan posisi mendadak, kontrol bila keluhan memberat.',
                'riwayat_alergi' => 'Tidak ada.',
            ],
            [
                'keluhan' => 'Nyeri kepala bagian belakang dan mudah lelah.',
                'anamesis' => 'Pasien memiliki riwayat tekanan darah tinggi, konsumsi obat tidak teratur selama beberapa minggu.',
                'pemeriksaan_fisik' => 'Tekanan darah 150/95 mmHg, nadi 84x/menit, tidak ada edema tungkai.',
                'pemeriksaan_lab' => 'Gula darah sewaktu dalam batas normal.',
                'diagnostik' => 'Hipertensi tidak terkontrol',
                'terapi' => 'Amlodipine 5 mg 1x1 malam, diet rendah garam, monitoring tekanan darah, kontrol 1 minggu.',
                'riwayat_alergi' => 'Tidak ada alergi obat yang diketahui.',
            ],
            [
                'keluhan' => 'Gatal kemerahan pada lengan setelah konsumsi makanan laut.',
                'anamesis' => 'Ruam muncul sekitar 2 jam setelah makan udang. Tidak ada sesak napas atau bengkak bibir.',
                'pemeriksaan_fisik' => 'Urtikaria ringan pada lengan kanan dan kiri, tanda vital stabil.',
                'pemeriksaan_lab' => 'Tidak dilakukan pemeriksaan laboratorium.',
                'diagnostik' => 'Urtikaria akut',
                'terapi' => 'Cetirizine 10 mg 1x1 malam, hindari pencetus alergi, segera kembali bila muncul sesak.',
                'riwayat_alergi' => 'Alergi makanan laut.',
            ],
            [
                'keluhan' => 'Nyeri pinggang setelah mengangkat barang berat.',
                'anamesis' => 'Nyeri terasa saat membungkuk, tidak menjalar ke kaki, tidak ada gangguan BAK/BAB.',
                'pemeriksaan_fisik' => 'Spasme otot paravertebral lumbal, straight leg raising negatif.',
                'pemeriksaan_lab' => 'Tidak dilakukan pemeriksaan laboratorium.',
                'diagnostik' => 'Low back pain mekanik',
                'terapi' => 'Ibuprofen 400 mg 2x1 setelah makan, kompres hangat, hindari angkat berat sementara.',
                'riwayat_alergi' => 'Tidak ada.',
            ],
            [
                'keluhan' => 'Sering buang air kecil dan mudah haus.',
                'anamesis' => 'Keluhan dirasakan sejak beberapa minggu, pasien memiliki riwayat keluarga diabetes.',
                'pemeriksaan_fisik' => 'Keadaan umum baik, tidak ada tanda dehidrasi berat.',
                'pemeriksaan_lab' => 'Gula darah sewaktu 218 mg/dL.',
                'diagnostik' => 'Hiperglikemia suspek diabetes melitus',
                'terapi' => 'Edukasi diet rendah gula sederhana, aktivitas fisik teratur, rencana pemeriksaan GDP dan HbA1c.',
                'riwayat_alergi' => 'Tidak ada alergi obat.',
            ],
        ];

        shuffle($visitTemplates);

        $visitCount = random_int(2, 5);
        $visits = [];

        for ($i = 0; $i < $visitCount; $i++) {
            $template = $visitTemplates[$i % count($visitTemplates)];

            $visits[] = array_merge($template, [
                'patient_id' => $patientId,
                'tanggal_berobat' => Carbon::now()
                    ->subDays(random_int(7, 420))
                    ->format('Y-m-d'),
            ]);
        }

        usort($visits, function (array $firstVisit, array $secondVisit) {
            return strcmp($secondVisit['tanggal_berobat'], $firstVisit['tanggal_berobat']);
        });

        return $visits;
    }
}

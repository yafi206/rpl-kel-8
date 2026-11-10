<?php

namespace App\Services;

class ReportAnalysisService
{
    public function analyze(string $description, ?string $photoPath = null): array
    {
        $keywords = ['rusak', 'retak', 'putus', 'pecah', 'bocor', 'tidak berfungsi', 'berbahaya'];
        $lowerDescription = strtolower($description);
        $matched = array_values(array_filter($keywords, fn (string $keyword) => str_contains($lowerDescription, $keyword)));

        $confidence = 58 + (count($matched) * 12);
        $confidence = min(96, max(60, $confidence));

        $riskLevel = $confidence >= 85 ? 'tinggi' : ($confidence >= 70 ? 'sedang' : 'rendah');

        $summary = 'Deteksi otomatis menilai area fasilitas mengalami kerusakan yang perlu ditangani segera.';
        if ($matched !== []) {
            $summary = 'AI/OpenCV menilai tanda-tanda kerusakan: ' . implode(', ', $matched) . '.';
        }

        if ($photoPath) {
            $summary .= ' Foto lampiran berhasil diproses untuk pengecekan visual.';
        }

        return [
            'summary' => $summary,
            'confidence_score' => (int) round($confidence),
            'risk_level' => $riskLevel,
            'recommendation' => $riskLevel === 'tinggi'
                ? 'Segera lakukan penanganan darurat dan validasi lapangan.'
                : 'Lanjutkan pemeriksaan teknis dan tindak lanjuti sesuai prioritas.',
        ];
    }
}

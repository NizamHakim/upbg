<?php

namespace App\Exports;

use App\Models\Tes;
use PhpOffice\PhpSpreadsheet\Style\Style;
use App\Exports\RuanganTesSheets\AllRuanganSheet;
use App\Exports\RuanganTesSheets\PerRuanganSheet;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RuanganTesExport implements WithMultipleSheets, WithDefaultStyles
{
  private $tes;

  public function __construct(Tes $tes)
  {
    $this->tes = $tes;
  }

  public function defaultStyles(Style $defaultStyle)
  {
    return [
      'font' => [
        'name' => 'Times New Roman',
        'size' => 11,
      ],
    ];
  }

  public function sheets(): array
  {
    $sheets = [];

    $sheets[] = new AllRuanganSheet($this->tes, $this->tes->pivotPeserta);

    $dataPerRuangan = $this->tes->pivotPeserta->groupBy(function ($item) {
      return $item->ruangan?->kode ?? 'Tanpa Ruangan';
    });

    foreach ($dataPerRuangan as $ruangan => $data) {
      $sheets[] = new PerRuanganSheet($ruangan, $this->tes, $data);
    }

    return $sheets;
  }
}

<?php

namespace App\Exports;

use App\Models\Peserta;
use Illuminate\Http\Request;
use App\Models\ConfigLaporan;
use App\Models\PertemuanKelas;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\Style;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class DepartemenExport implements
  FromCollection,
  WithDrawings,
  WithMapping,
  WithCustomStartCell,
  WithHeadings,
  WithEvents,
  WithDefaultStyles
{
  private $departemen;
  private $mahasiswa;
  private $index = 1;

  public function __construct(Request $request)
  {
    $this->departemen = $request?->departemen;
    $this->mahasiswa = $request?->mahasiswa;
  }

  public function collection()
  {
    $pesertaList = Peserta::query();

    $pesertaList->when($this->departemen, function ($query) {
      return $query->where('occupation', 'like', '%' . $this->departemen . '%');
    });

    $pesertaList->when($this->mahasiswa, function ($query) {
      return $query->where('id', $this->mahasiswa);
    });

    $pesertaList->with([
      'kelas' => function ($query) {
        $query->withCount(['pertemuan as progress' => function ($query) {
          $query->where('terlaksana', true);
        }]);
      },
      'pivotTes.tes',
    ]);
    $pesertaList = $pesertaList->get();

    foreach ($pesertaList as $peserta) {
      foreach ($peserta->kelas as $kelas) {
        $kehadiran = PertemuanKelas::where('kelas_id', $kelas->id)->where('terlaksana', true)->whereHas('presensi', function ($query) use ($peserta) {
          $query->where('peserta_id', $peserta->id)->where('hadir', true);
        })->count();
        $kelas->kehadiran = $kehadiran;
        $kelas->percentage = $kelas->progress > 0 ? ($kehadiran / $kelas->progress) * 100 : 0;
      }
    }
    return $pesertaList;
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

  public function map($peserta): array
  {
    $kelasList = $peserta->kelas->map(function ($kelas) {
      return "$kelas->kode - $kelas->percentage%";
    })->implode("\n");

    $tesList = $peserta->pivotTes->map(function ($pivotTes) {
      $hadir = $pivotTes->hadir ? 'Hadir' : 'Tidak Hadir';
      return "{$pivotTes->tes->kode} - {$hadir}";
    })->implode("\n");

    return [
      $this->index++,
      $peserta->nama,
      $peserta->nik,
      $peserta->occupation,
      $kelasList,
      $tesList,
    ];
  }

  public function headings(): array
  {
    return [
      'No',
      'Nama Mahasiswa',
      'NRP',
      'Departemen / Prodi',
      "Kode Kelas \n (kehadiran / pertemuan terlaksana)",
      'Kode Tes',
    ];
  }

  public function startCell(): string
  {
    return 'A12';
  }

  public function drawings()
  {
    $drawing = new Drawing();
    $drawing->setName('Logo');
    $drawing->setDescription('Logo ITS');
    $drawing->setPath(public_path('images/its.png'));
    $drawing->setHeight(90);
    $drawing->setWidth(90);
    $drawing->setCoordinates('A3');

    return $drawing;
  }

  public function registerEvents(): array
  {
    return [
      BeforeSheet::class => [self::class, 'beforeSheet'],
      AfterSheet::class => [self::class, 'afterSheet'],
    ];
  }

  public static function beforeSheet(BeforeSheet $event)
  {
    $sheet = $event->sheet->getDelegate();
    $kop = ConfigLaporan::where('group', 'Kop')->first()->data;
    $darkblue = '313193';
    $lightblue = '6ccff6';

    $sheet->getColumnDimension('A')->setWidth(40, 'px');
    $sheet->getStyle('A')->applyFromArray([
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
        'wrapText' => true,
      ]
    ]);

    $sheet->getColumnDimension('B')->setWidth(95, 'px');
    $sheet->getStyle('B')->applyFromArray([
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
        'wrapText' => true,
      ]
    ]);

    $sheet->getColumnDimension('C')->setWidth(105, 'px');
    $sheet->getStyle('C')->applyFromArray([
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
        'wrapText' => true,
      ]
    ]);

    $sheet->getColumnDimension('D')->setWidth(120, 'px');
    $sheet->getStyle('D')->applyFromArray([
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
        'wrapText' => true,
      ]
    ]);

    $sheet->getColumnDimension('E')->setWidth(190, 'px');
    $sheet->getStyle('E')->applyFromArray([
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
        'wrapText' => true,
      ]
    ]);

    $sheet->getColumnDimension('F')->setWidth(190, 'px');
    $sheet->getStyle('F')->applyFromArray([
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
        'wrapText' => true,
      ]
    ]);

    $sheet->mergeCells('B2:F2');
    $sheet->setCellValue('B2', $kop['Kementrian']);
    $sheet->getStyle('B2')->applyFromArray([
      'font' => [
        'bold' => true,
        'size' => 16,
        'color' => ['argb' => $lightblue],
      ],
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'wrapText' => false
      ],
    ]);

    $sheet->mergeCells('B3:F3');
    $sheet->setCellValue('B3', $kop['ITS']);
    $sheet->mergeCells('B4:F4');
    $sheet->setCellValue('B4', $kop['UPBG']);
    $sheet->getStyle('B3:F4')->applyFromArray([
      'font' => [
        'bold' => true,
        'size' => 14,
        'color' => ['argb' => $darkblue],
      ],
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
      ],
    ]);


    $sheet->mergeCells('B5:F5');
    $sheet->setCellValue('B5', $kop['Alamat']);
    $sheet->mergeCells('B6:F6');
    $sheet->setCellValue('B6', $kop['Kontak']);
    $sheet->mergeCells('B7:F7');
    $sheet->setCellValue('B7', $kop['Website']);
    $sheet->getStyle('B5:F7')->applyFromArray([
      'font' => [
        'size' => 12,
      ],
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
      ],
    ]);

    $sheet->getStyle('A9:F9')->applyFromArray([
      'borders' => [
        'top' => [
          'borderStyle' => Border::BORDER_DOUBLE,
          'color' => ['argb' => $darkblue],
        ],
      ],
    ]);

    $sheet->mergeCells('A10:F10');
    $sheet->setCellValue('A10', 'DAFTAR MAHASISWA KURSUS PER DEPARTEMEN');
    $sheet->getStyle('A10:F10')->applyFromArray([
      'font' => [
        'bold' => true,
        'size' => 11,
      ],
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
      ],
    ]);
  }

  public static function afterSheet(AfterSheet $event)
  {
    $sheet = $event->sheet->getDelegate();
    $tandatangan = ConfigLaporan::where('group', 'Tanda Tangan')->first()->data;
    $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4)->setOrientation(PageSetup::ORIENTATION_PORTRAIT)->setRowsToRepeatAtTopByStartAndEnd(1, 17);
    $rows = $sheet->getHighestRow();
    $cols = $sheet->getHighestColumn();
    $rowStart = 12;

    // set all cells style
    $sheet->getStyle("A{$rowStart}:{$cols}{$rows}")->applyFromArray([
      'borders' => [
        'allBorders' => [
          'borderStyle' => Border::BORDER_THIN,
        ],
        'outline' => [
          'borderStyle' => 'double',
        ],
      ],
    ]);

    // heading row height and style
    $sheet->getRowDimension($rowStart)->setRowHeight(50);
    $sheet->getStyle("A{$rowStart}:F{$rowStart}")->applyFromArray([
      'font' => [
        'bold' => true,
      ],
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
      ],
      'fill' => [
        'fillType' => 'solid',
        'startColor' => [
          'rgb' => 'd9d9d9',
        ],
      ],
    ]);

    // data row height
    for ($i = $rowStart + 1; $i <= $rows; $i++) {
      $cellE = "E{$i}";
      $countE = substr_count($sheet->getCell($cellE)->getValue(), "\n") + 1;
      $cellF = "F{$i}";
      $countF = substr_count($sheet->getCell($cellF)->getValue(), "\n") + 1;
      $sheet->getRowDimension($i)->setRowHeight(max($countE, $countF) * 15);
    }

    $date = Carbon::now()->format('F d, Y');
    $sheet->setCellValue("F" . ($rows + 3), "Surabaya, {$date}");
    $sheet->setCellValue("F" . ($rows + 4), $tandatangan['Jabatan']);
    $sheet->setCellValue("F" . ($rows + 8), $tandatangan['Nama Kepala UPBG']);
    $sheet->setCellValue("F" . ($rows + 9), $tandatangan['NIP Kepala UPBG']);

    $sheet->getStyle("F" . ($rows + 3) . ":F" . ($rows + 9))->applyFromArray([
      'font' => [
        'size' => 11,
      ],
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_LEFT,
        'wrapText' => false
      ],
    ]);
  }
}

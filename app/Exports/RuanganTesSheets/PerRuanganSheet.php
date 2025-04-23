<?php

namespace App\Exports\RuanganTesSheets;

use App\Models\Tes;
use App\Models\ConfigLaporan;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;

class PerRuanganSheet implements
  WithTitle,
  FromCollection,
  WithDrawings,
  WithMapping,
  WithCustomStartCell,
  WithHeadings,
  WithEvents
{
  private $title;
  private $tes;
  private $data;
  private $index = 1;

  public function __construct($title, $tes, $data)
  {
    $this->title = $title;
    $this->tes = $tes;
    $this->data = $data;
  }

  public function collection()
  {
    return $this->data;
  }

  public function title(): string
  {
    return $this->title;
  }

  public function headings(): array
  {
    return [
      'No',
      'Name',
      'NRP/ID.Number',
      'Dept./Occupation',
      'Signature'
    ];
  }

  public function startCell(): string
  {
    return 'A18';
  }

  public function columnFormats(): array
  {
    return [
      'A' => NumberFormat::FORMAT_GENERAL,
      'B' => NumberFormat::FORMAT_GENERAL,
      'C' => NumberFormat::FORMAT_TEXT,
      'D' => NumberFormat::FORMAT_GENERAL,
      'E' => NumberFormat::FORMAT_GENERAL,
    ];
  }

  public function map($peserta): array
  {
    return [
      $this->index++,
      $peserta->peserta->nama,
      $peserta->peserta->nik,
      $peserta->peserta->occupation,
      ''
    ];
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
    $tes = $event->getConcernable()->tes;
    $kop = ConfigLaporan::where('group', 'Kop')->first()->data;
    $kodeAbsenTes = ConfigLaporan::where('group', 'Kode Laporan')->first()->data['Absen Tes'];
    $darkblue = '313193';
    $lightblue = '6ccff6';

    $sheet->getColumnDimension('A')->setWidth(40, 'px');
    $sheet->getStyle('A')->applyFromArray([
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
        'wrapText' => true
      ]
    ]);

    $sheet->getColumnDimension('B')->setWidth(200, 'px');
    $sheet->getStyle('B')->applyFromArray([
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_LEFT,
        'vertical' => Alignment::VERTICAL_CENTER,
        'wrapText' => true
      ]
    ]);

    $sheet->getColumnDimension('C')->setWidth(150, 'px');
    $sheet->getStyle('C')->applyFromArray([
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
        'wrapText' => true
      ]
    ]);

    $sheet->getColumnDimension('D')->setWidth(250, 'px');
    $sheet->getStyle('D')->applyFromArray([
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
        'wrapText' => true
      ]
    ]);

    $sheet->getColumnDimension('E')->setWidth(100, 'px');
    $sheet->getStyle('E')->applyFromArray([
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
        'wrapText' => true
      ]
    ]);

    $sheet->mergeCells('B2:E2');
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

    $sheet->mergeCells('B3:E3');
    $sheet->setCellValue('B3', $kop['ITS']);
    $sheet->mergeCells('B4:E4');
    $sheet->setCellValue('B4', $kop['UPBG']);
    $sheet->getStyle('B3:E4')->applyFromArray([
      'font' => [
        'bold' => true,
        'size' => 14,
        'color' => ['argb' => $darkblue],
      ],
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
      ],
    ]);


    $sheet->mergeCells('B5:E5');
    $sheet->setCellValue('B5', $kop['Alamat']);
    $sheet->mergeCells('B6:E6');
    $sheet->setCellValue('B6', $kop['Kontak']);
    $sheet->mergeCells('B7:E7');
    $sheet->setCellValue('B7', $kop['Website']);
    $sheet->getStyle('B5:E7')->applyFromArray([
      'font' => [
        'size' => 12,
      ],
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
      ],
    ]);

    $sheet->getStyle('A9:E9')->applyFromArray([
      'borders' => [
        'top' => [
          'borderStyle' => Border::BORDER_DOUBLE,
          'color' => ['argb' => $darkblue],
        ],
      ],
    ]);

    $sheet->mergeCells('A10:E10');
    $sheet->setCellValue('A10', 'Attendance List');
    $currentMonth = Carbon::now()->month;
    $year = $currentMonth <= 6 ? Carbon::now()->year - 1 : Carbon::now()->year;
    $nextYear = $year + 1;
    $sheet->mergeCells('A11:E11');
    $sheet->setCellValue('A11', "Academic Year : {$year} / {$nextYear}");
    $date = $tes->tanggal->format('F d, Y');
    $waktuMulai = $tes->waktu_mulai->format('H:i');
    $waktuSelesai = $tes->waktu_selesai->format('H:i');
    $sheet->mergeCells('A12:E12');
    $sheet->setCellValue('A12', "Date : {$date} | Time : {$waktuMulai} - {$waktuSelesai}");
    $sheet->mergeCells('A13:E13');
    $sheet->setCellValue('A13', "Test Code : " . $tes->kode);
    $sheet->getStyle('A10:E13')->applyFromArray([
      'font' => [
        'bold' => true,
        'size' => 11,
      ],
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
      ],
    ]);


    $sheet->setCellValue('A15', "Class :");
    $sheet->setCellValue('D15', "Number of Test Takers :");
    $sheet->setCellValue('E15', $tes->totalPeserta);
    $sheet->setCellValue('A16', "Proctor :");
    $sheet->setCellValue('D16', "Corrector :");

    $sheet->getStyle('A15:E16')->applyFromArray([
      'font' => [
        'size' => 11,
      ],
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_LEFT,
        'wrapText' => false
      ],
    ]);

    $sheet->mergeCells('D17:E17');
    $sheet->setCellValue('D17', $kodeAbsenTes);
    $sheet->getStyle('D17')->applyFromArray([
      'font' => [
        'bold' => true,
        'size' => 11,
      ],
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_RIGHT,
        'wrapText' => false
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

    // set all cells style
    $sheet->getStyle("A18:{$cols}{$rows}")->applyFromArray([
      'borders' => [
        'allBorders' => [
          'borderStyle' => Border::BORDER_THIN,
        ],
        'outline' => [
          'borderStyle' => 'double',
        ],
      ],
    ]);
    for ($i = 18; $i <= $rows; $i++) {
      $sheet->getRowDimension($i)->setRowHeight(25);
    }

    // set heading style
    $sheet->getStyle('A18:E18')->applyFromArray([
      'font' => [
        'bold' => true,
      ],
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
      ]
    ]);

    $date = Carbon::now()->format('F d, Y');
    $sheet->setCellValue("D" . ($rows + 3), "Surabaya, {$date}");
    $sheet->setCellValue("D" . ($rows + 4), $tandatangan['Jabatan']);
    $sheet->setCellValue("D" . ($rows + 8), $tandatangan['Nama Kepala UPBG']);
    $sheet->setCellValue("D" . ($rows + 9), $tandatangan['NIP Kepala UPBG']);

    $sheet->getStyle("A" . ($rows + 3) . ":E" . ($rows + 9))->applyFromArray([
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

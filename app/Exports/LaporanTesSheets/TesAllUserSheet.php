<?php

namespace App\Exports\LaporanTesSheets;

use App\Models\User;
use App\Models\ConfigLaporan;
use Illuminate\Support\Carbon;
use function Illuminate\Log\log;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\Style;
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
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class TesAllUserSheet implements
  WithDrawings,
  WithMapping,
  FromCollection,
  ShouldAutoSize,
  WithEvents,
  WithCustomStartCell,
  WithHeadings,
  WithTitle
{
  private $data;
  private $tanggal;
  private $kategori;
  private $title;
  private $index = 1;

  public function __construct($data, $tanggal, $kategori)
  {
    $this->data = $data;
    $this->tanggal = $tanggal;
    $this->kategori = $kategori;
    $this->title = $kategori;
  }

  public function collection()
  {
    return $this->data;
  }

  public function map($user): array
  {
    $array[] = [
      $this->index++,
      $user->name,
      '',
      '',
      '',
      $user->pengawasTes->count(),
    ];

    foreach ($user->pengawasTes as $tes) {
      $array[] = [
        "",
        "",
        $tes->tipe->kode,
        $tes->tanggal->format('l, j F Y'),
        "{$tes->waktu_mulai->format('H:i')} - {$tes->waktu_selesai->format('H:i')}",
        '',
      ];
    }
    return $array;
  }

  public function headings(): array
  {
    return [
      'No',
      'Name',
      'Tes Type',
      'Date',
      'Time',
      'Total Number',
    ];
  }

  public function startCell(): string
  {
    return 'A14';
  }

  public function title(): string
  {
    return $this->title;
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
    $data = $event->getConcernable();
    $kop = ConfigLaporan::where('group', 'Kop')->first()->data;
    $kodeLaporanTes = ConfigLaporan::where('group', 'Kode Laporan')->first()->data['Laporan Tes'];
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

    $sheet->getColumnDimension('B')->setWidth(60, 'px');
    $sheet->getStyle('B')->applyFromArray([
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
        'wrapText' => true
      ]
    ]);

    $sheet->getColumnDimension('C')->setWidth(140, 'px');
    $sheet->getStyle('C')->applyFromArray([
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
        'wrapText' => true,
      ]
    ]);

    $sheet->getColumnDimension('D')->setWidth(200, 'px');
    $sheet->getStyle('D')->applyFromArray([
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
        'wrapText' => true
      ]
    ]);

    $sheet->getColumnDimension('E')->setWidth(200, 'px');
    $sheet->getStyle('E')->applyFromArray([
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
        'wrapText' => true,
      ]
    ]);

    $sheet->getColumnDimension('F')->setWidth(100, 'px');
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
    $sheet->setCellValue('A10', "PROCTOR'S MONTHLY RECAPITULATION");
    $sheet->getStyle('A10:F10')->applyFromArray([
      'font' => [
        'bold' => true,
        'size' => 11,
      ],
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
      ],
    ]);

    $sheet->mergeCells("A12:D12");
    $sheet->setCellValue('A12', "Program : {$data->kategori}");
    $sheet->mergeCells("A13:D13");
    $sheet->setCellValue('A13', "MONTH : {$data->tanggal}");
    $sheet->getStyle('A12:D13')->applyFromArray([
      'font' => [
        'size' => 11,
      ],
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_LEFT,
        'wrapText' => false
      ],
    ]);

    $sheet->setCellValue('F13', $kodeLaporanTes);
    $sheet->getStyle('F13')->applyFromArray([
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
    $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4)->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
    $rows = $sheet->getHighestRow() + 1;
    $cols = $sheet->getHighestColumn();
    $rowStart = 14;

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

    // set heading row height and style
    $sheet->getRowDimension($rowStart)->setRowHeight(40);
    $sheet->getStyle("A{$rowStart}:F{$rowStart}")->applyFromArray([
      'font' => [
        'bold' => true,
      ],
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
      ],
    ]);

    for ($i = $rowStart + 1; $i <= $rows; $i++) {
      $sheet->getRowDimension($i)->setRowHeight(25);
      $value = $sheet->getCell("A{$i}")->getValue();

      // row nama
      if (is_numeric($value)) {
        $sheet->mergeCells("B{$i}:E{$i}");
        $sheet->getStyle("A{$i}:F{$i}")->applyFromArray([
          'fill' => [
            'fillType' => 'solid',
            'startColor' => [
              'rgb' => 'd9d9d9',
            ],
          ],
        ]);
        $sheet->getStyle("B{$i}")->applyFromArray([
          'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_LEFT,
            'vertical' => Alignment::VERTICAL_CENTER,
          ],
        ]);
      }
    }

    // total
    $sheet->setCellValue('E' . $rows, 'Total')->getStyle('E' . $rows)->applyFromArray([
      'font' => [
        'bold' => true,
      ],
    ]);
    $sheet->setCellValue('F' . $rows, '=SUM(F1:F' . $rows - 1 . ')')->getStyle('F' . $rows)->applyFromArray([
      'font' => [
        'bold' => true,
      ],
    ]);

    $date = Carbon::now()->format('F d, Y');
    $sheet->setCellValue("E" . ($rows + 3), "Surabaya, {$date}");
    $sheet->setCellValue("E" . ($rows + 4), $tandatangan['Jabatan']);
    $sheet->setCellValue("E" . ($rows + 8), $tandatangan['Nama Kepala UPBG']);
    $sheet->setCellValue("E" . ($rows + 9), $tandatangan['NIP Kepala UPBG']);

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

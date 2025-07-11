<?php

namespace App\Exports\LaporanKelasSheets;

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

class KelasAllUserSheet implements
  WithDrawings,
  WithMapping,
  FromCollection,
  WithEvents,
  WithCustomStartCell,
  WithHeadings,
  WithTitle
{
  private $data;
  private $tanggal;
  private $kategori;
  private $program;
  private $title;
  private $index = 1;

  public function __construct($data, $tanggal, $kategori, $program)
  {
    $this->data = $data;
    $this->tanggal = $tanggal;
    $this->kategori = $kategori;
    $this->program = $program;
    $this->title = $kategori . ' - ' . $program;
  }

  public function collection()
  {
    return $this->data;
  }

  public function map($user): array
  {
    $array[] = [
      $this->index,
      $user->name
    ];

    $pertemuanCount = 0;

    foreach ($user->pengajarKelas as $kelas) {
      $tanggalList = [];
      foreach ($kelas->pertemuan as $pertemuan) {
        if (!$pertemuan->terlaksana || ($pertemuan->terlaksana && $pertemuan->pengajar_id == $user->id)) {
          $tanggalList[] = $pertemuan->tanggal->format('d/m');
        }
      }

      if (empty($tanggalList)) {
        continue;
      }

      $pertemuanCount += count($tanggalList);

      $array[] = [
        "",
        "",
        $kelas->kode,
        implode(', ', $tanggalList),
        count($tanggalList),
        "{$kelas->pertemuan->first()->waktu_mulai->format('H:i')} - {$kelas->pertemuan->first()->waktu_selesai->format('H:i')}",
      ];
    }

    if ($pertemuanCount == 0) return [];

    $this->index++;
    return $array;
  }

  public function headings(): array
  {
    return [
      'No',
      'Nama',
      'Kode Kelas',
      'Tanggal Mengajar',
      'Total',
      'Jam',
    ];
  }

  public function startCell(): string
  {
    return 'A15';
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
    $kodeLaporanKelas = ConfigLaporan::where('group', 'Kode Laporan')->first()->data['Laporan Kelas'];
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

    $sheet->getColumnDimension('C')->setWidth(180, 'px');
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
        'wrapText' => true,
      ]
    ]);

    $sheet->getColumnDimension('E')->setWidth(60, 'px');
    $sheet->getStyle('E')->applyFromArray([
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
        'wrapText' => true,
      ]
    ]);

    $sheet->getColumnDimension('F')->setWidth(200, 'px');
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
    $sheet->setCellValue('A10', "MONTHLY TEACHER'S TIME TABLE RECAPITULATION");
    $sheet->mergeCells('A11:F11');
    $sheet->setCellValue('A11', "{$data->kategori} Course - {$data->program}");
    $sheet->getStyle('A10:F11')->applyFromArray([
      'font' => [
        'bold' => true,
        'size' => 11,
      ],
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
      ],
    ]);


    $sheet->mergeCells("A13:D13");
    $sheet->setCellValue('A13', "MONTH : {$data->tanggal}");
    $sheet->mergeCells("A14:D14");
    $sheet->setCellValue('A14', "Course : {$data->kategori} - {$data->program}");
    $sheet->getStyle('A13:D14')->applyFromArray([
      'font' => [
        'size' => 11,
      ],
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_LEFT,
        'wrapText' => false
      ],
    ]);

    $sheet->setCellValue('F14', $kodeLaporanKelas);
    $sheet->getStyle('F14')->applyFromArray([
      'font' => [
        'bold' => true,
        'size' => 11,
      ],
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_LEFT,
        'wrapText' => false
      ],
    ]);
  }

  public static function afterSheet(AfterSheet $event)
  {
    $sheet = $event->sheet->getDelegate();
    $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4)->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
    $rows = $sheet->getHighestRow() + 1;
    $cols = $sheet->getHighestColumn();
    $rowStart = 15;

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

    // data height and style
    for ($i = $rowStart + 1; $i <= $rows; $i++) {
      $sheet->getRowDimension($i)->setRowHeight(25);
      $value = $sheet->getCell("A{$i}")->getValue();

      // row nama
      if (is_numeric($value)) {
        $sheet->mergeCells("B{$i}:F{$i}");
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
    $sheet->setCellValue('D' . $rows, 'Total')->getStyle('D' . $rows)->applyFromArray([
      'font' => [
        'bold' => true,
      ],
    ]);
    $sheet->setCellValue('E' . $rows, '=SUM(E1:E' . $rows - 1 . ')')->getStyle('E' . $rows)->applyFromArray([
      'font' => [
        'bold' => true,
      ],
    ]);

    $tandatangan = ConfigLaporan::where('group', 'Tanda Tangan')->first()->data;
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

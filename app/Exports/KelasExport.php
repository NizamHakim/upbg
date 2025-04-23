<?php

namespace App\Exports;

use App\Models\User;
use App\Models\ProgramKelas;
use Illuminate\Http\Request;
use App\Models\KategoriKelas;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\LaporanKelasSheets\KelasAllUserSheet;
use App\Exports\LaporanKelasSheets\KelasEachUserSheet;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use PhpOffice\PhpSpreadsheet\Style\Style;

class KelasExport implements WithMultipleSheets, WithDefaultStyles
{
  use Exportable;

  private $firstDate;
  private $lastDate;
  private $includeTidakTerlaksana;
  private $kelasIds;

  public function __construct(Request $request)
  {
    $this->firstDate = Carbon::parse($request['tanggal-mulai']);
    $this->lastDate = Carbon::parse($request['tanggal-akhir']);
    $this->includeTidakTerlaksana = $request['include-tidak-terlaksana'];
    $this->kelasIds = $request['kelas'];
  }

  public function getData()
  {
    $data = [];
    $kategoriList = KategoriKelas::all();
    $programList = ProgramKelas::all();

    foreach ($kategoriList as $kategori) {
      foreach ($programList as $program) {
        $userList = User::query();

        $userList->whereHas('pengajarKelas', function ($query) use ($kategori, $program) {
          $query->whereHas('tipe', function ($query) use ($kategori) {
            $query->where('kategori_id', $kategori->id);
          })
            ->where('program_id', $program->id)
            ->whereHas('pertemuan', function ($query) {
              $query->whereBetween('tanggal', [$this->firstDate, $this->lastDate])
                ->when(!$this->includeTidakTerlaksana, function ($query) {
                  $query->where('terlaksana', true);
                });
            })
            ->whereIn('kelas.id', $this->kelasIds);
        });

        $userList->with([
          'pengajarKelas' => function ($query) use ($kategori, $program) {
            $query->whereHas('tipe', function ($query) use ($kategori) {
              $query->where('kategori_id', $kategori->id);
            })
              ->where('program_id', $program->id)
              ->whereHas('pertemuan', function ($query) {
                $query->whereBetween('tanggal', [$this->firstDate, $this->lastDate])
                  ->when(!$this->includeTidakTerlaksana, function ($query) {
                    $query->where('terlaksana', true);
                  });
              })
              ->whereIn('kelas.id', $this->kelasIds)
              ->with(['pertemuan' => function ($query) {
                $query->whereBetween('tanggal', [$this->firstDate, $this->lastDate])
                  ->when(!$this->includeTidakTerlaksana, function ($query) {
                    $query->where('terlaksana', true);
                  });
              }]);
          },
        ]);

        $userList = $userList->get();
        $data[] = [
          'kategori' => $kategori->nama,
          'program' => $program->nama,
          'users' => $userList,
        ];
      }
    }

    return $data;
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
    $data = $this->getData();
    $tanggal = "{$this->firstDate->format('l, j F Y')} - {$this->lastDate->format('l, j F Y')}";

    $sheets = [];
    foreach ($data as $key => $value) {
      if ($value['users']->count() == 0) {
        continue;
      }
      $sheets[] = new KelasAllUserSheet($value['users'], $tanggal, $value['kategori'], $value['program']);
      foreach ($value['users'] as $user) {
        $sheets[] = new KelasEachUserSheet($user, $tanggal, $value['kategori'], $value['program']);
      }
    }

    return $sheets;
  }
}

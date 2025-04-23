<?php

namespace App\Exports;

use App\Models\User;
use App\Models\KategoriTes;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\Style\Style;
use App\Exports\LaporanTesSheets\TesAllUserSheet;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use App\Exports\LaporanTesSheets\TesEachUserSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;


class TesExport implements WithMultipleSheets, WithDefaultStyles
{
  private $firstDate;
  private $lastDate;
  private $includeTidakTerlaksana;
  private $tesIds;

  public function __construct(Request $request)
  {
    $this->firstDate = Carbon::parse($request['tanggal-mulai']);
    $this->lastDate = Carbon::parse($request['tanggal-akhir']);
    $this->includeTidakTerlaksana = $request['include-tidak-terlaksana'];
    $this->tesIds = $request['tes'];
  }

  public function getData()
  {
    $data = [];
    $kategoriList = KategoriTes::all();

    foreach ($kategoriList as $kategori) {
      $userList = User::query();

      $userList->whereHas('pengawasTes', function ($query) use ($kategori) {
        $query->whereHas('tipe', function ($query) use ($kategori) {
          $query->where('kategori_id', $kategori->id);
        })->whereBetween('tanggal', [$this->firstDate, $this->lastDate])
          ->when(!$this->includeTidakTerlaksana, function ($query) {
            $query->where('terlaksana', true);
          })
          ->whereIn('tes.id', $this->tesIds);
      });

      $userList->with([
        'pengawasTes' => function ($query) use ($kategori) {
          $query->whereHas('tipe', function ($query) use ($kategori) {
            $query->where('kategori_id', $kategori->id);
          })->whereBetween('tanggal', [$this->firstDate, $this->lastDate])
            ->when(!$this->includeTidakTerlaksana, function ($query) {
              $query->where('terlaksana', true);
            })
            ->whereIn('tes.id', $this->tesIds);
        },
      ]);

      $userList = $userList->get();
      $data[] = [
        'kategori' => $kategori->nama,
        'users' => $userList,
      ];
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
      $sheets[] = new TesAllUserSheet($value['users'], $tanggal, $value['kategori']);
      foreach ($value['users'] as $user) {
        $sheets[] = new TesEachUserSheet($user, $tanggal, $value['kategori']);
      }
    }

    return $sheets;
  }
}

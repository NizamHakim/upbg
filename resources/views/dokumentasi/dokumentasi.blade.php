@use('App\Models\Role', 'Role')

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<x-head title="Panduan Penggunaan" />

<body class="scrollbar flex min-h-screen flex-col overflow-y-scroll font-poppins text-sm text-gray-600">
  <div class="flex flex-1">
    <div class="flex min-w-0 flex-1 bg-gray-100">
      <main class="mx-auto w-full min-w-0 max-w-7xl px-8 pb-20 pt-6">
        <div class="flex flex-col gap-1">
          <p class="text-2xl font-semibold text-upbg">Panduan Penggunaan</p>
        </div>

        <button onclick="window.scrollTo(0, 0);" class="btn btn-rounded fixed bottom-5 right-5 flex items-center justify-center border-none bg-upbg text-base text-white hover:bg-upbg-dark sm:bottom-10 sm:right-10"><i class="fa-solid fa-arrow-up"></i></button>

        <hr class="my-5">

        <div class="flex flex-col gap-4">
          <div class="flex flex-col gap-1">
            <h1 class="text-base font-semibold text-upbg">Kelas</h1>
            <a href="#tambah-kelas" class="link"># Tambah Kelas</a>
            <a href="#edit-kelas" class="link"># Edit Kelas</a>
            <a href="#hapus-kelas" class="link"># Hapus Kelas</a>
            <a href="#tambah-peserta-kelas" class="link"># Tambah Peserta Kelas</a>
            <a href="#edit-peserta-kelas" class="link"># Edit Peserta Kelas</a>
            <a href="#hapus-peserta-kelas" class="link"># Hapus Peserta Kelas</a>
            <a href="#tambah-pertemuan" class="link"># Tambah Pertemuan</a>
            <a href="#mulai-pertemuan" class="link"># Mulai Pertemuan</a>
            <a href="#edit-pertemuan" class="link"># Edit Pertemuan</a>
            <a href="#reschedule-pertemuan" class="link"># Reschedule Pertemuan</a>
            <a href="#edit-topik-catatan" class="link"># Edit Topik Catatan</a>
            <a href="#kelola-kelas" class="link"># Kelola Kelas</a>
          </div>
          <div class="flex flex-col gap-1">
            <h1 class="text-base font-semibold text-upbg">Tes</h1>
            <a href="#tambah-tes" class="link"># Tambah Tes</a>
            <a href="#edit-tes" class="link"># Edit Tes</a>
            <a href="#hapus-tes" class="link"># Hapus Tes</a>
            <a href="#mulai-tes" class="link"># Mulai Tes</a>
            <a href="#tambah-peserta-tes" class="link"># Tambah Peserta Tes</a>
            <a href="#edit-peserta-tes" class="link"># Edit Peserta Tes</a>
            <a href="#hapus-peserta-tes" class="link"># Hapus Peserta Tes</a>
          </div>
          <div class="flex flex-col gap-1">
            <h1 class="text-base font-semibold text-upbg">Laporan</h1>
            <a href="#buat-laporan-kelas" class="link"># Buat Laporan Kelas</a>
            <a href="#buat-laporan-tes" class="link"># Buat Laporan Tes</a>
            <a href="#buat-laporan-peserta" class="link"># Buat Laporan Peserta</a>
          </div>
        </div>

        <hr class="my-5">

        <div class="flex flex-col gap-8">
          <div class="flex flex-col gap-6">
            <h1 class="text-lg font-semibold text-upbg">Kelas</h1>

            <div class="flex flex-col gap-2">
              <a id="tambah-kelas" href="#tambah-kelas" class="link text-base font-semibold text-upbg"># Tambah Kelas</a>
              <p class="italic">Hak akses : {{ $permissions['tambah-kelas'] }}</p>
              <section class="flex flex-col gap-6">
                <p>Untuk membuat kelas baru, user mengisi form pembuatan kelas pada <a href="{{ route('kelas.create') }}" target="_blank" class="link">halaman tambah kelas</a>, user kemudian mengisi data-data yang diperlukan sesuai pada gambar. Beberapa aturan pengisian form adalah sebagai berikut:</p>
                <ol class="ml-4 list-outside list-decimal">
                  <li>Kolom <strong>Kode Kelas</strong> tidak dapat diisi secara manual tetapi akan otomatis terisi dengan mengisi kolom - kolom dibawahnya.</li>
                  <li>Sekurang-kurangnya 1 hari pada <strong>Jadwal</strong> harus sama dengan hari tanggal kelas dimulai. Untuk kelas dengan 1 pertemuan per minggu maka hari jadwal harus sama dengan hari tanggal kelas dimulai.</li>
                </ol>
                <img src="{{ asset('images/dokumentasi/form-tambah-kelas-1.png') }}" alt="form-tambah-kelas-1" class="rounded border">
                <p>User juga dapat menambahkan peserta secara manual ataupun menggunakan file .xlsx atau .csv dengan aturan format file seperti tertera pada gambar. Untuk melihat format atau mengunduh file template tekan tulisan <strong>Panduan Pengunaan</strong>.</p>
                <img src="{{ asset('images/dokumentasi/form-tambah-kelas-2.png') }}" alt="form-tambah-kelas-2" class="rounded border">
                <img src="{{ asset('images/dokumentasi/form-tambah-kelas-3.png') }}" alt="form-tambah-kelas-3" class="rounded border">
                <p>Setelah semua data terisi, tekan tombol tambah untuk menambahkan kelas. Jika semua data telah memenuhi syarat, kelas akan dibuat dan user akan diarahkan ke halaman kelas tersebut.</p>
              </section>
            </div>

            <div class="flex flex-col gap-2">
              <a id="edit-kelas" href="#edit-kelas" class="link text-base font-semibold text-upbg"># Edit Kelas</a>
              <p class="italic">Hak akses : {{ $permissions['edit-kelas'] }}</p>
              <section class="flex flex-col gap-6">
                <p>Untuk mengedit kelas, user menuju ke halaman kelas yang ingin diedit kemudian memilih opsi edit kelas.</p>
                <img src="{{ asset('images/dokumentasi/form-edit-kelas-1.png') }}" alt="form-edit-kelas-1" class="rounded border">
                <p>User kemudian dapat mengubah data-data kelas sesuai kebutuhan dan menekan tombol simpan untuk menyimpan perubahan.</p>
                <img src="{{ asset('images/dokumentasi/form-edit-kelas-2.png') }}" alt="form-edit-kelas-2" class="rounded border">
              </section>
            </div>

            <div class="flex flex-col gap-2">
              <a id="hapus-kelas" href="#hapus-kelas" class="link text-base font-semibold text-upbg"># Hapus Kelas</a>
              <p class="italic">Hak akses : {{ $permissions['hapus-kelas'] }}</p>
              <section class="flex flex-col gap-6">
                <p>Untuk menghapus kelas, user menuju ke halaman kelas yang ingin dihapus kemudian memilih opsi hapus kelas.</p>
                <img src="{{ asset('images/dokumentasi/hapus-kelas-1.png') }}" alt="hapus-kelas-1" class="rounded border">
                <p>Tekan hapus untuk konfirmasi penghapusan kelas. Semua data pertemuan pada kelas tersebut akan dihapus permanen.</p>
                <img src="{{ asset('images/dokumentasi/hapus-kelas-2.png') }}" alt="hapus-kelas-2" class="rounded border">
              </section>
            </div>

            <div class="flex flex-col gap-2">
              <a id="tambah-peserta-kelas" href="#tambah-peserta-kelas" class="link text-base font-semibold text-upbg"># Tambah Peserta Kelas</a>
              <p class="italic">Hak akses : {{ $permissions['kelola-peserta-kelas'] }}</p>
              <section class="flex flex-col gap-6">
                <p>User dapat menambahkan peserta baru pada kelas yang sudah dibuat dengan menuju ke halaman kelas yang dinginkan dan memilih menu <strong>Daftar Peserta</strong>. Pada halaman daftar peserta, tekan tombol <strong>Tambah Peserta</strong> untuk menuju ke halaman tambah peserta.</p>
                <img src="{{ asset('images/dokumentasi/tambah-peserta-kelas-1.png') }}" alt="tambah-peserta-kelas-1" class="rounded border">
                <p>User dapat menambahkan peserta secara manual ataupun menggunakan file .xlsx atau .csv dengan aturan format file seperti tertera pada gambar. Untuk melihat format atau mengunduh file template tekan tulisan <strong>Panduan Pengunaan</strong>.</p>
                <img src="{{ asset('images/dokumentasi/tambah-peserta-kelas-2.png') }}" alt="tambah-peserta-kelas-2" class="rounded border">
                <img src="{{ asset('images/dokumentasi/tambah-peserta-kelas-3.png') }}" alt="tambah-peserta-kelas-3" class="rounded border">
                <p>Setelah semua data terisi, tekan tombol tambah untuk menambahkan peserta. Jika semua data telah memenuhi syarat, peserta akan ditambahkan ke kelas tersebut.</p>
              </section>
            </div>

            <div class="flex flex-col gap-2">
              <a id="edit-peserta-kelas" href="#edit-peserta-kelas" class="link text-base font-semibold text-upbg"># Edit Peserta Kelas</a>
              <p class="italic">Hak akses : {{ $permissions['kelola-peserta-kelas'] }}</p>
              <section class="flex flex-col gap-6">
                <p>User dapat mengubah status peserta pada suatu kelas dengan menuju ke halaman kelas yang dinginkan dan memilih menu <strong>Daftar Peserta</strong>. Pada halaman daftar peserta, pilih peserta yang ingin diubah statusnya dan pilih menu edit.</p>
                <img src="{{ asset('images/dokumentasi/edit-peserta-kelas-1.png') }}" alt="edit-peserta-kelas-1" class="rounded border">
                <p>User kemudian mengubah status peserta dan menekan tombol simpan untuk menyimpan perubahan.</p>
                <img src="{{ asset('images/dokumentasi/edit-peserta-kelas-2.png') }}" alt="edit-peserta-kelas-2" class="rounded border">
                <p>Peserta dengan status <strong>Tidak Aktif</strong> tidak akan diikutkan pada pembuatan daftar presensi pertemuan yang akan datang.</p>
              </section>
            </div>

            <div class="flex flex-col gap-2">
              <a id="hapus-peserta-kelas" href="#hapus-peserta-kelas" class="link text-base font-semibold text-upbg"># Hapus Peserta Kelas</a>
              <p class="italic">Hak akses : {{ $permissions['kelola-peserta-kelas'] }}</p>
              <section class="flex flex-col gap-6">
                <p>User dapat menghapus peserta dari suatu kelas dengan menuju ke halaman kelas yang dinginkan dan memilih menu <strong>Daftar Peserta</strong>. Pada halaman daftar peserta, pilih peserta yang ingin dihapus dan pilih menu hapus.</p>
                <img src="{{ asset('images/dokumentasi/hapus-peserta-kelas-1.png') }}" alt="hapus-peserta-kelas-1" class="rounded border">
                <p>Tekan hapus untuk konfirmasi penghapusan peserta. Semua data pertemuan peserta pada kelas tersebut akan dihapus permanen.</p>
                <img src="{{ asset('images/dokumentasi/hapus-peserta-kelas-2.png') }}" alt="hapus-peserta-kelas-2" class="rounded border">
              </section>
            </div>

            <div class="flex flex-col gap-2">
              <a id="tambah-pertemuan" href="#tambah-pertemuan" class="link text-base font-semibold text-upbg"># Tambah Pertemuan</a>
              <p class="italic">Hak akses : {{ $permissions['tambah-pertemuan'] }}</p>
              <section class="flex flex-col gap-6">
                <p>Jika perlu menambahkan pertemuan pada kelas, user dapat menuju ke halaman kelas yang diinginkan dan menekan tombol <strong>Tambah Pertemuan</strong>. User kemudian mengisi data-data yang diperlukan dan menekan tombol tambah.</p>
                <img src="{{ asset('images/dokumentasi/tambah-pertemuan-1.png') }}" alt="tambah-pertemuan-1" class="rounded border">
              </section>
            </div>

            <div class="flex flex-col gap-2">
              <a id="mulai-pertemuan" href="#mulai-pertemuan" class="link text-base font-semibold text-upbg"># Mulai Pertemuan</a>
              <p class="italic">Hak akses : {{ $permissions['mulai-pertemuan'] }}</p>
              <section class="flex flex-col gap-6">
                <p>Ketika sudah memasuki waktunya, user dapat memulai pertemuan dengan menekan tombol <strong>Mulai pertemuan</strong> dan memilih pengajar. Opsi pengajar terdiri dari Staf Pengajar yang terdaftar di kelas itu.</p>
                <img src="{{ asset('images/dokumentasi/mulai-pertemuan-1.png') }}" alt="mulai-pertemuan-1" class="rounded border">
                <p>Setelah pertemuan dimulai, daftar kehadiran akan dibuat dan user dapat mulai mengisi daftar kehadiran peserta.</p>
                <img src="{{ asset('images/dokumentasi/mulai-pertemuan-2.png') }}" alt="mulai-pertemuan-2" class="rounded border">
              </section>
            </div>

            <div class="flex flex-col gap-2">
              <a id="edit-pertemuan" href="#edit-pertemuan" class="link text-base font-semibold text-upbg"># Edit Pertemuan</a>
              <p class="italic">Hak akses : {{ $permissions['edit-pertemuan'] }}</p>
              <section class="flex flex-col gap-6">
                <p>User dapat mengubah data-data terkait pertemuan seperti Status Terlaksana dan Pengajar dengan memilih menu edit.</p>
                <img src="{{ asset('images/dokumentasi/edit-pertemuan-1.png') }}" alt="edit-pertemuan-1" class="rounded border">
                <p>User kemudian mengubah data-data pertemuan dan menekan tombol simpan untuk menyimpan perubahan.</p>
                <img src="{{ asset('images/dokumentasi/edit-pertemuan-2.png') }}" alt="edit-pertemuan-2" class="rounded border">
                <p>Mengubah status menjadi <strong class="text-red-600">Tidak Terlaksana</strong> akan menghapus daftar presensi yang telah dibuat sedangkan mengubah status menjadi <strong class="text-green-600">Terlaksana</strong> akan membuat daftar presensi.</p>
              </section>
            </div>

            <div class="flex flex-col gap-2">
              <a id="reschedule-pertemuan" href="#reschedule-pertemuan" class="link text-base font-semibold text-upbg"># Reschedule Pertemuan</a>
              <p class="italic">Hak akses : {{ $permissions['reschedule-pertemuan'] }}</p>
              <section class="flex flex-col gap-6">
                <p>User dapat melakukan reschedule jadwal pertemuan yang akan datang.</p>
                <img src="{{ asset('images/dokumentasi/reschedule-pertemuan-1.png') }}" alt="reschedule-pertemuan-1" class="rounded border">
                <img src="{{ asset('images/dokumentasi/reschedule-pertemuan-2.png') }}" alt="reschedule-pertemuan-2" class="rounded border">
                <p>User juga dapat melakukan reschedule pertemuan yang sudah terlewat tetapi tidak terlaksana</p>
                <img src="{{ asset('images/dokumentasi/reschedule-pertemuan-3.png') }}" alt="reschedule-pertemuan-3" class="rounded border">
                <p>Menu ini merupakan subset dari menu <a href="#edit-pertemuan" class="link">edit pertemuan</a> dan hanya berlaku untuk Staf Pengajar. Pada user dengan hak akses lebih tinggi (Superuser atau Admin Pengajaran) menu ini diganti dengan menu <a href="#edit-pertemuan" class="link">edit pertemuan</a>.</p>
              </section>
            </div>

            <div class="flex flex-col gap-2">
              <a id="edit-topik-catatan" href="#edit-topik-catatan" class="link text-base font-semibold text-upbg"># Edit Topik Catatan</a>
              <p class="italic">Hak akses : {{ $permissions['edit-topik-catatan'] }}</p>
              <section class="flex flex-col gap-6">
                <p>Staf Pengajar dianjurkan mengisi topik bahasan pertemuan saat ini sehingga dapat mempermudah pengajar pengganti apabila suatu saat berhalangan hadir pada pertemuan selanjutnya. Sedangkan catatan digunakan untuk berkomunikasi dengan admin apabila diperlukan, salah satu contohnya adalah mengisi catatan dengan alasan ketika pertemuan tidak terlaksana.</p>
                <img src="{{ asset('images/dokumentasi/edit-topik-catatan-1.png') }}" alt="edit-topik-catatan-1" class="rounded border">
              </section>
            </div>

            <div class="flex flex-col gap-2">
              <a id="kelola-kelas" href="#kelola-kelas" class="link text-base font-semibold text-upbg"># Kelola Kelas</a>
              <p class="italic">Hak akses : {{ $permissions['kelola-kelas'] }}</p>
              <section class="flex flex-col gap-6">
                <p>Menu kelola kelas terdiri dari: <strong>Program</strong>, <strong>Tipe</strong>, <strong>Level</strong>, dan <strong>Kategori</strong>.</p>
                <p>Program, Tipe, dan Level berfungsi untuk menyusun kode dalam pembuatan kelas dan menggunakan aturan yang sama yaitu Soft Delete. Ketika dilakukan penghapusan Program, Tipe, atau Level akan ada opsi untuk hapus biasa (soft delete) atau hapus permanen.</p>
                <img src="{{ asset('images/dokumentasi/kelola-kelas-1.png') }}" alt="kelola-kelas-1" class="rounded border">
                <p>Hapus permanen berarti semua data kelas yang terasosiasi dengan data tersebut juga akan dihapus. Sedangkan soft delete berarti data akan tetap bisa diakses hanya saja pembuatan kelas baru dengan data tersebut tidak diperbolehkan. Untuk menjaga data legacy agar tetap dapat diakses disarankan untuk tidak menghapus data secara permanen. Data yang di soft delete dapat di restore.</p>
                <img src="{{ asset('images/dokumentasi/kelola-kelas-2.png') }}" alt="kelola-kelas-2" class="rounded border">
                <p>Sedangkan Kategori merupakan superset dari Tipe yang berfungsi dalam pembuatan laporan keuangan, menghapus sebuah kategori akan menghapusnya secara permanen dan mengubah Tipe yang terasosiasi menjadi tanpa kategori. Tipe yang tidak punya kategori akan tidak diikutkan pada pembuatan laporan.</p>
                <img src="{{ asset('images/dokumentasi/kelola-kelas-3.png') }}" alt="kelola-kelas-3" class="rounded border">
              </section>
            </div>
          </div>

          <div class="flex flex-col gap-6">
            <h1 class="text-lg font-semibold text-upbg">Tes</h1>

            <div class="flex flex-col gap-2">
              <a id="tambah-tes" href="#tambah-tes" class="link text-base font-semibold text-upbg"># Tambah Tes</a>
              <p class="italic">Hak akses : {{ $permissions['tambah-tes'] }}</p>
              <section class="flex flex-col gap-6">
                <p>Untuk membuat tes baru, user mengisi form pembuatan tes pada <a href="{{ route('tes.create') }}" target="_blank" class="link">halaman tambah tes</a>, user kemudian mengisi data-data yang diperlukan sesuai pada gambar. Beberapa aturan pengisian form adalah sebagai berikut:</p>
                <ol class="ml-4 list-outside list-decimal">
                  <li>Kolom <strong>Kode Tes</strong> tidak dapat diisi secara manual tetapi akan otomatis terisi dengan mengisi kolom - kolom dibawahnya.</li>
                </ol>
                <img src="{{ asset('images/dokumentasi/tambah-tes-1.png') }}" alt="tambah-tes-1" class="rounded border">
                <p>Setelah semua data terisi, tekan tombol tambah untuk menambahkan tes. Jika semua data telah memenuhi syarat, tes akan dibuat dan user akan diarahkan ke halaman tes tersebut.</p>
              </section>
            </div>

            <div class="flex flex-col gap-2">
              <a id="edit-tes" href="#edit-tes" class="link text-base font-semibold text-upbg"># Edit Tes</a>
              <p class="italic">Hak akses : {{ $permissions['edit-tes'] }}</p>
              <section class="flex flex-col gap-6">
                <p>Untuk mengedit tes, user menuju ke halaman tes yang ingin diedit kemudian memilih opsi edit tes.</p>
                <img src="{{ asset('images/dokumentasi/edit-tes-1.png') }}" alt="edit-tes-1" class="rounded border">
                <p>User kemudian dapat mengubah data-data tes sesuai kebutuhan dan menekan tombol simpan untuk menyimpan perubahan.</p>
                <img src="{{ asset('images/dokumentasi/edit-tes-2.png') }}" alt="edit-tes-2" class="rounded border">
                <p>Menghapus ruangan yang telah diasosiasikan dengan peserta akan mengubah ruangan peserta tersebut menjadi tanpa ruangan.</p>
              </section>
            </div>

            <div class="flex flex-col gap-2">
              <a id="hapus-tes" href="#hapus-tes" class="link text-base font-semibold text-upbg"># Hapus Tes</a>
              <p class="italic">Hak akses : {{ $permissions['hapus-tes'] }}</p>
              <section class="flex flex-col gap-6">
                <p>Untuk menghapus tes, user menuju ke halaman tes yang ingin dihapus kemudian memilih opsi hapus tes.</p>
                <img src="{{ asset('images/dokumentasi/hapus-tes-1.png') }}" alt="hapus-tes-1" class="rounded border">
                <p>Tekan hapus untuk konfirmasi penghapusan tes. Semua data tes tersebut akan dihapus permanen.</p>
                <img src="{{ asset('images/dokumentasi/hapus-tes-2.png') }}" alt="hapus-tes-2" class="rounded border">
              </section>
            </div>

            <div class="flex flex-col gap-2">
              <a id="mulai-tes" href="#mulai-tes" class="link text-base font-semibold text-upbg"># Mulai Tes</a>
              <p class="italic">Hak akses : {{ $permissions['mulai-tes'] }}</p>
              <section class="flex flex-col gap-6">
                <p>Ketika sudah memasuki waktunya, user dapat memulai tes dengan menekan tombol <strong>Mulai Tes</strong>.</p>
                <img src="{{ asset('images/dokumentasi/mulai-tes-1.png') }}" alt="mulai-pertemuan-1" class="rounded border">
                <p>Setelah pertemuan dimulai, user dapat mulai mengisi daftar kehadiran peserta.</p>
                <img src="{{ asset('images/dokumentasi/mulai-tes-2.png') }}" alt="mulai-pertemuan-2" class="rounded border">
              </section>
            </div>

            <div class="flex flex-col gap-2">
              <a id="tambah-peserta-tes" href="#tambah-peserta-tes" class="link text-base font-semibold text-upbg"># Tambah Peserta Tes</a>
              <p class="italic">Hak akses : {{ $permissions['kelola-peserta-tes'] }}</p>
              <section class="flex flex-col gap-6">
                <p>User dapat menambahkan peserta pada tes yang sudah dibuat dengan menuju ke halaman tes yang dinginkan dan memilih menu <strong>Daftar Peserta</strong>. Pada halaman daftar peserta, tekan tombol <strong>Tambah Peserta</strong> untuk menuju ke halaman tambah peserta.</p>
                <img src="{{ asset('images/dokumentasi/tambah-peserta-tes-1.png') }}" alt="tambah-peserta-tes-1" class="rounded border">
                <p>User dapat menambahkan peserta secara manual ataupun menggunakan file .xlsx atau .csv dengan aturan format file seperti tertera pada gambar. Untuk melihat format atau mengunduh file template tekan tulisan <strong>Panduan Pengunaan</strong>.</p>
                <img src="{{ asset('images/dokumentasi/tambah-peserta-tes-2.png') }}" alt="tambah-peserta-tes-2" class="rounded border">
                <img src="{{ asset('images/dokumentasi/tambah-peserta-tes-3.png') }}" alt="tambah-peserta-tes-3" class="rounded border">
                <p>Setelah semua data terisi, tekan tombol tambah untuk menambahkan peserta. Jika semua data telah memenuhi syarat, peserta akan ditambahkan ke kelas tersebut. Peserta akan diassign secara random ke ruangan yang masih memiliki kuota.</p>
              </section>
            </div>

            <div class="flex flex-col gap-2">
              <a id="edit-peserta-tes" href="#edit-peserta-tes" class="link text-base font-semibold text-upbg"># Edit Peserta Tes</a>
              <p class="italic">Hak akses : {{ $permissions['kelola-peserta-tes'] }}</p>
              <section class="flex flex-col gap-6">
                <p>User dapat mengubah ruangan peserta dengan menggantinya satu per satu atau secara grup berdasarkan ruangan.</p>
                <img src="{{ asset('images/dokumentasi/edit-peserta-tes-1.png') }}" alt="edit-peserta-tes-1" class="rounded border">
                <p>Opsi ruangan yang tersedia adalah ruangan yang telah diasosiasikan dengan tes ini.</p>
              </section>
            </div>

            <div class="flex flex-col gap-2">
              <a id="hapus-peserta-tes" href="#hapus-peserta-tes" class="link text-base font-semibold text-upbg"># Hapus Peserta Tes</a>
              <p class="italic">Hak akses : {{ $permissions['kelola-peserta-tes'] }}</p>
              <section class="flex flex-col gap-6">
                <p>User dapat menghapus peserta dari suatu tes dengan memilih peserta yang ingin dihapus dan pilih menu hapus.</p>
                <img src="{{ asset('images/dokumentasi/hapus-peserta-tes-1.png') }}" alt="hapus-peserta-tes-1" class="rounded border">
                <p>Tekan hapus untuk konfirmasi penghapusan peserta. Semua data tes peserta pada tes tersebut akan dihapus permanen.</p>
              </section>
            </div>
          </div>

          <div class="flex flex-col gap-6">
            <h1 class="text-lg font-semibold text-upbg">Laporan</h1>

            <div class="flex flex-col gap-2">
              <a id="buat-laporan-kelas" href="#buat-laporan-kelas" class="link text-base font-semibold text-upbg"># Buat Laporan Kelas</a>
              <p class="italic">Hak akses : {{ $permissions['buat-laporan-kelas'] }}</p>
              <section class="flex flex-col gap-6">
                <p>Pilih tanggal mulai dan tanggal akhir untuk menentukan interval. Jika ingin mengikutkan data pertemuan yang tidak terlaksana (Bulan Desember) maka centang checkbox Include Tidak Terlaksana kemudian tekan Search.</p>
                <img src="{{ asset('images/dokumentasi/buat-laporan-kelas-1.png') }}" alt="buat-laporan-kelas-1" class="rounded border">
                <p>Pilih kelas yang ingin dimasukkan dalam laporan kemudian tekan download.</p>
                <img src="{{ asset('images/dokumentasi/buat-laporan-kelas-2.png') }}" alt="buat-laporan-kelas-2" class="rounded border">
                <p>Untuk pertemuan yang <strong class="text-red-600">Tidak Terlaksana</strong> dengan pengajar lebih dari 1 pada sebuah kelas, pertemuan ini dianggap <strong class="text-green-600">Terlaksana</strong> untuk setiap pengajar.</p>
                <img src="{{ asset('images/dokumentasi/buat-laporan-kelas-3.png') }}" alt="buat-laporan-kelas-3" class="rounded border">
                <p>03/03 Terlaksana sehingga hanya ada pada Galih, sedangkan 05/03 dan 10/03 Tidak Terlaksana sehingga dianggap terlaksana untuk keduanya.</p>
              </section>
            </div>

            <div class="flex flex-col gap-2">
              <a id="buat-laporan-tes" href="#buat-laporan-tes" class="link text-base font-semibold text-upbg"># Buat Laporan Tes</a>
              <p class="italic">Hak akses : {{ $permissions['buat-laporan-tes'] }}</p>
              <section class="flex flex-col gap-6">
                <p>Pilih tanggal mulai dan tanggal akhir untuk menentukan interval. Jika ingin mengikutkan data pertemuan yang tidak terlaksana (Bulan Desember) maka centang checkbox Include Tidak Terlaksana kemudian tekan Search.</p>
                <img src="{{ asset('images/dokumentasi/buat-laporan-tes-1.png') }}" alt="buat-laporan-tes-1" class="rounded border">
                <p>Pilih tes yang ingin dimasukkan dalam laporan kemudian tekan download.</p>
                <img src="{{ asset('images/dokumentasi/buat-laporan-tes-2.png') }}" alt="buat-laporan-tes-2" class="rounded border">
              </section>
            </div>

            <div class="flex flex-col gap-2">
              <a id="buat-laporan-peserta" href="#buat-laporan-peserta" class="link text-base font-semibold text-upbg"># Buat Laporan Peserta</a>
              <p class="italic">Hak akses : {{ $permissions['buat-laporan-peserta'] }}</p>
              <section class="flex flex-col gap-6">
                <p>Cari nama departemen atau nama mahasiswa kemudian tekan Search.</p>
                <img src="{{ asset('images/dokumentasi/buat-laporan-peserta-1.png') }}" alt="buat-laporan-peserta-1" class="rounded border">
                <p>Tekan download.</p>
                <img src="{{ asset('images/dokumentasi/buat-laporan-peserta-2.png') }}" alt="buat-laporan-peserta-2" class="rounded border">
              </section>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</body>

</html>

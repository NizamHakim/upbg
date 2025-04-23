<x-guest-layout>
  <div class="flex flex-col gap-4 sm:hidden">
    <section>
      <div id="calendar"></div>
    </section>
    <section>
      <h1 class="text-center text-2xl font-semibold text-upbg">Jadwal UPBG</h1>
      <div id="jadwal-mobile-container">
        @include('jadwal.partials.jadwal-mobile', ['today' => $today, 'jadwalToday' => $jadwalToday])
      </div>
    </section>
  </div>

  <div class="hidden size-full flex-col sm:flex">
    <div class="mb-4 flex flex-col items-center gap-2">
      <h1 class="text-center text-2xl font-semibold text-upbg">Jadwal UPBG</h1>
      <div id="tanggal-display-desktop">
        @include('jadwal.partials.tanggal-desktop', ['start' => $start, 'end' => $end])
      </div>
      <input type="date" class="fp-datepicker input-appearance w-96 bg-white" placeholder="Pilih tanggal" name="datepicker-desktop" data-route="{{ route('jadwal') }}">
    </div>
    <div id="jadwal-desktop" class="jadwal scrollbar overflow-scroll rounded-lg bg-white shadow-sm">
      @include('jadwal.partials.jadwal-table', ['jadwalList' => $jadwalList, 'ruanganList' => $ruanganList])
    </div>
  </div>

  <x-modal id="detail-jadwal-tes">
    <x-slot name="title">Detail Jadwal</x-slot>
    <div id="mount-tes" class="flex flex-col gap-4"></div>
    <x-slot name="control">
      <button type="button" class="cancel-button btn btn-white border-none shadow-none">Close</button>
    </x-slot>
  </x-modal>

  <x-modal id="detail-jadwal-kelas">
    <x-slot name="title">Detail Jadwal</x-slot>
    <div id="mount-kelas" class="flex flex-col gap-4"></div>
    <x-slot name="control">
      <button type="button" class="cancel-button btn btn-white border-none shadow-none">Close</button>
    </x-slot>
  </x-modal>
  @pushOnce('scripts')
    <script src="{{ asset('js/jadwal/jadwal.js') }}"></script>
  @endPushOnce
</x-guest-layout>

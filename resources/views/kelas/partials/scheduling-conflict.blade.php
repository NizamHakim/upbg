@props(['hariOptions'])

<div id="scheduling-conflict" class="flex min-w-0 flex-col gap-2">
  <p class="input-label">Scheduling Conflict</p>

  <div class="scheduling-conflict-container flex min-w-0 flex-col gap-3" data-route="{{ route('kelas.verify-schedule') }}">
    <div class="scheduling-conflict-placeholder dotted-placeholder-div">
      Isi Ruangan, Banyak Pertemuan, Tanggal Mulai, dan Jadwal untuk melihat kemungkinan scheduling conflict
    </div>
    <div class="scheduling-conflict-data hidden"></div>
  </div>

  <p class="text-xs italic text-gray-400">note : kelas tetap dapat dibuat meskipun ada scheduling conflict, silahkan berkoordinasi dengan admin dan pengajar untuk penyesuaian jadwal</p>
</div>

@pushOnce('scripts')
  <script src="{{ asset('js/kelas/partials/scheduling-conflict.js') }}"></script>
@endPushOnce

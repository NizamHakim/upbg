@props(['presensi'])

@if ($presensi->hadir)
  <button type="submit" name="hadir" value="1" class="btn-hadir btn-rounded btn-white active">H</button>
  <button type="submit" name="hadir" value="0" class="btn-alfa btn-rounded btn-white">A</button>
@else
  <button type="submit" name="hadir" value="1" class="btn-hadir btn-rounded btn-white">H</button>
  <button type="submit" name="hadir" value="0" class="btn-alfa btn-rounded btn-white active">A</button>
@endif

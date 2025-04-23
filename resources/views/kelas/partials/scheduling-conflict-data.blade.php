@props(['conflictsList'])

<table>
  <thead>
    <th>Tanggal</th>
    <th>Ruangan</th>
    <th>Kegiatan</th>
  </thead>
  <tbody>
    @foreach ($conflictList as $conflict)
      <tr>
        <td>{{ $conflict['tanggal']->isoFormat('') }}</td>
        <td>{{ $conflict['ruangan']->kode }}</td>
      </tr>
    @endforeach
  </tbody>
</table>

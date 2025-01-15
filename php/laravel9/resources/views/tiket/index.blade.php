@include('layout.header')
        <h3>Tiket</h3>
        <a href=" {{ route('tiket.create') }}" class="tombol">Tambah</a>
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Asal</th>
                    <th>Tujuan</th>
                    <th>Penumpang</th>
                    <th>Transportasi</th>
                    <th>Pembayaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($allTiket as $key => $r)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $r->asal }}</td>
                    <td>{{ $r->tujuan }}</td>
                    <td>{{ $r->pengguna->nama_pengguna }}</td>
                    <td>{{ $r->transportasi->tipe_transportasi }}</td>
                    <td>{{ $r->bayar->nama_pembayar }}</td>
                    <td>
                        <form action="{{ route('tiket.destroy', $r->id) }}" method="POST">
                            <a href="{{ route('tiket.show', $r->id) }}" class="tombol">Detail</a>
                            <a href="{{ route('tiket.edit', $r->id) }}" class="tombol">Edit</a>
                            @csrf
                            @method("DELETE")
                            <button type="submit", class="tombol">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
@include('layout.footer')
@include('layout.header')
        <h3>Transportasi</h3>
        <a href=" {{ route('transportasi.create') }}" class="tombol">Tambah</a>
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Tipe Transportasi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($allTransportasi as $key => $r)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $r->tipe_transportasi }}</td>
                    <td>
                        <form action="{{ route('transportasi.destroy', $r->id) }}" method="POST">
                            <a href="{{ route('transportasi.show', $r->id) }}" class="tombol">Detail</a>
                            <a href="{{ route('transportasi.edit', $r->id) }}" class="tombol">Edit</a>
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
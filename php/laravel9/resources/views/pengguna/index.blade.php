@include('layout.header')
        <h3>Pengguna</h3>
        <a href=" {{ route('pengguna.create') }}" class="tombol">Tambah</a>
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Pengguna</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($allPengguna as $key => $r)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $r->nama_pengguna }}</td>
                    <td>
                        <form action="{{ route('pengguna.destroy', $r->id) }}" method="POST">
                            <a href="{{ route('pengguna.show', $r->id) }}" class="tombol">Detail</a>
                            <a href="{{ route('pengguna.edit', $r->id) }}" class="tombol">Edit</a>
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
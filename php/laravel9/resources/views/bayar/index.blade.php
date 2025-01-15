@include('layout.header')
        <h3>Pembayaran</h3>
        <a href=" {{ route('bayar.create') }}" class="tombol">Tambah</a>
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Pembayar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($allBayar as $key => $r)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $r->nama_pembayar }}</td>
                    <td>
                        <form action="{{ route('bayar.destroy', $r->id) }}" method="POST">
                            <a href="{{ route('bayar.show', $r->id) }}" class="tombol">Detail</a>
                            <a href="{{ route('bayar.edit', $r->id) }}" class="tombol">Edit</a>
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
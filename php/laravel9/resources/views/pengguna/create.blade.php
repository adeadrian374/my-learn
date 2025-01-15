@include('layout.header')
        <h3>Tambahkan Pengguna</h3>
        <form action="{{ route('pengguna.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="">Nama Pengguna</label>
                <input type="text" name="nama_pengguna" id="" placeholder="Masukkan Nama Pengguna">
            </div>
            <button type="submit" class="tombol">Tambahkan</button>
        </form>
        
@include('layout.footer')
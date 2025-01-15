@include('layout.header')
        <h3>Edit Pengguna</h3>
        <form action="{{ route('pengguna.update', $pengguna->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="">Nama Pengguna</label>
                <input type="text" name="nama_pengguna" id="" value="{{ $pengguna->nama_pengguna }}">
            </div>
            <button type="submit" class="tombol">Update</button>
        </form>
        
@include('layout.footer')
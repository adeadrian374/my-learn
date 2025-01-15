@include('layout.header')
        <h3>Edit Pembayar</h3>
        <form action="{{ route('bayar.update', $bayar->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="">Nama pembayar</label>
                <input type="text" name="nama_pembayar" id="" value="{{ $bayar->nama_pembayar }}">
            </div>
            <button type="submit" class="tombol">Update</button>
        </form>
        
@include('layout.footer')
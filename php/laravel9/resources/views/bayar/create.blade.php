@include('layout.header')
        <h3>Tambahkan Pembayar</h3>
        <form action="{{ route('bayar.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="">Nama Pembayar</label>
                <input type="text" name="nama_pembayar" id="" placeholder="Masukkan Nama Bayar">
            </div>
            <button type="submit" class="tombol">Tambahkan</button>
        </form>
        
@include('layout.footer')
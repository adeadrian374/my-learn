@include('layout.header')
        <h3>Tambahkan Transportasi</h3>
        <form action="{{ route('transportasi.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="">Tipe Transportasi</label>
                <input type="text" name="tipe_transportasi" id="" placeholder="Masukkan Nama Transportasi">
            </div>
            <button type="submit" class="tombol">Tambahkan</button>
        </form>
        
@include('layout.footer')
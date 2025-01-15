@include('layout.header')
        <h3>Tambahkan Tiket</h3>
        <form action="{{ route('tiket.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="">Asal Tiket</label>
                <input type="text" name="asal" id="" placeholder="Masukkan Asal Anda">
            </div>
            <div class="form-group">
                <label for="">Tujuan Tiket</label>
                <input type="text" name="tujuan" id="" placeholder="Masukkan Tujuan Anda">
            </div>
            <div class="form-group">
                <label for="">Transportasi</label>
                <select name="transportasi_id" id="">
                    @foreach ($transportasi as $t)
                    <option value="{{ $t->id }}">{{ $t->tipe_transportasi }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="">Penumpang</label>
                <select name="pengguna_id" id="">
                    @foreach ($pengguna as $p)
                    <option value="{{ $p->id }}">{{ $p->nama_pengguna }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="">Pembayar</label>
                <select name="bayar_id" id="">
                    @foreach ($bayar as $b)
                    <option value="{{ $b->id }}">{{ $b->nama_pembayar }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="tombol">Tambahkan</button>
        </form>
        
@include('layout.footer')
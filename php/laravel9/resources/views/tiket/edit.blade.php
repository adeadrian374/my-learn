@include('layout.header')
        <h3>Edit Tiket</h3>
        <form action="{{ route('tiket.update', $tiket->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="">Asal Tiket</label>
                <input type="text" name="asal" id="" value="{{ $tiket->asal }}">
            </div>
            <div class="form-group">
                <label for="">Tujuan Tiket</label>
                <input type="text" name="tujuan" id="" value="{{ $tiket->tujuan }}">
            </div>
            <div class="form-group">
                <label for="">Transportasi</label>
                <select name="transportasi_id" id="">
                    @foreach ($transportasi as $t)
                    <option value="{{ $t->id }}" {{ ($t->id == $tiket->transportasi_id) ? 'selected':'' }}>{{ $t->tipe_transportasi }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="">Penumpang</label>
                <select name="pengguna_id" id="">
                    @foreach ($pengguna as $p)
                    <option value="{{ $p->id }}" {{ ($p->id == $tiket->pengguna_id) ? 'selected':'' }}>{{ $p->nama_pengguna }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="">Pembayar</label>
                <select name="bayar_id" id="">
                    @foreach ($bayar as $b)
                    <option value="{{ $b->id }}" {{ ($b->id == $tiket->bayar_id) ? 'selected':'' }}>{{ $b->nama_pembayar }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="tombol">Update</button>
        </form>
        
@include('layout.footer')
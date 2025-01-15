@include('layout.header')
        <h3>Edit Transportasi</h3>
        <form action="{{ route('transportasi.update', $transportasi->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="">Tipe Transportasi</label>
                <input type="text" name="tipe_transportasi" id="" value="{{ $transportasi->tipe_transportasi }}">
            </div>
            <button type="submit" class="tombol">Update</button>
        </form>
        
@include('layout.footer')
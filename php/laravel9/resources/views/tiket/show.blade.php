@include('layout.header')
        <h3>Detail Tiket</h3>
        <table>
            <tbody>
                <tr>
                    <td width="150px">Asal</td>
                    <td>{{ $tiket->asal }}</td>
                </tr>
                <tr>
                    <td>Tujuan</td>
                    <td>{{ $tiket->tujuan }}</td>
                </tr>
                <tr>
                    <td>Nama Penumpang</td>
                    <td>{{ $tiket->pengguna->nama_pengguna}}</td>
                </tr>
                <tr>
                    <td>Jenis Transportasi</td>
                    <td>{{ $tiket->transportasi->tipe_transportasi}}</td>
                </tr>
                <tr>
                    <td>Nama Pembayar</td>
                    <td>{{ $tiket->bayar->nama_pembayar}}</td>
                </tr>
            </tbody>
        </table>
@include('layout.footer')
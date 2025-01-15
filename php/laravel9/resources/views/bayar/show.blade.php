@include('layout.header')
        <h3>Detail Pembayaran</h3>
        <table>
            <tbody>
                <tr>
                    <td width="150px">Nama Pembayar</td>
                    <td>{{ $bayar->nama_pembayar }}</td>
                </tr>
            </tbody>
        </table>
@include('layout.footer')
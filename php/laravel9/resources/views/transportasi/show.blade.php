@include('layout.header')
        <h3>Detail Transportasi</h3>
        <table>
            <tbody>
                <tr>
                    <td width="150px">Tipe Transportasi</td>
                    <td>{{ $transportasi->tipe_transportasi }}</td>
                </tr>
            </tbody>
        </table>
@include('layout.footer')
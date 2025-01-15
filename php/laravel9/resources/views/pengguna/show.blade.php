@include('layout.header')
        <h3>Detail Pengguna</h3>
        <table>
            <tbody>
                <tr>
                    <td width="150px">Nama Pengguna</td>
                    <td>{{ $pengguna->nama_pengguna }}</td>
                </tr>
            </tbody>
        </table>
@include('layout.footer')
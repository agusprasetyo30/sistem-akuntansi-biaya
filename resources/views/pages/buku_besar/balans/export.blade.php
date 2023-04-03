<table border=1 style="border-collapse: collapse">
    <thead>
        <tr >
            <td align="center" valign="middle" rowspan="3"><b>Material</b></td>
            <td align="center" valign="middle" rowspan="3"><b>Plant Code</b></td>
            <td align="center" valign="middle" rowspan="3"><b>Keterangan</b></td>
            
            @foreach ($asumsi_umum as $data)
                <td align="center" valign="middle" colspan="3" data-format="dd/mm/yyyy"><b>{{ date('d/m/Y', strtotime($data->month_year)) }} </b></td>
            @endforeach
        </tr>
        <tr>
            @foreach ($asumsi_umum as $data)
                <td align="center" valign="middle">Q</td>
                <td align="center" valign="middle">P</td>
                <td align="center" valign="middle">Nilai = Q X P</td>
            @endforeach
        </tr>
        <tr>
            @foreach ($asumsi_umum as $data)
                <td align="center" valign="middle">Ton</td>
                <td align="center" valign="middle">Rp/Ton</td>
                <td align="center" valign="middle">Nilai (Rp)</td>
            @endforeach
        </tr>

        @foreach ($balans_datas as $key => $data)
            <tr>
                <td>{{ $data->material_code }}</td>
                <td>{{ $data->plant_code }}</td>
                <td>{{ $data->kategori_balans->kategori_balans_desc }}</td>

                @foreach ($asumsi_umum as $key_asumsi => $item)
                    @if ($data->kategori_balans_id == 6 || $data->kategori_balans->kategori_balans_desc == 'Saldo Akhir')
                        @if ($fixed_value_data['q'][$key_asumsi][$key] < 0)
                            <td style="background-color: red" data-format='Rp* #,##0.00_-;Rp* -#,##0.00_-;Rp* "0"_-;_-@_-'>{{ $fixed_value_data['q'][$key_asumsi][$key] }}</td>
                            <td style="background-color: red" data-format='Rp* #,##0.00_-;Rp* -#,##0.00_-;Rp* "0"_-;_-@_-'>{{ $fixed_value_data['p'][$key_asumsi][$key] }}</td>
                            <td style="background-color: red" data-format='Rp* #,##0.00_-;Rp* -#,##0.00_-;Rp* "0"_-;_-@_-'>{{ $fixed_value_data['nilai'][$key_asumsi][$key] }}</td>

                        @else
                            <td data-format='Rp* #,##0.00_-;[Red]Rp* -#,##0.00_-;Rp* "0"_-;_-@_-'>{{ $fixed_value_data['q'][$key_asumsi][$key] }}</td>
                            <td data-format='Rp* #,##0.00_-;Rp* -#,##0.00_-;Rp* "0"_-;_-@_-'>{{ $fixed_value_data['p'][$key_asumsi][$key] }}</td>
                            <td data-format='Rp* #,##0.00_-;Rp* -#,##0.00_-;Rp* "0"_-;_-@_-'>{{ $fixed_value_data['nilai'][$key_asumsi][$key] }}</td>
                        @endif
                    @else
                        <td data-format='Rp* #,##0.00_-;Rp* -#,##0.00_-;Rp* "0"_-;_-@_-'>{{ $fixed_value_data['q'][$key_asumsi][$key] }}</td>
                        <td data-format='Rp* #,##0.00_-;Rp* -#,##0.00_-;Rp* "0"_-;_-@_-'>{{ $fixed_value_data['p'][$key_asumsi][$key] }}</td>
                        <td data-format='Rp* #,##0.00_-;Rp* -#,##0.00_-;Rp* "0"_-;_-@_-'>{{ $fixed_value_data['nilai'][$key_asumsi][$key] }}</td>
                    @endif

                @endforeach
            </tr>
        @endforeach
    </thead>

</table>

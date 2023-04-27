<table border=1 style="border-collapse: collapse">
    <thead>
        <tr>
            <td align="center" valign="top" rowspan="3"><b>Biaya</b></td>
            <td align="center" valign="top" rowspan="3"><b>Material</b></td>
            
            @foreach ($product_lists as $product)
                <td colspan="4">
                    <b>{{ $product->product_code }} {{ $product->material_name }}</b>
                </td>
            @endforeach

        </tr>
        <tr>
            @foreach ($product_lists as $product)
                <td colspan="4">
                    <b>{{ $product->plant_code }} {{ $product->plant_desc }}</b>
                </td>
            @endforeach
        </tr>
        
        <tr>
            @foreach ($product_lists as $product)
                <td>Harga Satuan</td>
                <td>CR</td>
                <td>Biaya Per Ton</td>
                <td>Total Biaya</td>
            @endforeach
        </tr>

    </thead>
    <tbody>
        @php
            // Variabel ini digunakan untuk inisialiasi index manual (karena kalau diambil dari data foreach pas dilakukan filtering datanya beda)
            $key_temp = 0;
        @endphp
        @foreach ($material_lists as $key => $material)    
            <tr>
                <td>{{ $material->material_code }}</td>
                <td>{{ $material->material_name }}</td>
    
                @foreach ($product_lists as $product)
                    <td data-format='Rp* #,##0_-;Rp* -#,##0_-;"-"_-;_-@_-'>{{ $fixed_value_data['harga_satuan'][$key_temp][$key] }}</td>
                    <td data-format='#,##0.00_-;-#,##0.00_-;"-"_-;_-@_-'>{{ $fixed_value_data['cr'][$key_temp][$key] }}</td>
                    <td data-format='Rp* #,##0_-;Rp* -#,##0_-;"-"_-;_-@_-'>{{ $fixed_value_data['biaya_per_ton'][$key_temp][$key] }}</td>
                    <td data-format='Rp* #,##0_-;Rp* -#,##0_-;"-"_-;_-@_-'>{{ $fixed_value_data['total_biaya'][$key_temp][$key] }}</td>

                    @php
                        $key_temp++;
                    @endphp
                @endforeach
            </tr>

            @php
                $key_temp = 0;
            @endphp
        @endforeach

        <tr>
            <td colspan="2" style="text-align: right">
                <b>Total</b>
            </td>
            
            @foreach ($product_lists as $product)
                <td data-format='Rp* #,##0_-;Rp* -#,##0.00_-;Rp* "0"_-;_-@_-'>{{ $total['harga_satuan'][$key_temp] }}</td>
                <td data-format='#,##0.00_-;-#,##0.00_-;"0"_-;_-@_-'>{{ $total['cr'][$key_temp] }}</td>
                <td data-format='Rp* #,##0_-;Rp* -#,##0.00_-;"0"_-;_-@_-'>{{ $total['biaya_per_ton'][$key_temp] }}</td>
                <td data-format='Rp* #,##0_-;Rp* -#,##0.00_-;Rp* "0"_-;_-@_-'>{{ $total['total_biaya'][$key_temp] }}</td>

                @php
                    $key_temp++;
                @endphp
            @endforeach
            
        </tr>
    </tbody>
</table>

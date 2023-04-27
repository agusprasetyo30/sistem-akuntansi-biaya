<table border=1 style="border-collapse: collapse">
    <thead>
        <tr>
            <td align="center" valign="middle" rowspan="5">Jenis Biaya</td>
			
			@foreach ($asumsi as $data_asumsi)
				<td align="center" valign="default" colspan="4">{{ date('F Y', strtotime($data_asumsi->month_year)) }}</td>
			@endforeach
		</tr>
		{{-- <tr>
		</tr> --}}
		<tr>
			@for ($i = 0; $i < $asumsi->count(); $i++)			
				<td align="center" valign="default" colspan="4">{{ $product->first()->material_code . ' ' . $product->first()->material_name }}</td>
			@endfor
		</tr>
		<tr>
			@for ($i = 0; $i < $asumsi->count(); $i++)			
				<td align="center" valign="default" colspan="4">{{ $plant->first()->plant_code . ' ' . $plant->first()->plant_desc }}</td>
			@endfor

		</tr>
		<tr>
			@foreach ($kp as $simulasi_proyeksi)
				<td align="center" valign="default" colspan="4">Kuantum Produksi {{ number_format($simulasi_proyeksi->kuantum_produksi, 0, ",", ".") }}</td>
			@endforeach
		</tr>
		<tr>
			@foreach ($asumsi as $data_asumsi)
				<td align="center" valign="top">Harga Satuan</td>
				<td align="center" valign="top">CR</td>
				<td align="center" valign="top">Harga Per Ton</td>
				<td align="center" valign="top">Total Biaya</td>
			@endforeach
		</tr>
	</thead>
	<tbody>
		@php
            // Variabel ini digunakan untuk inisialiasi index manual (karena kalau diambil dari data foreach pas dilakukan filtering datanya beda)
            $key_temp = 0;
        @endphp
		@foreach ($query as $key => $data)
			<tr>
				<td style="text-align: left">
					@if ($data->kategori == 0)
						<b>{{ $data->name }}</b>
					@else
						{{ $data->name }}
					@endif
				</td>

				@foreach ($asumsi as $asumsi_data)

					@if (($data->no == 1 && $data->kategori == 0) || 
						($data->no == 2 && $data->kategori == 0) || 
						($data->no == 3 && $data->kategori == 0) || 
						($data->no == 4 && $data->kategori == 0) || 
						($data->no == 6 && $data->kategori == 0))
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					@else
					
						@if ($data->no == 16 && $data->kategori == 0)
							<td data-format='$* #,##0.00_-'>{{ $fixed_value_data['harga_satuan'][$key_temp][$key] }}</td>
							<td data-format='#,##0.00_-;-#,##0.00_-;"0"_-;_-@_-'>{{ $fixed_value_data['cr'][$key_temp][$key] }}</td>
							<td data-format='$* #,##0.00_-'>{{ $fixed_value_data['biaya_per_ton'][$key_temp][$key] }}</td>
							<td data-format='$* #,##0.00_-'>{{ $fixed_value_data['total_biaya'][$key_temp][$key] }}</td>
						@else
							<td data-format='Rp* #,##0_-;Rp* -#,##0_-;Rp* "0"_-;_-@_-'>{{ $fixed_value_data['harga_satuan'][$key_temp][$key] }}</td>
							<td data-format='#,##0.00_-;-#,##0.00_-;"0"_-;_-@_-'>{{ $fixed_value_data['cr'][$key_temp][$key] }}</td>
							<td data-format='Rp* #,##0_-;Rp* -#,##0_-;"0"_-;_-@_-'>{{ $fixed_value_data['biaya_per_ton'][$key_temp][$key] }}</td>
							<td data-format='Rp* #,##0_-;Rp* -#,##0_-;Rp* "0"_-;_-@_-'>{{ $fixed_value_data['total_biaya'][$key_temp][$key] }}</td>
						@endif
					@endif

					
				@endforeach
			</tr>
		@php
			$key_temp = 0;
		@endphp
		@endforeach
	</tbody>
</table>
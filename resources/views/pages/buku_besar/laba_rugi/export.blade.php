<table border=1 style="border-collapse: collapse">
	<thead>
		<tr>
			<td align="center" valign="top"><b>Versi</b></td>
			<td align="center" valign="top"><b>Kategori Produk</b></td>
			<td align="center" valign="top"><b>Biaya Penjualan</b></td>
			<td align="center" valign="top"><b>Biaya Adm Umum</b></td>
			<td align="center" valign="top"><b>Biaya Bunga</b></td>
		</tr>
	</thead>
	<tbody>
		@foreach ($labarugi as $dt)
		<tr>
			<td >{{ $dt->version }}</td>
			<td >{{ $dt->prod }}</td>
			<td data-format='Rp* #,##0_-;Rp* -#,##0_-;"0"_-;_-@_-'>{{ $dt->value_bp }}</td>
			<td data-format='Rp* #,##0_-;Rp* -#,##0_-;"0"_-;_-@_-'>{{ $dt->value_bau }}</td>
			<td data-format='Rp* #,##0_-;Rp* -#,##0_-;"0"_-;_-@_-'>{{ $dt->value_bb }}</td>

		</tr>
		@endforeach
	</tbody>
</table>
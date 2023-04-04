<table border=1 style="border-collapse: collapse">
	<thead>
		<tr>
			<td align="center" valign="top"><b>Versi</b></td>
			<td align="center" valign="top"><b>Priode</b></td>
			<td align="center" valign="top"><b>Material</b></td>
			<td align="center" valign="top"><b>Value</b></td>
		</tr>
	</thead>
	<tbody>
		@foreach ($pemakaian as $dt)
		<tr>
			<td >{{ $dt->version }}</td>
			<td data-format='[$-id]mm yyyy'>{{ date('m/Y', strtotime($dt->month_year)) }}</td>
			<!-- <td data-format='mm-yyyy'>{{ $dt->month_year }}</td> -->
			<td >{{ $dt->materialjoin }}</td>
			<td data-format='#,##0_-;-#,##0_-;"0"_-;_-@_-'>{{ $dt->pj_pemakaian_value }}</td>
		</tr>
		@endforeach
	</tbody>
</table>

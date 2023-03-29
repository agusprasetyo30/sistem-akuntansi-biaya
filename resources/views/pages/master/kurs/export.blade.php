<table border=1 style="border-collapse: collapse">
	<thead>
		<tr>
			<td align="center" valign="top"><b>Periode</b></td>
			<td align="center" valign="top"><b>Kurs</b></td>
		</tr>
	</thead>
	<tbody>
		@foreach ($kurs as $dtkurs)
		<tr>
			<td data-format='mm/yyyy'>{{ date('m/Y', strtotime($dtkurs->month_year)) }}</td>
			<td data-format='Rp* #,##0_-;Rp* -#,##0_-;"0"_-;_-@_-'>{{ $dtkurs->usd_rate }}</td>

		</tr>
		@endforeach
	</tbody>
</table>
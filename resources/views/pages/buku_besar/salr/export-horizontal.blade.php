<table border=1 style="border-collapse: collapse">
	<thead>
		<tr>
			<td rowspan="2" style="text-align: center"><b>Group Account</b></td>
			<td rowspan="2" style="text-align: center"><b>Group Account Desc</b></td>

			@foreach ($cost_centers as $cost_center)
				<td style="text-align: center"><b>{{ $cost_center->cost_center }}</b></td>
			@endforeach
		</tr>
		<tr>
			@foreach ($cost_centers as $cost_center)
				<td style="text-align: center"><b>{{ $cost_center->cost_center_desc }}</b></td>
			@endforeach
		</tr>
	</thead>
	<tbody>
		@foreach ($group_accounts as $key => $group_account)
			<tr>
				<td>{{ $group_account->group_account_fc }}</td>
				<td>{{ $group_account->group_account_fc_desc }}</td>

				@foreach ($cost_centers as $key_cost_center => $cost_center)
					<td data-format='Rp* #,##0_-;Rp* -#,##0_-;"-"_-;_-@_-'>{{ $fixed_value_data['value'][$key_cost_center][$key] }}</td>
				@endforeach
			</tr>
		@endforeach
		
		<tr>
			<td colspan="1" style="text-align: center"><b>Total</b></td>
			<td colspan="1" style="text-align: center"><b>Perhitungan</b></td>
			@php $key_temp = 0 @endphp
			
			@foreach ($cost_centers as $cost_center)
				<td data-format='Rp* #,##0_-;Rp* -#,##0_-;Rp* "0"_-;_-@_-'>{{ $total['value'][$key_temp] }}</td>

				@php $key_temp++ @endphp
			@endforeach
		</tr>
	</tbody>
</table>
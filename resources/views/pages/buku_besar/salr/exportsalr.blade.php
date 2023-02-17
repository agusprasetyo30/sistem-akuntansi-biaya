<table>
    <thead>
      <tr>
        <th>Cost Center</th>
        <th>Gl Account FC</th>
        <th>Company Code</th>
      </tr>
    </thead>
    <tbody>
        @foreach ($exportsalr as $items)
        <tr>
            <th>{{ $items->cost_center }}</th>
            <td>{{ $items->gl_account_fc }}</td>
            <td>{{ $items->company_code }}</td>
        </tr>
      @endforeach
    </tbody>
</table>
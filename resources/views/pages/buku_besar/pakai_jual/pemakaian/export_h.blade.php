<table border=1 style="border-collapse: collapse">
    <thead>
        <tr>
            @foreach ($header as $dtheader)
            @if($loop->index < 2)
            <td>{{ $dtheader }}</td>
            @else
            <td data-format='[$-id]mmmm yyyy'>{{ date('F Y', strtotime($dtheader)) }}</td>
            @endif
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($body as $dtbody)
            <tr>
            @foreach ($dtbody as $dt)
            @if($loop->index < 2)
            <td>{{$dt}}</td>
            @else
            <td data-format='#,##0_-;-#,##0_-;"0"_-;_-@_-'>{{ $dt }}</td>
            @endif
            @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
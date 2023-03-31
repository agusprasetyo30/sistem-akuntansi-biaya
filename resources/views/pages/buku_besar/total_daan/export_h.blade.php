<table border=1 style="border-collapse: collapse">
    <thead>
        <tr>
            @foreach ($header as $dtheader)
            @if($loop->index < 2) <td>{{ $dtheader }}</td>
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
                @if($dt == -2)
                <td>-</td>
                @else
                    @if( $dt == -1 )
                        <td style="background-color: salmon">-</td>
                    @else
                        @if($mata_uang == 'IDR')
                        <td style="background-color: darkseagreen" data-format='Rp* #,##0_-;Rp* -#,##0_-;"0"_-;_-@_-'>{{ $dt }}</td>
                        @else
                        <td style="background-color: darkseagreen" data-format='$* #,##0.00_-'>{{ $dt }}</td>
                        @endif
                    @endif
                @endif
            @endif
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>
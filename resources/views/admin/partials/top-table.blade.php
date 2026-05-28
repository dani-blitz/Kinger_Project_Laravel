<table style="width:100%">
    @foreach($items as $item)
        <tr><td>{{ $item->$nameField }}</td><td>{{ $item->total }}</td></tr>
    @endforeach
</table>

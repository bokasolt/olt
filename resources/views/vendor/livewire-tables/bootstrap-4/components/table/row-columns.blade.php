@foreach($columns as $column)
    @if ($column->isVisible())
        <td class="column-{{ $column->column }}">
            <span>
                @if ($column->asHtml)
                {{ new \Illuminate\Support\HtmlString($column->formatted($row)) }}
            @else
                {{ $column->formatted($row) }}
            @endif
            </span>
        </td>
    @endif
@endforeach

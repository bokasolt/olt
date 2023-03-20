<x-livewire-tables::bs4.table.cell>
    {{ $row->name }}
</x-livewire-tables::bs4.table.cell>

<x-livewire-tables::bs4.table.cell>
    {{ $row->url }}
</x-livewire-tables::bs4.table.cell>

<x-livewire-tables::bs4.table.cell>
    @include('backend.google-sheet.includes.actions', ['model' => $row])
</x-livewire-tables::bs4.table.cell>

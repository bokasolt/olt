@if ($domain->users_count)
    <span class="badge badge-secondary">Ordered: {{ $domain->users_count }}</span>
@else
@unless($domain->deleted_at)
    <input type="checkbox" wire:model="checkbox_values" value="{{ $domain->id }}">
@endunless
@endif

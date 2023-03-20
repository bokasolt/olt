<div class="text-nowrap">
    @if ($logged_in_user->can('admin.access.google-sheet.change'))
        <x-utils.edit-button :href="route('admin.google-sheet.edit', $row)" text="" />
    @endif
    @if ($logged_in_user->can('admin.access.google-sheet.delete'))
        <x-utils.delete-button :href="route('admin.google-sheet.destroy', $row)" text="" />
    @endif
</div>

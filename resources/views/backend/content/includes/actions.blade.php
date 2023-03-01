<div class="text-nowrap">
    <x-utils.edit-button :href="route('admin.content.edit', $content)" text="" />
    <x-utils.delete-button
            :href="route('admin.content.destroy', $content)"
            text="" />
</div>
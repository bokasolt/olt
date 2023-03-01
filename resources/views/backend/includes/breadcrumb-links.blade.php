@if ($logged_in_user->hasAllAccess() || $logged_in_user->can('admin.import.domain'))
    <x-utils.link class="c-subheader-nav-link" :href="route('admin.import.domain.index')" :text="__('Import domains')" />
@endif

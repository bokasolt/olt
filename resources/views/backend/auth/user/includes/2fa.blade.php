@if ($user->hasTwoFactorEnabled())
    <span class="badge badge-success" data-toggle="tooltip" title="{{ $user->twoFactorAuth->enabled_at }}">@lang('Yes')</span>
@else
    <span class="badge badge-danger">@lang('No')</span>
@endif

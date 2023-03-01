@props(['href' => '#', 'permission' => false, 'text' => 'View'])

<x-utils.link :href="$href" class="btn btn-info btn-sm mb-1" icon="fas fa-search" :text="$text" permission="{{ $permission }}" />

@props(['href' => '#', 'text' => __('Edit'), 'permission' => false])

<x-utils.link :href="$href" class="btn btn-primary btn-sm mb-1" icon="fas fa-pencil-alt" text="{{ $text }}" permission="{{ $permission }}" />

<form method="post" enctype="multipart/form-data" {{ $attributes->merge(['action' => '#', 'class' => 'form-horizontal']) }}>
    @csrf

    {{ $slot }}
</form>

@props(['classes' => 'table table-striped'])
<div class="table-responsive">
    <table class="{{ $classes }}">
        <thead>
            <tr>
                {{ $head }}
            </tr>
        </thead>

        <tbody>
            {{ $body }}
        </tbody>
    </table>
</div>

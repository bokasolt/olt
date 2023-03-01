<div class="modal fade" role="dialog" tabindex="-1" id="purchaseModal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Purchase</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>To continue please purchase  (${{ $price }} per website). Minimum order is {{ $minOrder }} websites.</p>
                <x-utils.link
                    :href="route('frontend.user.purchase')"
                    :active="activeClass(Route::is('frontend.user.purchase'))"
                    :text="__('Purchase')"
                    class="btn btn-primary"/>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')
    <script src="{{ mix('js/livewire.js') }}"></script>
@endpush

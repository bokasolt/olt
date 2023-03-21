@extends('backend.layouts.app')

@section('title', __('Dashboard'))

@section('breadcrumb-links')
    @include('backend.includes.breadcrumb-links')
@endsection


@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Welcome :Name', ['name' => $logged_in_user->name])
        </x-slot>

        <x-slot name="headerActions">
                <x-utils.link
                    class="card-header-action"
                    :href="route('admin.trashed-domains')"
                    :text="__('Removed domains')"
                />
                <x-utils.link
                    icon="c-icon cil-sync"
                    class="card-header-action"
                    :href="route('admin.ahrefs.sync-all')"
                    :text="__('Update metrics')"
                />
                <x-utils.link
                    icon="c-icon cil-plus"
                    class="card-header-action"
                    :href="route('admin.domain.create')"
                    :text="__('Add Domain')"
                />
        </x-slot>

        <x-slot name="body">
            @lang('Dashboard')
            <livewire:backend.domains-table />
        </x-slot>
    </x-backend.card>

    <script>

        $( "html" ).delegate( ".column-title, .column-additional_notes", "click", function () {
            console.log('here')
            let element = $(this)
            setTimeout(function () {
                if (!element.hasClass('editorField') && !element.hasClass('clicked') && element.text().trim() !== '') {
                    $('.column-title').removeClass("clicked");
                    $('.column-additional_notes').removeClass("clicked");
                    $('.moreInfo').remove();
                    element.toggleClass("clicked");
                    const text = element.text();
                    element.append('<div class="moreInfo">' + text + ' <span class="closeMoreInfo">X</span></div>')
                }
            }, 200)
        })

        $( "html" ).delegate( ".closeMoreInfo", "click", function () {
            $('.column-title').removeClass("clicked");
            $('.column-additional_notes').removeClass("clicked");
            $('.moreInfo').remove();
        });

        $( "html" ).delegate( ".column-price", "dblclick", function () {
            if (!$(this).hasClass('editorField')) {
                $(this).toggleClass("editorField");
                const text = parseFloat($(this).text());
                $(this).append('<div class="editor"><input type="text" name="price" style="border:1px solid #d8dbe0" value="'+text+'"> ' +
                    '<button onclick="save($(this))" class="btn btn-sm btn-primary">save</button>' +
                    '<button onclick="closeEditor($(this))" class="card-header-action" style="border: none;">close</button></div>')
            }
        })

        $( "html" ).delegate( ".column-additional_notes", "dblclick", function () {
            if (!$(this).hasClass('editorField') && !$(this).hasClass('clicked')) {
                $(this).toggleClass("editorField");
                $(this).toggleClass("clicked");
                const text = $(this).text().trim();
                $(this).append('<div class="editor"><textarea type="text" name="additional_notes" style="border:1px solid #d8dbe0">'+text+'</textarea> ' +
                    '<button onclick="save($(this))" class="btn btn-sm btn-primary">save</button>' +
                    '<button onclick="closeEditor($(this))" class="card-header-action" style="border: none;">close</button></div>')
            }
        })

        $( "html" ).delegate( "body", "mouseup", function (e) {
            if ($(".moreInfo") && e.target !== $(".moreInfo")[0]){
                $('.column-title').removeClass("clicked");
                $('.column-additional_notes').removeClass("clicked");
                $('.moreInfo').remove();
            }
        });

        document.addEventListener('scroll', function (event) {
            $('.column-title').removeClass("clicked");
            $('.column-additional_notes').removeClass("clicked");
            $('.moreInfo').remove();
        }, true /*Capture event*/);

        function closeEditor(element) {
            element.parent().parent().removeClass('editorField')
            element.parent().remove()
            $('.column-title').removeClass("clicked");
            $('.column-additional_notes').removeClass("clicked");
        }

        function save(element) {
            const domain = element.parent().parent().parent().find('.column-domain h5 a').text()
            const price = element.siblings('input[name="price"]').val()
            const additional_notes = element.siblings('textarea[name="additional_notes"]').val()

            const _token = $('meta[name="csrf-token"]').attr('content')

            $.ajax({
                url: '{{ route('admin.domain.quickEdit') }}',
                type: 'patch',
                data: {
                    domain: domain,
                    price: price,
                    additional_notes: additional_notes,
                    _token: _token
                },
                dataType: 'json',
                beforeSend: function () {
                    $('#tableGoogleSheet').css('opacity', 0.5);
                },
                complete: function () {
                    $('#tableGoogleSheet').css('opacity', 1);
                },
                success: function (json) {
                    if (json['price'] && price) {
                        element.parent().parent().find('span').text(json['price'])
                        closeEditor(element)
                    }
                    if (json['additional_notes'] && additional_notes) {
                        element.parent().parent().find('span').text(json['additional_notes'])
                        closeEditor(element)
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    if (xhr && xhr.responseJSON && xhr.responseJSON.message) {
                        alert(thrownError + '\n\r' + xhr.responseJSON.message);
                    } else {
                        alert(xhr.responseText);
                    }
                }
            })
        }
    </script>
@endsection

@extends('backend.layouts.app')

@section('title', __('Update Domain'))

@section('content')
    <form>
        <x-backend.card>
            <x-slot name="header">
                @lang('Import google sheet')
            </x-slot>

            <x-slot name="headerActions">
                <button class="btn btn-sm btn-primary float-right import" type="submit">@lang('Upload')</button>
                <x-utils.link class="card-header-action" :href="route('admin.google-sheet.index')" :text="__('Cancel')"/>
            </x-slot>

            <x-slot name="body">

                <table>
                        @if($googleSheet->associations && $googleSheetData)
                            <tr>
                                <td><input type="checkbox" id="checkAll"></td>
                                @foreach($googleSheet->associations as $association)
                                    <td>{{ $association['db_column'] }}</td>
                                @endforeach
                            </tr>

                            @foreach($googleSheetData->values as $numberRow => $row)
                                @if($numberRow && !empty($row))
                                    <tr>
                                        <td><input type="checkbox" value="{{ $numberRow }}" name="rows[]"></td>
                                        @foreach($googleSheet->associations as $association)
                                            @if(isset($row[$association['gs_column']]))
                                                <td>{{ $row[$association['gs_column']] }}</td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    </tr>
                </table>

                <!-- Modal -->
                <div class="modal fade" id="existingModal" tabindex="-1" role="dialog" aria-labelledby="existingModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="existingModalLabel">@lang('Domains already exist in the database')</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary import">@lang('Upload')</button>
                            </div>
                        </div>
                    </div>
                </div>
            </x-slot>

            <x-slot name="footer">
            </x-slot>
        </x-backend.card>
    </form>


    <script>
        $(document).ready(function(){
            $(document).delegate('#checkAll', 'click', function(e) {
                if ($('#checkAll:checked').length) {
                    $('input[type="checkbox"]').attr('checked', 'checked')
                } else {
                    $('input[type="checkbox"]').removeAttr('checked')
                }
            });

            $(document).delegate('.import', 'click', function(e) {

                e.preventDefault()

                const formData = $('form').serializeArray();
                $.ajax({
                    url: '{{ route('admin.google-sheet.import', $googleSheet->id) }}',
                    type: 'post',
                    data: formData,
                    dataType: 'json',
                    beforeSend: function() {
                        $('#tableGoogleSheet').css('opacity', 0.5);
                    },
                    complete: function() {
                        $('#tableGoogleSheet').css('opacity', 1);
                    },
                    success: function(json) {
                        if (json['existing']) {

                            let entity = '';

                            json['existing'].forEach( item => {
                                if (item.domain) {
                                    entity += '<div class="existingItem">' +
                                        item.domain + ' <div><label> overwrite <input type="radio" name="overwrite[' + item.domain + ']" value="1" checked></label>'
                                        + ' <label> skip <input type="radio" name="overwrite[' + item.domain + ']" value="0"></label></div>'
                                        + '</div>'
                                }
                            })

                            $('#existingModal .modal-body').html(entity)
                            $('#existingModal').modal('show')
                        }

                        if (json['redirect'] && json['message']) {
                            alert(json['message'])
                            window.location.href = json['redirect']
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        if (xhr && xhr.responseJSON && xhr.responseJSON.message) {
                            if (xhr.responseJSON.errors && xhr.responseJSON.errors.rows) {
                                alert(thrownError + '\n\r' + xhr.responseJSON.errors.rows[0]);
                            } else {
                                alert(thrownError + '\n\r' + xhr.responseJSON.message);
                            }
                        } else {
                            alert(xhr.responseText);
                        }
                    }
                });
            });

        });
    </script>
    <style>
        td {
            border: 1px solid #d8dbe0;
            padding: 5px;
        }
        .modal-body label {
            float: right;
            padding: 0 10px;
        }
        .existingItem {
            display: flex;
            justify-content: space-between;
        }
    </style>
@endsection

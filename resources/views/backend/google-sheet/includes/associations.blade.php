<div>
    <div class="card-header row">
        @lang('Google sheet associations')
    </div>

    <div id="tableGoogleSheet">

        <button id="btnLoadGoogleSheet" class="btn btn-sm btn-primary float-right" type="submit">@lang('Load google sheets columns')</button>

        <table>
            <tr>
                <td>@lang('Column in database')</td>
                <td>@lang('Column in Google Sheet')</td>
            </tr>
            @foreach($fillable as $fill)
            <tr>
                <td>{{ $fill }}</td>
                <td class="columnGoogleSheet">
                    <select name="associations[{{$fill}}]"><option>-- not use --</option></select>
                </td>
            </tr>
            @endforeach
        </table>
    </div>

    <input type="hidden" name="import" value="0">
    <button class="btn btn-sm btn-primary float-right" type="submit" style="margin-left: 15px" id="saveAndImport" >@lang('Save and Import')</button>
    <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Save')</button>
</div>


<script>
    $(document).ready(function(){

        const _token = $('meta[name="csrf-token"]').attr('content')

        $(document).delegate('#btnLoadGoogleSheet', 'click', function(e) {
            e.preventDefault()

            const url = $('#url').val()

            $.ajax({
                url: '{{ route('admin.google-sheet.load') }}',
                type: 'post',
                data: {url, _token},
                dataType: 'json',
                beforeSend: function() {
                    $('#tableGoogleSheet').css('opacity', 0.5);
                },
                complete: function() {
                    $('#tableGoogleSheet').css('opacity', 1);
                },
                success: function(json) {
                    $('.alert-dismissible, .text-danger').remove();
                    $('.form-group').removeClass('has-error');

                    if (json['data']) {
                        options = '<option>-- not use --</option>'
                        json['data'].forEach((val, index) => {
                            options += '<option value="' + index + '">' + val + '</option>'
                        })
                        $('.columnGoogleSheet select').html(options)

                        setDefault()
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    if (xhr.responseJSON.message) {
                        alert(thrownError + '\n\r' + xhr.responseJSON.message);
                    } else {
                        alert(xhr.responseText);
                    }
                }
            });
        });

        function setDefault()
        {
            let associations = [];
            @if(isset($googleSheet) && $googleSheet->associations)
                associations = @json($googleSheet->associations);
            @endif

            for (let i in associations) {
                if ($('select[name="associations[' + associations[i].db_column + ']"]')
                && associations[i].gs_column !== '-- not use --') {
                    $('select[name="associations[' + associations[i].db_column + ']"] option[value=' + associations[i].gs_column + ']')
                        .attr('selected','selected')
                }
            }
        }

        if ($('#url').val()) {
            $('#btnLoadGoogleSheet').click()
        }

        $(document).delegate('#saveAndImport', 'click', function(e) {
            e.preventDefault()

            $('input[name="import"]').attr('value', 1)

            $('form').submit()
        });
    });
</script>

<style>
    td {
        padding: 8px 15px;
    }
    #tableGoogleSheet {
        transition: .3s;
        margin-top: 15px;
    }
</style>
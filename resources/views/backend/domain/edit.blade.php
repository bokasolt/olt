@extends('backend.layouts.app')

@section('title', __('Update Domain'))

@section('content')
    <x-forms.patch :action="route('admin.domain.update', $domain->id)">
        <x-backend.card>
            <x-slot name="header">
                @lang('Update Domain')
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.dashboard')" :text="__('Cancel')"/>
            </x-slot>

            <x-slot name="body">
                @if ($domain->ahrefs_error_message)
                <div class="form-group row">
                    <label class="col-md-2 col-form-label">@lang('Ahrefs error message')</label>

                    <div class="col-md-10">{{ $domain->ahrefs_error_message }}</div>
                </div><!--form-group-->
                @endif

                <div class="form-group row">
                    <label for="domain" class="col-md-2 col-form-label">@lang('Domain')</label>

                    <div class="col-md-10">
                        <input type="text" name="domain" id="domain" class="form-control" placeholder="{{ __('Domain') }}"
                               value="{{ old('domain') ?? $domain->domain }}" maxlength="255" required/>
                    </div>
                </div><!--form-group-->

                <div class="form-group row">
                    <label for="niche" class="col-md-2 col-form-label">@lang('NICHE')</label>

                    <div class="col-md-4">
                            <select name="niche" class="form-control" required x-on:change="niche = $event.target.value">
                                @foreach ($lists['niche'] as $k => $v)
                                <option value="{{ $k }}" {{ $k === (old('niche') ?? strtoupper($domain->niche)) ? 'selected' : '' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                    </div>
                </div><!--form-group-->

                <div class="form-group row">
                    <label for="lang" class="col-md-2 col-form-label">@lang('LANGUAGE')</label>

                    <div class="col-md-2">
                            <select name="lang" class="form-control" required x-on:change="lang = $event.target.value">
                                @foreach ($lists['lang'] as $k => $v)
                                <option value="{{ $k }}" {{ $k === (old('lang') ?? strtoupper($domain->lang)) ? 'selected' : '' }}>{{ $v }}</option>
                                @endforeach
                            </select>

                    </div>
                </div><!--form-group-->

                <div class="form-group row">
                    <label for="title" class="col-md-2 col-form-label">@lang('Title of homepage')</label>

                    <div class="col-md-10">
                        <input type="text" name="title" id="title" class="form-control"
                               placeholder="{{ __('Title of homepage') }}"
                               value="{{ old('title') ?? $domain->title }}" maxlength="1023"/>
                    </div>
                </div><!--form-group-->

                @if ($domain->ahrefs_sync_at)
                <div class="form-group row">
                    <label class="col-md-2 col-form-label">@lang('Ahrefs sync at')</label>

                    <div class="col-md-10">{{ $domain->ahrefs_sync_at }}</div>
                </div><!--form-group-->
                @endif

                <div class="form-group row">
                    <label for="ahrefs_dr" class="col-md-2 col-form-label">@lang('Ahrfes DR')</label>

                    <div class="col-md-10">
                        <input type="number" name="ahrefs_dr" id="ahrefs_dr" class="form-control"
                               placeholder="{{ __('Ahrfes DR') }}"
                               value="{{ old('ahrefs_dr') ?? $domain->ahrefs_dr }}" maxlength="32"/>
                    </div>
                </div><!--form-group-->

                <div class="form-group row">
                    <label for="ahrefs_traffic" class="col-md-2 col-form-label">@lang('Ahrefs Traffic')</label>

                    <div class="col-md-10">
                        <input type="number" name="ahrefs_traffic" id="ahrefs_traffic" class="form-control"
                               placeholder="{{ __('Ahrefs Traffic') }}"
                               value="{{ old('ahrefs_traffic') ?? $domain->ahrefs_traffic }}" maxlength="32"/>
                    </div>
                </div><!--form-group-->

                <div class="form-group row">
                    <label for="linked_domains" class="col-md-2 col-form-label">@lang('Linked domains')</label>

                    <div class="col-md-10">
                        <input type="number" name="linked_domains" id="linked_domains" class="form-control"
                               placeholder="{{ __('Linked domains') }}"
                               value="{{ old('linked_domains') ?? $domain->linked_domains }}" maxlength="32"/>
                    </div>
                </div><!--form-group-->

                <div class="form-group row">
                    <label for="ref_domains" class="col-md-2 col-form-label">@lang('Ref. domains')</label>

                    <div class="col-md-10">
                        <input type="number" name="ref_domains" id="ref_domains" class="form-control"
                               placeholder="{{ __('Ref. domains') }}"
                               value="{{ old('linked_domains') ?? $domain->ref_domains }}" maxlength="32"/>
                    </div>
                </div><!--form-group-->

                <div class="form-group row">
                    <label for="num_organic_keywords_top_10"
                           class="col-md-2 col-form-label">@lang('NUMBER of Organic Keywords TOP 10')</label>

                    <div class="col-md-10">
                        <input type="number" name="num_organic_keywords_top_10" id="num_organic_keywords_top_10"
                               class="form-control" placeholder="{{ __('NUMBER of Organic Keywords TOP 10') }}"
                               value="{{ old('num_organic_keywords_top_10') ?? $domain->num_organic_keywords_top_10 }}"
                               maxlength="32"/>
                    </div>
                </div><!--form-group-->

                <div class="form-group row">
                    <label for="article_by" class="col-md-2 col-form-label">@lang('Article provides by')</label>

                    <div class="col-md-4">
                            <select name="article_by" class="form-control" id="article_by" required x-on:change="article_by = $event.target.value">
                                @foreach ($lists['article_by'] as $k => $v)
                                <option value="{{ $k }}" {{ $k === (old('article_by') ?? strtoupper($domain->article_by)) ? 'selected' : '' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                    </div>
                </div><!--form-group-->

                <div class="form-group row">
                    <label for="price" class="col-md-2 col-form-label">@lang('Price (USD)')</label>

                    <div class="col-md-10">
                        <input type="number" name="price" id="price" class="form-control" step=".01" min="0"
                               placeholder="{{ __('Price (USD)') }}"
                               value="{{ old('price') ?? $domain->price }}" maxlength="32"/>
                    </div>
                </div><!--form-group-->

                <div class="form-group row">
                    <label for="sponsored_label_fld" class="col-md-2 col-form-label">@lang('Sponsored label')</label>

                    <div class="col-md-2">
                            <select name="sponsored_label" class="form-control" id="sponsored_label_fld" required x-on:change="sponsored_label = $event.target.value">
                                @foreach ($lists['sponsored_label'] as $k => $v)
                                <option value="{{ $k }}" {{ $k === (old('sponsored_label') ?? strtoupper($domain->sponsored_label)) ? 'selected' : '' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                    </div>
                </div><!--form-group-->


                <div class="form-group row">
                    <label for="type_of_publication"
                           class="col-md-2 col-form-label">@lang('Type of publication')</label>

                    <div class="col-md-4">
                            <select name="type" class="form-control" id="type_of_publication" required x-on:change="type_of_publication = $event.target.value">
                                @foreach ($lists['type_of_publication'] as $k => $v)
                                <option value="{{ $k }}" {{ $k === (old('type_of_publication') ?? strtoupper($domain->type_of_publication)) ? 'selected' : '' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                    </div>
                </div><!--form-group-->

                <div class="form-group row">
                    <label for="type_of_link" class="col-md-2 col-form-label">@lang('Type of link')</label>

                    <div class="col-md-10">
                            <select name="type_of_link" class="form-control" id="type_of_link" required x-on:change="type_of_link = $event.target.value">
                                @foreach ($lists['type_of_link'] as $k => $v)
                                <option value="{{ $k }}" {{ $k === (old('type_of_link') ?? strtoupper($domain->type_of_link)) ? 'selected' : '' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                    </div>
                </div><!--form-group-->

                <div class="form-group row">
                    <label for="contact_email" class="col-md-2 col-form-label">@lang('Contact email')</label>

                    <div class="col-md-10">
                        <input type="email" name="contact_email" id="contact_email" class="form-control"
                               placeholder="{{ __('Contact email') }}"
                               value="{{ old('contact_email') ?? $domain->contact_email }}"
                               maxlength="255"/>
                    </div>
                </div><!--form-group-->

                <div class="form-group row">
                    <label for="contact_form_link" class="col-md-2 col-form-label">@lang('Link to contact form')</label>

                    <div class="col-md-10">
                        <input type="text" name="contact_form_link" id="contact_form_link"
                               class="form-control"
                               placeholder="{{ __('Link to contact form') }}"
                               value="{{ old('contact_form_link') ?? $domain->contact_form_link }}"/>
                    </div>
                </div><!--form-group-->

                <div class="form-group row">
                    <label for="contact_name" class="col-md-2 col-form-label">@lang('Contact name')</label>

                    <div class="col-md-10">
                        <input type="text" name="contact_name" id="contact_name" class="form-control"
                               placeholder="{{ __('Contact name') }}"
                               value="{{ old('contact_name') ?? $domain->contact_name }}"
                               maxlength="255"/>
                    </div>
                </div><!--form-group-->

                <div class="form-group row">
                    <label for="additional_notes" class="col-md-2 col-form-label">@lang('Additional notes')</label>

                    <div class="col-md-10">
                        <textarea name="additional_notes" id="additional_notes" class="form-control"
                               placeholder="{{ __('Additional notes') }}"
                        >{{ old('additional_notes') ?? $domain->additional_notes }}</textarea>
                    </div>
                </div><!--form-group-->

            </x-slot>

            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Update Domain')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.patch>
@endsection

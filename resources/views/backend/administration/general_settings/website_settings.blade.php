@extends('layouts.app')

@section('content')
<div class="row">
    @php $settings = \App\Models\Setting::all(); @endphp

    <div class="col-lg-9">
        <div class="card">

            <div class="card-header">
                <h4 class="header-title">{{ _lang('Website Settings') }}</h4>
            </div>

            <div class="card-body">
                <form method="post" class="settings-submit params-panel" autocomplete="off"
                    action="{{ route('settings.website_settings','store') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Main Heading') }}</label>
                                <input type="text" class="form-control" name="main_heading"
                                    value="{{ get_setting($settings, 'main_heading') }}" required>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Banner') }}</label>
                                <input type="file" class="dropify" name="home_banner" data-default-file="{{ get_setting($settings, 'home_banner') != '' ? asset('/uploads/media/'.get_setting($settings, 'home_banner')) : '' }}""  data-allowed-file-extensions="png jpg">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Footer About Us') }}</label>
                                <textarea class="form-control"
                                    name="about_us">{{ get_setting($settings, 'about_us') }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Copyright Text') }}</label>
                                <input type="text" class="form-control" name="copyright"
                                    value="{{ get_setting($settings, 'copyright') }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Primary Color') }}</label>
                                <input type="text" class="form-control" name="primary_color"
                                    value="{{ get_setting($settings, 'primary_color','#68a728') }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Sidebar Active Color') }}</label>
                                <input type="text" class="form-control" name="sidebar_active_color"
                                    value="{{ get_setting($settings, 'sidebar_active_color','#dbfbba') }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Footer Top Color') }}</label>
                                <input type="text" class="form-control" name="footer_top_color"
                                    value="{{ get_setting($settings, 'footer_top_color','#2f3640') }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Footer Bottom Color') }}</label>
                                <input type="text" class="form-control" name="footer_bottom_color"
                                    value="{{ get_setting($settings, 'footer_bottom_color','#26282b') }}">
                            </div>
                        </div>

                        <input type="hidden" name="footer_important_links" value="">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Footer Important Links') }}</label>
                                <select class="form-control auto-multiple-select select2" data-selected="{{ get_setting($settings, 'footer_important_links') }}" name="footer_important_links[]" multiple>
                                    @foreach(\App\Models\Page::where('status', 1)->get() as $page)
                                        <option value="{{ $page->id }}">{{ $page->translation->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <input type="hidden" name="footer_popular_category" value="">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Footer Popular Category') }}</label>
                                <select class="form-control auto-multiple-select select2"  data-selected="{{ get_setting($settings, 'footer_popular_category') }}" name="footer_popular_category[]" multiple>
                                    {{ buildOptionTree(App\Models\Category::all(), 0) }}
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Meta Keywords') }}</label>
                                <input type="text" class="form-control" name="meta_keywords"
                                    value="{{ get_setting($settings, 'meta_keywords') }}">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Meta Content') }}</label>
                                <textarea class="form-control"
                                    name="meta_content">{{ get_setting($settings, 'meta_content') }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg"><i
                                        class="icofont-check-circled"></i> {{ _lang('Save Settings') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <form method="post" class="validate" autocomplete="off" action="{{ action('OfferController@update', $id) }}" enctype="multipart/form-data">
            @csrf
            <input name="_method" type="hidden" value="PATCH">
            
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="header-title">{{ _lang('Update Offer') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Name') }}</label>
                                        <input type="text" class="form-control" name="trans[name]"
                                            value="{{ $product->translation->name }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Unit') }}</label>
                                        <select class="form-control auto-select select2"
                                            data-selected="{{ $product->unit_id }}" name="unit_id" required>
                                            <option value="">{{ _lang('Select One') }}</option>
                                            @foreach(App\Models\Unit::all() as $unit)
                                                <option value="{{ $unit->id }}">{{ $unit->translation->name }} ({{ $unit->translation->short_name }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Unit Number') }}</label>
                                        <input type="number" class="form-control" name="unit_number"
                                            value="{{ $product->unit_number }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Price').' '.currency() }}</label>
                                        <input type="text" class="form-control float-field" name="price"
                                            value="{{ $product->price }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Special Price').' '.currency() }}</label>
                                        <input type="text" class="form-control float-field" name="special_price"
                                            value="{{ $product->special_price }}">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Description') }}</label>
                                        <textarea class="form-control summernote" name="trans[description]">{{ $product->translation->description }}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Banner') }} 400px x 300px</label>
                                        <input type="file" class="form-control dropify" name="banner" data-default-file="{{ asset('uploads/media/'.$product->banner) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="header-title">{{ _lang('Other Details') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Category') }}</label>
                                        <select class="form-control auto-select select2"
                                            data-selected="{{ $product->category_id }}" name="category_id" required>
                                            <option value="">{{ _lang('Select One') }}</option>
                                            {{ buildOptionTree(App\Models\Category::all(), 0) }}
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('In Stock') }}</label>
                                        <select class="form-control auto-select" data-selected="{{ $product->in_stock }}"
                                            name="in_stock" required>
                                            <option value="1">{{ _lang('In Stock') }}</option>
                                            <option value="0">{{ _lang('Out Of Stock') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Is Active') }}</label>
                                        <select class="form-control auto-select" data-selected="{{ $product->is_active }}"
                                            name="is_active">
                                            <option value="1">{{ _lang('Active') }}</option>
                                            <option value="0">{{ _lang('Inactive') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Thumbnail') }} 400px x 400px</label>
                                        <input type="file" class="form-control dropify" name="thumbnail" data-default-file="{{ asset('uploads/media/'.$product->thumbnail) }}">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-lg btn-block mt-3"><i
                                                class="icofont-check-circled"></i>
                                            {{ _lang('Save Changes') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
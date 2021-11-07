<form method="post" class="ajax-submit" autocomplete="off" action="{{ action('CouponController@update', $id) }}" enctype="multipart/form-data">
    @csrf
    <input name="_method" type="hidden" value="PATCH">				
    <div class="row px-2">
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">{{ _lang('Name') }}</label>						
                <input type="text" class="form-control" name="trans[name]" value="{{ $coupon->translation->name }}" required>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">{{ _lang('Code') }}</label>						
                <input type="text" class="form-control" name="code" value="{{ $coupon->code }}" required>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">{{ _lang('Discount Type') }}</label>						
                <select class="form-control auto-select" data-selected="{{ $coupon->is_percent }}" name="is_percent" required>
                    <option value="0">{{ _lang('Fixed') }}</option>
                    <option value="1">{{ _lang('Percent') }}</option>
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">{{ _lang('Value') }}</label>						
                <input type="text" class="form-control" name="value" value="{{ $coupon->value }}" required>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">{{ _lang('Status') }}</label>						
                <select class="form-control auto-select" data-selected="{{ $coupon->is_active }}" name="is_active" required>
                    <option value="1">{{ _lang('Active') }}</option>
                    <option value="0">{{ _lang('InActive') }}</option>
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">{{ _lang('Start Date') }}</label>						
                <input type="text" class="form-control datepicker" name="start_date" value="{{ $coupon->start_date }}" required>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">{{ _lang('End Date') }}</label>						
                <input type="text" class="form-control datepicker" name="end_date" value="{{ $coupon->end_date }}" required>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">{{ _lang('Products') }}</label>						
                <select class="form-control" name="products[]" id="products" multiple>
                    @foreach(\App\Models\Product::whereIn('id', $coupon->products->pluck('id'))->get() as $product)
                        <option value="{{ $product->id }}" selected>{{ $product->translation->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">{{ _lang('Categories') }}</label>						
                <select class="form-control" name="categories[]" id="categories" multiple>
                    @foreach(\App\Models\Category::whereIn('id', $coupon->categories->pluck('id'))->get() as $category)
                        <option value="{{ $category->id }}" selected>{{ $category->translation->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-lg mt-4"><i class="icofont-check-circled"></i> {{ _lang('Update') }}</button>
            </div>
        </div>	
    </div>	
</form>

<script>
$('#products').select2({
    minimumInputLength: 2,
    ajax: {
        url: function (params) {
            return _url + '/products/search_products';
        },
        processResults: function (data) {
            var data = $.map(data, function (obj) {
                obj.text = obj.text || obj.name; 
                return obj;
            });

            return {
                results: data
            };
        }
    }
});

$('#categories').select2({
    minimumInputLength: 2,
    ajax: {
        url: function (params) {
            return _url + '/categories/search_categories';
        },
        processResults: function (data) {
            var data = $.map(data, function (obj) {
                obj.text = obj.text || obj.name; 
                return obj;
            });

            return {
                results: data
            };
        }
    }
});

</script>
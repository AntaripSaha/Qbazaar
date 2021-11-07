<form method="post" class="ajax-submit" autocomplete="off" action="{{ route('coupons.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="row px-2">
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">{{ _lang('Name') }}</label>						
                <input type="text" class="form-control" name="trans[name]" value="{{ old('trans.name') }}" required>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">{{ _lang('Code') }}</label>						
                <input type="text" class="form-control" name="code" value="{{ old('code') }}" required>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">{{ _lang('Discount Type') }}</label>						
                <select class="form-control auto-select" data-selected="{{ old('is_percent',1) }}" name="is_percent" required>
                    <option value="0">{{ _lang('Fixed') }}</option>
                    <option value="1">{{ _lang('Percent') }}</option>
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">{{ _lang('Value') }}</label>						
                <input type="text" class="form-control" name="value" value="{{ old('value') }}" required>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">{{ _lang('Status') }}</label>						
                <select class="form-control auto-select" data-selected="{{ old('is_active',1) }}" name="is_active" required>
                    <option value="1">{{ _lang('Active') }}</option>
                    <option value="0">{{ _lang('InActive') }}</option>
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">{{ _lang('Start Date') }}</label>						
                <input type="text" class="form-control datepicker" name="start_date" value="{{ old('start_date') }}" required>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">{{ _lang('End Date') }}</label>						
                <input type="text" class="form-control datepicker" name="end_date" value="{{ old('end_date') }}" required>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">{{ _lang('Products') }}</label>						
                <select class="form-control" name="products[]" id="products" multiple>
                </select>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">{{ _lang('Categories') }}</label>						
                <select class="form-control" name="categories[]" id="categories" multiple>
                </select>
            </div>
        </div>
            
        <div class="col-md-12">
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-lg mt-4"><i class="icofont-check-circled"></i> {{ _lang('Save Coupon') }}</button>
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
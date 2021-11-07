<form method="post" class="ajax-screen-submit" autocomplete="off" action="{{ route('units.store') }}" enctype="multipart/form-data">
	{{ csrf_field() }}
	<div class="col-md-12">
		<div class="form-group">
			<label class="control-label">{{ _lang('Name') }}</label>						
			<input type="text" class="form-control" name="trans[name]" value="{{ old('trans.name') }}" required>
		</div>
	</div>

	<div class="col-md-12">
		<div class="form-group">
			<label class="control-label">{{ _lang('Short Name') }}</label>						
			<input type="text" class="form-control" name="trans[short_name]" value="{{ old('trans.short_name') }}" required>
		</div>
	</div>
    
	<div class="col-md-12">
	    <div class="form-group">
		    <button type="submit" class="btn btn-primary btn-lg"><i class="icofont-check-circled"></i> {{ _lang('Save') }}</button>
	    </div>
	</div>
</form>

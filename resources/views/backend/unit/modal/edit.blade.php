<form method="post" class="ajax-screen-submit" autocomplete="off" action="{{ action('UnitController@update', $id) }}" enctype="multipart/form-data">
	{{ csrf_field()}}
	<input name="_method" type="hidden" value="PATCH">				
	<div class="col-md-12">
		<div class="form-group">
			<label class="control-label">{{ _lang('Name') }}</label>						
			<input type="text" class="form-control" name="trans[name]" value="{{ $unit->translation->name }}" required>
		</div>
	</div>

	<div class="col-md-12">
		<div class="form-group">
			<label class="control-label">{{ _lang('Short Name') }}</label>						
			<input type="text" class="form-control" name="trans[short_name]" value="{{ $unit->translation->short_name }}" required>
		</div>
	</div>
	
	<div class="form-group">
	    <div class="col-md-12">
		    <button type="submit" class="btn btn-primary btn-lg"><i class="icofont-check-circled"></i> {{ _lang('Update') }}</button>
	    </div>
	</div>
</form>


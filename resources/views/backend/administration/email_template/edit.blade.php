@extends('layouts.app')

@section('content')
<h4 class="page-title">{{ _lang('Update Email Template') }}</h4>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<form method="post" class="validate" autocomplete="off" action="{{ action('EmailTemplateController@update', $id) }}" enctype="multipart/form-data">
					@csrf
					<input name="_method" type="hidden" value="PATCH">

					<div class="col-md-12">
					 <div class="form-group">
						<label class="control-label">{{ _lang('Name') }}</label>
						<input type="text" class="form-control" name="name" value="{{ $emailtemplate->name }}" readOnly="true">
					 </div>
					</div>

					<div class="col-md-12">
						<div class="form-group border border-primary p-3">
							<pre>{{ $vars[$emailtemplate->name] }}</pre>
						</div>
					</div>

					<div class="col-md-12">
					 <div class="form-group">
						<label class="control-label">{{ _lang('Subject') }}</label>
						<input type="text" class="form-control" name="subject" value="{{ $emailtemplate->subject }}" required>
					 </div>
					</div>

					<div class="col-md-12">
					 <div class="form-group">
						<label class="control-label">{{ _lang('Body') }}</label>
						<textarea class="form-control summernote" name="body" required>{{ $emailtemplate->body }}</textarea>
					 </div>
					</div>


					<div class="form-group">
					  <div class="col-md-12">
						<button type="submit" class="btn btn-primary btn-lg"><i class="icofont-check-circled"></i> {{ _lang('Update') }}</button>
					  </div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection



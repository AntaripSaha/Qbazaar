@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-lg-12">
		<div class="card">
		    <div class="card-header">
				<span class="header-title">{{ _lang('Category Details') }}</span>
			</div>
			
			<div class="card-body">
			    <table class="table table-bordered">
					@if($category->image != '')
					<tr>
						<td colspan="2" class="text-center"><img src="{{ asset('uploads/media/'.$category->image) }}" class="table-banner"></td>
					</tr>
					@endif
				    <tr><td>{{ _lang('Name') }}</td><td>{{ $category->translation->name }}</td></tr>
				    <tr><td>{{ _lang('Description') }}</td><td>{{ $category->translation->description }}</td></tr>
				    <tr><td>{{ _lang('Icon') }}</td><td>{{ $category->icon }}</td></tr>
					<tr><td>{{ _lang('Parent Category') }}</td><td>{{ $category->parent_category->translation->name }}</td></tr>
			    </table>
			</div>
	    </div>
	</div>
</div>
@endsection
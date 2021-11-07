@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header d-flex align-items-center">
				<h4 class="header-title">{{ _lang('All Categories') }}</h4>
				@if(has_permission('categories.create'))
				<a class="btn btn-primary btn-sm ml-auto" href="{{ route('categories.create') }}"><i class="icofont-plus-circle"></i> {{ _lang('Add New') }}</a>
				@endif
			</div>
			<div class="card-body">
				<table id="categories_table" class="table table-bordered data-table">
					<thead>
					    <tr>
							<th>{{ _lang('Thumbnail') }}</th>
						    <th>{{ _lang('Name') }}</th>
							<th>{{ _lang('Description') }}</th>
							<th>{{ _lang('Parent') }}</th>
							<th class="text-center">{{ _lang('Status') }}</th>
							<th class="text-center">{{ _lang('Action') }}</th>
					    </tr>
					</thead>
					<tbody>
					    @foreach($categorys as $category)
					    <tr data-id="row_{{ $category->id }}">
							<td class='image'><img src="{{ asset('uploads/media/'.$category->image) }}" class="thumb-md img-thumbnail"></td>
							<td class='name'>{{ $category->translation->name }}</td>
							<td class='description'>{{ $category->translation->description }}</td>
							<td class='parent_category'>{{ $category->parent_category->translation->name }}</td>
							<td class='is_active text-center'>{!! $category->is_active ? xss_clean(status(_lang('Active'))) : xss_clean(status(_lang('InActive','danger'))) !!}</td>

							<td class="text-center">
								<span class="dropdown">
								  <button class="btn btn-primary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								  {{ _lang('Action') }}
								  </button>
								  <form action="{{ action('CategoryController@destroy', $category['id']) }}" method="post">
									{{ csrf_field() }}
									<input name="_method" type="hidden" value="DELETE">

									<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
										<a href="{{ action('CategoryController@edit', $category['id']) }}" class="dropdown-item dropdown-edit dropdown-edit"><i class="icofont-edit-alt"></i> {{ _lang('Edit') }}</a>
										<a href="{{ action('CategoryController@show', $category['id']) }}" class="dropdown-item dropdown-view dropdown-view"><i class="icofont-eye-alt"></i> {{ _lang('View') }}</a>
										<button class="btn-remove dropdown-item" type="submit"><i class="icofont-ui-delete"></i> {{ _lang('Delete') }}</button>
									</div>
								  </form>
								</span>
							</td>
					    </tr>
					    @endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endsection
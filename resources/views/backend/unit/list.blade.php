@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<div class="card no-export">
		    <div class="card-header">
				<span class="panel-title">{{ _lang('Product Units') }}</span>
				@if(has_permission('units.create'))
				<a class="btn btn-primary btn-sm float-right ajax-modal" data-title="{{ _lang('Add Product Unit') }}" href="{{ route('units.create') }}"><i class="icofont-plus-circle"></i> {{ _lang('Add New') }}</a>
				@endif
			</div>
			<div class="card-body">
				<table id="units_table" class="table data-table">
					<thead>
					    <tr>
						    <th>{{ _lang('Name') }}</th>
						    <th>{{ _lang('Short Name') }}</th>
						    <th class="text-center">{{ _lang('Action') }}</th>
					    </tr>
					</thead>
					<tbody>
					    @foreach($units as $unit)
					    <tr data-id="row_{{ $unit->id }}">
							<td class="name">{{ $unit->translation->name }}</td>
							<td class="short_name">{{ $unit->translation->short_name }}</td>
							<td class="text-center">
								<span class="dropdown">
								  <button class="btn btn-primary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								  {{ _lang('Action') }}
								  <i class="fas fa-angle-down"></i>
								  </button>
								  <form action="{{ action('UnitController@destroy', $unit['id']) }}" method="post">
									{{ csrf_field() }}
									<input name="_method" type="hidden" value="DELETE">

									<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
										<a href="{{ action('UnitController@edit', $unit['id']) }}" data-title="{{ _lang('Update Unit') }}" class="dropdown-item dropdown-edit ajax-modal"><i class="icofont-edit-alt"></i> {{ _lang('Edit') }}</a>
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
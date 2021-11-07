@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header d-flex align-items-center">
				<h4 class="header-title">{{ _lang('All Coupons') }}</h4>
				@if(has_permission('coupons.create'))
				<a class="btn btn-primary btn-sm ml-auto ajax-modal" data-title="{{ _lang('Add New Coupon') }}" href="{{ route('coupons.create') }}"><i class="icofont-plus-circle"></i> {{ _lang('Add New') }}</a>
				@endif
			</div>
			<div class="card-body">
				<table id="coupons_table" class="table table-bordered data-table">
					<thead>
					    <tr>
						    <th>{{ _lang('Name') }}</th>
						    <th>{{ _lang('Code') }}</th>
							<th>{{ _lang('Discount') }}</th>
							<th>{{ _lang('Status') }}</th>
							<th class="text-center">{{ _lang('Action') }}</th>
					    </tr>
					</thead>
					<tbody>
					    @foreach($coupons as $coupon)
					    <tr data-id="row_{{ $coupon->id }}">
							<td class='code'>{{ $coupon->translation->name }}</td>
							<td class='code'>{{ $coupon->code }}</td>
							<td class='value'>
								@if($coupon->is_percent)
								{{ $coupon->value.' %' }}
								@else
								{{ $coupon->value }}
								@endif
							</td>
							<td class='is_active'>{!! $coupon->is_active ? xss_clean(status(_lang('Active'))) : xss_clean(status(_lang('Inactive'), 'danger')) !!}</td>

							<td class="text-center">
								<span class="dropdown">
								  <button class="btn btn-primary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								  {{ _lang('Action') }}
								  <i class="fas fa-angle-down"></i>
								  </button>
								  <form action="{{ action('CouponController@destroy', $coupon['id']) }}" method="post">
									{{ csrf_field() }}
									<input name="_method" type="hidden" value="DELETE">

									<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
										<a href="{{ action('CouponController@edit', $coupon['id']) }}" data-title="{{ _lang('Update Coupon') }}" class="dropdown-item dropdown-edit dropdown-edit ajax-modal"><i class="icofont-edit-alt"></i> {{ _lang('Edit') }}</a>
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
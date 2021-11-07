<form method="post" class="assign-delivery-boy" autocomplete="off" action="{{ route('orders.assign_delivery_boy', $order->id) }}">
	{{ csrf_field() }}

	<div class="row p-2">

		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Delivery Boy') }}</label>
				<select class="form-control select2" name="delivery_boy_id" required>
					<option value="">{{ _lang('Select Delivery Boy') }}</option>
					{{ create_option('users','id','name',old('delivery_boy_id', $order->delivery_boy_id), array('user_type=' => 'delivery_boy')) }}
				</select>
			</div>
		</div>

        <div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Delivery Status') }}</label>
				<select class="form-control auto-select" data-selected="{{ $order->delivery_status }}" name="delivery_status" required>
                    <option value="processing">{{ _lang('Processing') }}</option>
                    <option value="pending">{{ _lang('Pending') }}</option>
                    <option value="completed">{{ _lang('Completed') }}</option>
				</select>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<button type="submit" class="btn btn-primary btn-lg"><i class="icofont-check-circled"></i> {{ _lang('Confirm') }}</button>
			</div>
		</div>
	</div>
</form>

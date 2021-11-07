@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-lg-9">
		<div class="card">
			<div class="card-header">
				<span class="panel-title">{{ _lang('View Order Details') }}</span>
			</div>

			<div class="card-body">

				<div class="receipt-content">
					<div class="row">
						<div class="col-md-12">
							<div class="invoice-wrapper">

								@if($order->payment_status == $order::PAID)
								<div class="payment-info">
									<div class="row">
										<div class="col-sm-6">
											<span>{{ _lang('Order ID') }}: <b>{{ $order->id }}</b></span><br>
											<span>{{ _lang('Order Date') }}: <b>{{ $order->created_at }}</b></span><br>
											<span>{{ _lang('Delivery Status') }}: <b>{!! xss_clean($order->getDeliveryStatus()) !!}</b></span><br>
											<span>{{ _lang('Delivery Type') }}: <b>{{ strtoupper($order->delivery_type) }}</b></span><br>
											<span>{{ _lang('Delivery Time') }}: <b>{{ $order->delivery_time }}</b></span><br>
										</div>
										<div class="col-sm-6 text-right">
											<span>{{ _lang('Payment Method') }}</span>
											<strong>{{ strtoupper(str_replace('_',' ',$order->payment_method)) }}</strong>

											<!-- <span><u>{{ _lang('Transaction ID') }}</u></span>
											<strong>{{ $order->transaction->transaction_id }}</strong>

											<span>{{ _lang('Payment Date') }}</span>
											<strong>{{ $order->transaction->created_at }}</strong> -->
										</div>
									</div>
								</div>
								@else
								<div class="payment-info">
									<div class="row">
										<div class="col-sm-6">
											<span>{{ _lang('Order ID') }}: <b>{{ $order->id }}</b></span><br>
											<span>{{ _lang('Order Date') }}: <b>{{ $order->created_at }}</b></span><br>
											<span>{{ _lang('Delivery Status') }}: <b>{!! xss_clean($order->getDeliveryStatus()) !!}</b></span><br>
											<span>{{ _lang('Delivery Type') }}: <b>{{ strtoupper($order->delivery_type) }}</b></span><br>
											<span>{{ _lang('Delivery Time') }}: <b>{{ $order->delivery_time }}</b></span><br>
										</div>
										<div class="col-sm-6 text-right">
											<span>{{ _lang('Payment Method') }}</span>
											<strong>{{ strtoupper(str_replace('_',' ',$order->payment_method)) }}</strong>

											<span>{{ _lang('Payment Status') }}</span>
											<strong>{!! xss_clean($order->getPaymentStatus()) !!}</strong>
										</div>
									</div>
								</div>
								@endif

								<div class="payment-details">
									<div class="row">
										<div class="col-sm-4">
											<span>{{ _lang('Billing Details') }}</span>
											<strong>
												{{ $order->customer_name }}
											</strong>
											<p>
												{{ $order->customer_email }}<br>
												{{ $order->customer_phone }}<br>
											</p>
										</div>

										<div class="col-sm-4">
											<span>{{ _lang('Shipping Address') }}</span>
											<p>
												{{ $order->shipping_address }}<br>
											</p>
										</div>

										<div class="col-sm-4 text-right">
											<span>{{ _lang('Payment To') }}</span>
											<strong>
												{{ get_option('company_name') }}
											</strong>
											<p>
												{{ get_option('email') }}<br>
												{{ get_option('phone') }}<br>
												{!! xss_clean(get_option('address')) !!} <br>
											</p>
										</div>
									</div>
								</div>

								<div class="line-items">
									<div class="headers clearfix">
										<div class="row">
											<div class="col-6 col-md-4">{{ _lang('Description') }}</div>
											<div class="col-6 col-md-3  text-right">{{ _lang('Unit Price') }}</div>
											<div class="col-6 col-md-2">{{ _lang('Quantity') }}</div>
											<div class="col-6 col-md-3 text-right">{{ _lang('Amount') }}</div>
										</div>
									</div>
									<div class="items">

										@foreach($order->products as $product)
										<div class="row item">
											<div class="col-md-4 desc">
												{{ $product->product->translation->name }}
											</div>
											<div class="col-md-3 unit_price">
												{!! xss_clean(decimalPlace($product->unit_price, currency($order->currency))) !!}
											</div>
											<div class="col-md-2 qty">
												{{ $product->qty }}
											</div>
											<div class="col-md-3 amount">
												{!! xss_clean(decimalPlace($product->line_total, currency($order->currency))) !!}
											</div>
										</div>
										@endforeach

									</div>
									<div class="total text-right">
										@if($order->note != '')
										<p class="extra-notes">
											<strong>{{ _lang('Extra Notes') }}</strong>
											{{ $order->note }}
										</p>
										@endif
										<div class="field">
											{{ _lang('Sub Total') }} <span>{!! xss_clean(decimalPlace($order->sub_total, currency($order->currency))) !!}</span>
										</div>
										<div class="field">
											{{ _lang('Shipping') }} <span>+ {!! xss_clean(decimalPlace($order->shipping_cost, currency($order->currency))) !!}</span>
										</div>

										<div class="field">
											{{ _lang('Discount') }} <span>- {!! xss_clean(decimalPlace($order->discount, currency($order->currency))) !!}</span>
										</div>

										<div class="field grand-total">
											{{ _lang('Total') }} <span>{!! xss_clean(decimalPlace($order->total, currency($order->currency))) !!}</span>
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>

	<div class="col-lg-3">
		<div class="card">
			<div class="card-header">
				<span class="panel-title">{{ _lang('Order Actions') }}</span>
			</div>
			<div class="card-body">
				<form action="{{ action('OrderController@update', $order->id) }}" method="post" id="order-update-form">

					@csrf
					<input name="_method" type="hidden" value="PATCH">

					<div class="col-md-12">
						<div class="form-group">
							<label class="control-label">{{ _lang('Delivery Status') }}</label>
							<select class="form-control auto-select" data-selected="{{ $order->delivery_status }}" name="delivery_status" required>
								<option value="pending">{{ _lang('Pending') }}</option>
								<option value="completed">{{ _lang('Completed') }}</option>
								<option value="processing">{{ _lang('Processing') }}</option>
								<option value="canceled">{{ _lang('Canceled') }}</option>
							</select>
						</div>
					</div>




					<div class="col-md-12">
						<div class="form-group">
							<label class="control-label">{{ _lang('Payment Status') }}</label>
							<select class="form-control auto-select" data-selected="{{ $order->payment_status }}" name="payment_status" id="payment_status">
								<option value="pending">{{ _lang('Pending') }}</option>
								<option value="paid">{{ _lang('Paid') }}</option>
								<option value="refunded">{{ _lang('Refunded') }}</option>
							</select>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<label class="control-label">{{ _lang('Payment Method') }}</label>
							<select class="form-control auto-select" data-selected="{{ $order->payment_method }}" name="payment_method">
								@if($order->payment_method == '')
								<option value="">{{ _lang('Select Method') }}</option>
								<option value="Cash_On_Deliver">{{ _lang('Cash On Deliver') }}</option>
								@else
								<option value="{{ $order->payment_method }}">{{ ucwords(str_replace('_',' ',$order->payment_method)) }}</option>
								@endif
							</select>
						</div>
					</div>

					<!-- @if($order->payment_status == $order::PENDING)
					    <div class="col-md-12 d-none transaction_id">
					        <div class="form-group">
						        <label class="control-label">{{ _lang('Transaction ID') }}</label>
						        <input type="text" name="transaction_id" class="form-control">
							</div>
					    </div>
				    @endif -->

					<div class="col-md-12">
						<div class="form-group">
							<button type="submit" class="btn btn-warning btn-block">{{ _lang('Update Order') }}</button>
						</div>
					</div>

				</form>
			</div>
		</div>
	</div>
</div>
@endsection
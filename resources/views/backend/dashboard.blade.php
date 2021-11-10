@extends('layouts.app')

@section('content')
@php $permissions = permission_list(); @endphp
@php $user_type = Auth::user()->user_type; @endphp

<link rel="stylesheet" href="{{ asset('backend/plugins/chartJs/Chart.min.css') }}">
<!-- <div id="google_translate_element"> Translate   </div> -->

<div class="row">
    @if (in_array('dashboard.order_processing_widget',$permissions) || $user_type == 'admin')
    <div class="col-xl-3 col-md-6">
        <div class="card mb-4 mini-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <h5>{{ _lang('ORDERS IN PROCESSING') }}</h5>
                    <i class="icofont-automation ml-auto text-success"></i>
                </div>
                <h4>{{ $processing_orders }}</h4>
            </div>
        </div>
    </div>
    @endif

    @if (in_array('dashboard.pending_payments_widget',$permissions) || $user_type == 'admin')
    <div class="col-xl-3 col-md-6">
        <div class="card mb-4 mini-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <h5>{{ _lang('PENDING ORDERS') }}</h5>
                    <i class="icofont-hour-glass ml-auto text-danger"></i>
                </div>
                <h4>{{ $pending_orders }}</h4>
            </div>
        </div>
    </div>
    @endif

    @if (in_array('dashboard.current_day_sales_widget',$permissions) || $user_type == 'admin')
    <div class="col-xl-3 col-md-6">
        <div class="card mb-4 mini-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <h5>{{ _lang('CURRENT DAY SALES') }}</h5>
                    <i class="icofont-money-bag ml-auto text-primary"></i>
                </div>
                <h4>{{ decimalPlace($current_day_sales, currency()) }}</h4>
            </div>
        </div>
    </div>
    @endif

    @if (in_array('dashboard.total_sales_widget',$permissions) || $user_type == 'admin')
    <div class="col-xl-3 col-md-6">
        <div class="card mb-4 mini-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <h5>{{ _lang('TOTAL SALES') }}</h5>
                    <i class="icofont-money ml-auto text-info"></i>
                </div>
                <h4>{{ decimalPlace($total_sales, currency()) }}</h4>
            </div>
        </div>
    </div>
    @endif

</div>

@if (in_array('dashboard.month_wise_sales_widget',$permissions) || $user_type == 'admin')
<div class="row">
    <div class="col-xl-12">
        <div class="card mb-4">
            <div class="card-header">
                {{ _lang('Month Wise Sales & Order Summary') }}
            </div>
            <div class="card-body"><canvas id="month_wise_sales" width="100%" height="40"></canvas></div>
        </div>
    </div>
</div>
@endif

@if (in_array('dashboard.recent_order_widget',$permissions) || $user_type == 'admin')
<div class="card mb-4">
    <div class="card-header">
        {{ _lang('Recent Orders') }}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>{{ _lang('Order ID') }}</th>
                        <th>{{ _lang('Customer Name') }}</th>
                        <th>{{ _lang('Email') }}</th>
                        <th>{{ _lang('Phone') }}</th>
                        <th>{{ _lang('Delivery') }}</th>
                        <th>{{ _lang('Payment') }}</th>
                        <th>{{ _lang('Grand Total') }}</th>
                        <th class="text-center">{{ _lang('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recent_orders as $recent_order)
                    <tr>
                        <td>{{ $recent_order->id }}</td>
                        <td>{{ $recent_order->customer_name }}</td>
                        <td>{{ $recent_order->customer_email }}</td>
                        <td>{{ $recent_order->customer_phone }}</td>
                        <td>{!! xss_clean($recent_order->getDeliveryStatus()) !!}</td>
                        <td>{!! xss_clean($recent_order->getPaymentStatus()) !!}</td>
                        <td>{!! xss_clean(decimalPlace($recent_order->total, $recent_order->currency)) !!}</td>
                        <td class="text-center">
                            <div class="dropdown">
                                <button class="btn btn-light dropdown-toggle" type="button" data-toggle="dropdown">{{ _lang('Action') }}</button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ action('OrderController@show', $recent_order->id) }}"><i class="icofont-eye-alt"></i> {{ _lang('View') }}</a>
                                    <form action="{{ action('OrderController@destroy', $recent_order['id']) }}" method="post">
                                        @csrf
                                        <input name="_method" type="hidden" value="DELETE">
                                        <button class="button-link btn-remove" type="submit"><i class="icofont-ui-delete"></i> {{ _lang('Delete') }}</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@endsection

@section('js-script')

<script src="{{ asset('backend/plugins/chartJs/Chart.min.js') }}" crossorigin="anonymous"></script>
<script src="{{ asset('backend/assets/js/dashboard.js') }}"></script>
@endsection
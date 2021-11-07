@extends('layouts.app')

@section('content')

<style>
    #abc {
        position: absolute;
        right: 50px;
        top: 155.8px;
    }
</style>

<div class="row">
    <div class="col-lg-12">
        <div class="card no-export">
            <div class="card-header">
                <span class="panel-title">Total Product</span>
            </div>
            <div class="card-header">
                @if($order_products_date !== NULL)
                <h6>Total Products in Present Date:
                    {{ $order_products_date->created_at->format('d-M-y') }}
                </h6>
                @elseif($order_products_date == NULL)
                <h6>Total Products in Present Date: No Available Data!
                </h6>
                @endif

            </div>

            <div class="card-header">
                <form action="{{route('order.product.search')}}">
                    @csrf
                    <label for=" date">From:</label>
                    <input type="date" name="from_date">

                    <label for="date ">To:</label>
                    <input type="date" name="to_date">

                    <button type="submit" class="btn btn-info">Search</button>
                </form>
            </div>
            <div id="abc">
                <form action="{{route('order.product.download')}}">
                    @csrf
                    <button type="submit" class="btn btn-outline-success">Download PDF</button>
                </form>
            </div>
            <!-- All Products List Start -->

            <div class="card-body">
                <table id="orders_table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Unit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($product_names as $key => $product_name)
                        <tr>
                            <th>{{$key+1}}</th>
                            <th>{{$product_name->name}}</th>
                            <th>{{$total_products[$key]}}</th>
                            <th>{{$unit_translation[$key]['name']}}</th>
                        </tr>
                        @empty
                        <tr>
                            <td> No Data Found! </td>
                        </tr>

                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- All Products List Start -->

        </div>
    </div>
</div>


@endsection
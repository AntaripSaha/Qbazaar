<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="{{ public_path('frontend/css/bootstrap-3.css') }}">
    <link rel="stylesheet" href="{{ public_path('frontend/css/billing.css') }}">
    <style>
        .topbar-information,
        .invoice-to {
            display: block;
            width: 100%;
        }

        .topbar-information .col-md-3 {
            width: 30%;
            float: left;
        }

        .invoice-to .col-md-8 {
            width: 60%;
            float: left;
        }

        .invoice-to .col-md-4 {
            width: 33%;
            float: left;
        }

        table,
        td,
        th {
            border: 1px solid black;
            border-collapse: collapse;
            width: 100%;
        }

        .invoice-box .invoice-to .right table {
            border: none !important;
        }

        .invoice-box .invoice-to .right table thead tr th {
            border: none !important;
        }

        .payment-status-text {
            top: 10% !important;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="card-body invoice-box">

            <div class="card-header">
                @if($from !== NULL && $to !== NULL)
                <h4>Total Products: From {{ $from }} To {{ $to }}
                </h4>
                @elseif($from !== NULL)
                <h4>Total Products: In {{ $from }}
                </h4>
                @elseif($to !== NULL)
                <h4>Total Products: In {{ $to }}
                </h4>
                @endif

            </div>

            <!-- Total Products Start -->
            <div class="row invoice-test">
                <div class="card-body">
                    <table id="orders_table" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Serial</th>
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
            </div>
            <!-- Total Products End -->

        </div>
    </div>
</body>

</html>
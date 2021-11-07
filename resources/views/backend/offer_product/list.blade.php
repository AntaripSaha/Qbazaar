@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <span class="panel-title">{{ _lang('Offer Products') }}</span>
                @if(has_permission('offers.create'))
                <a class="btn btn-primary btn-sm ml-auto" data-title="{{ _lang('Add Product') }}"
                    href="{{ route('offers.create') }}"><i class="icofont-plus-circle"></i> {{ _lang('Add New') }}</a>
                @endif
            </div>
            <div class="card-body">
                <table id="products_table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>{{ _lang('Thumbnail') }}</th>
                            <th>{{ _lang('Name') }}</th>
                            <th>{{ _lang('Category') }}</th>
                            <th>{{ _lang('Unit') }}</th>
                            <th>{{ _lang('Price') }}</th>
                            <th>{{ _lang('In Stock') }}</th>
                            <th>{{ _lang('Status') }}</th>
                            <th class="text-center">{{ _lang('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js-script')
<script src="{{ asset('backend/assets/js/datatables/offers.js') }}"></script>
@endsection
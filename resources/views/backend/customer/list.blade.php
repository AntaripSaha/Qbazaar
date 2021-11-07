@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">

            <div class="card-header d-flex align-items-center">
                <h4 class="header-title">{{ _lang('Customer List') }}</h4>
                @if(has_permission('customers.create'))
                <a class="btn btn-primary btn-sm ml-auto ajax-modal" data-title="{{ _lang('Add New Customer') }}"
                    href="{{ route('customers.create') }}"><i class="icofont-plus-circle"></i> {{ _lang('Add New') }}</a>
                @endif
            </div>

            <div class="card-body">
                <table id="users_table" class="table table-bordered data-table">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>{{ _lang('Name') }}</th>
                            <th>{{ _lang('Email') }}</th>
                            <th>{{ _lang('Phone') }}</th>
                            <th>{{ _lang('Status') }}</th>
                            <th class="text-center">{{ _lang('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr data-id="row_{{ $user->id }}">
                            <td class='profile_picture text-center'><img
                                    src="{{ profile_picture($user->profile_picture) }}" class="thumb-sm img-thumbnail">
                            </td>
                            <td class='name'>{{ $user->name }}</td>
                            <td class='email'>{{ $user->email }}</td>
                            <td class='phone'>{{ $user->phone }}</td>
                            <td class='status'>{!! xss_clean(user_status($user->status)) !!}</td>
                            <td class="text-center">
                                <span class="dropdown">
                                    <button class="btn btn-primary dropdown-toggle btn-sm" type="button"
                                        id="dropdownMenuButton" data-toggle="dropdown">
                                        {{ _lang('Action') }}
                                    </button>
                                    <form action="{{ action('CustomerController@destroy', $user['id']) }}" method="post">
                                        {{ csrf_field() }}
                                        <input name="_method" type="hidden" value="DELETE">

                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a href="{{ action('CustomerController@edit', $user['id']) }}"
                                                data-title="{{ _lang('Add Delivery Boy') }}"
                                                class="dropdown-item ajax-modal"><i class="icofont-edit-alt"></i>
                                                {{ _lang('Edit') }}</a>
                                            <a href="{{ action('CustomerController@show', $user['id']) }}"
                                                data-title="{{ _lang('View Delivery Boy') }}"
                                                class="dropdown-item ajax-modal"><i class="icofont-eye-alt"></i>
                                                {{ _lang('View') }}</a>
                                            <button class="btn-remove dropdown-item" type="submit"><i
                                                    class="icofont-ui-delete"></i> {{ _lang('Delete') }}</button>
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
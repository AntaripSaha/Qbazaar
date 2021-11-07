@php $permissions = permission_list(); @endphp

<div class="sb-sidenav-menu-heading">{{ _lang('NAVIGATIONS') }}</div>

<a class="nav-link" href="{{ url('/admin/dashboard') }}">
	<div class="sb-nav-link-icon"><i class="icofont-dashboard-web"></i></div>
	{{ _lang('Dashboard') }}
</a>

@if (in_array('orders.index',$permissions))
<a class="nav-link" href="{{ route('orders.index') }}">
	<div class="sb-nav-link-icon"><i class="icofont-cart"></i></div>
	{{ _lang('Orders') }}
</a>
@endif

@if (in_array('transactions.index',$permissions))
<a class="nav-link" href="{{ route('transactions.index') }}">
	<div class="sb-nav-link-icon"><i class="icofont-credit-card"></i></div>
	{{ _lang('Transactions') }}
</a>
@endif

<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#products" aria-expanded="false" aria-controls="collapseLayouts">
	<div class="sb-nav-link-icon"><i class="icofont-box"></i></div>
	{{ _lang('Products') }}
	<div class="sb-sidenav-collapse-arrow"><i class="icofont-rounded-down"></i></div>
</a>
<div class="collapse" id="products" data-parent="#sidenavAccordion">
	<nav class="sb-sidenav-menu-nested nav">
        @if (in_array('products.index',$permissions))
		<a class="nav-link" href="{{ route('products.index') }}">{{ _lang('Products') }}</a>
        @endif

        @if (in_array('products.create',$permissions))
		<a class="nav-link" href="{{ route('products.create') }}">{{ _lang('Add New') }}</a>
        @endif

        @if (in_array('units.index',$permissions))
		<a class="nav-link" href="{{ route('units.index') }}">{{ _lang('Product Units') }}</a>
        @endif
	</nav>
</div>

<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#categories" aria-expanded="false" aria-controls="collapseLayouts">
	<div class="sb-nav-link-icon"><i class="icofont-listine-dots"></i></div>
	{{ _lang('Categories') }}
	<div class="sb-sidenav-collapse-arrow"><i class="icofont-rounded-down"></i></div>
</a>
<div class="collapse" id="categories" data-parent="#sidenavAccordion">
	<nav class="sb-sidenav-menu-nested nav">
        @if (in_array('categories.index',$permissions))
		<a class="nav-link" href="{{ route('categories.index') }}">{{ _lang('All Category') }}</a>
        @endif

        @if (in_array('categories.create',$permissions))
		<a class="nav-link" href="{{ route('categories.create') }}">{{ _lang('Add New') }}</a>
        @endif
	</nav>
</div>

@if (in_array('offers.index',$permissions))
<a class="nav-link" href="{{ route('offers.index') }}">
	<div class="sb-nav-link-icon"><i class="icofont-gift-box"></i></div>
	{{ _lang('Offers') }}
</a>
@endif

@if (in_array('coupons.index',$permissions))
<a class="nav-link" href="{{ route('coupons.index') }}">
	<div class="sb-nav-link-icon"><i class="icofont-sale-discount"></i></div>
	{{ _lang('Coupons') }}
</a>
@endif

@if (in_array('customers.index',$permissions))
<a class="nav-link" href="{{ route('customers.index') }}">
	<div class="sb-nav-link-icon"><i class="icofont-users-alt-4"></i></div>
	{{ _lang('Customers') }}
</a>
@endif

@if (in_array('delivery_boys.index',$permissions))
<a class="nav-link" href="{{ route('delivery_boys.index') }}">
	<div class="sb-nav-link-icon"><i class="icofont-users-alt-6"></i></div>
	{{ _lang('Delivery Boys') }}
</a>
@endif

@if (in_array('pages.index',$permissions))
<a class="nav-link" href="{{ route('pages.index') }}">
	<div class="sb-nav-link-icon"><i class="icofont-page"></i></div>
	{{ _lang('Custom Pages') }}
</a>
@endif

<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#reports" aria-expanded="false" aria-controls="collapseLayouts">
	<div class="sb-nav-link-icon"><i class="icofont-chart-line-alt"></i></div>
	{{ _lang('Reports') }}
	<div class="sb-sidenav-collapse-arrow"><i class="icofont-rounded-down"></i></div>
</a>
<div class="collapse" id="reports" data-parent="#sidenavAccordion">
	<nav class="sb-sidenav-menu-nested nav">
        @if (in_array('reports.sales_report',$permissions))
		<a class="nav-link" href="{{ route('reports.sales_report') }}">{{ _lang('Sales Report') }}</a>
        @endif

        @if (in_array('reports.order_report',$permissions))
		<a class="nav-link" href="{{ route('reports.order_report') }}">{{ _lang('Order Report') }}</a>
        @endif
	</nav>
</div>

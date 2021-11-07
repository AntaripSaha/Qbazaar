<div class="sb-sidenav-menu-heading">{{ _lang('NAVIGATIONS') }}</div>

<a class="nav-link" href="{{ url('/admin/dashboard') }}">
	<div class="sb-nav-link-icon"><i class="icofont-dashboard-web"></i></div>
	{{ _lang('Dashboard') }}
</a>

<a class="nav-link" href="{{ route('orders.index') }}">
	<div class="sb-nav-link-icon"><i class="icofont-cart"></i></div>
	{{ _lang('Orders') }}
</a>
<a class="nav-link" href="{{route('order.product')}}">
	<div class="sb-nav-link-icon"><i class="icofont-paperclip"></i></div>
	All Product
</a>

<a class="nav-link" href="{{ route('transactions.index') }}">
	<div class="sb-nav-link-icon"><i class="icofont-credit-card"></i></div>
	{{ _lang('Transactions') }}
</a>

<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#products" aria-expanded="false" aria-controls="collapseLayouts">
	<div class="sb-nav-link-icon"><i class="icofont-box"></i></div>
	{{ _lang('Products') }}
	<div class="sb-sidenav-collapse-arrow"><i class="icofont-rounded-down"></i></div>
</a>
<div class="collapse" id="products" data-parent="#sidenavAccordion">
	<nav class="sb-sidenav-menu-nested nav">
		<a class="nav-link" href="{{ route('products.index') }}">{{ _lang('Products') }}</a>
		<a class="nav-link" href="{{ route('products.create') }}">{{ _lang('Add New') }}</a>
		<a class="nav-link" href="{{ route('units.index') }}">{{ _lang('Product Units') }}</a>
	</nav>
</div>

<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#categories" aria-expanded="false" aria-controls="collapseLayouts">
	<div class="sb-nav-link-icon"><i class="icofont-listine-dots"></i></div>
	{{ _lang('Categories') }}
	<div class="sb-sidenav-collapse-arrow"><i class="icofont-rounded-down"></i></div>
</a>
<div class="collapse" id="categories" data-parent="#sidenavAccordion">
	<nav class="sb-sidenav-menu-nested nav">
		<a class="nav-link" href="{{ route('categories.index') }}">{{ _lang('All Category') }}</a>
		<a class="nav-link" href="{{ route('categories.create') }}">{{ _lang('Add New') }}</a>
	</nav>
</div>

<a class="nav-link" href="{{ route('offers.index') }}">
	<div class="sb-nav-link-icon"><i class="icofont-gift-box"></i></div>
	{{ _lang('Offers') }}
</a>

<a class="nav-link" href="{{ route('coupons.index') }}">
	<div class="sb-nav-link-icon"><i class="icofont-sale-discount"></i></div>
	{{ _lang('Coupons') }}
</a>

<a class="nav-link" href="{{ route('customers.index') }}">
	<div class="sb-nav-link-icon"><i class="icofont-users-alt-4"></i></div>
	{{ _lang('Customers') }}
</a>

<a class="nav-link" href="{{ route('delivery_boys.index') }}">
	<div class="sb-nav-link-icon"><i class="icofont-users-alt-6"></i></div>
	{{ _lang('Delivery Boys') }}
</a>

<a class="nav-link" href="{{ route('pages.index') }}">
	<div class="sb-nav-link-icon"><i class="icofont-page"></i></div>
	{{ _lang('Custom Pages') }}
</a>

<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#reports" aria-expanded="false" aria-controls="collapseLayouts">
	<div class="sb-nav-link-icon"><i class="icofont-chart-line-alt"></i></div>
	{{ _lang('Reports') }}
	<div class="sb-sidenav-collapse-arrow"><i class="icofont-rounded-down"></i></div>
</a>
<div class="collapse" id="reports" data-parent="#sidenavAccordion">
	<nav class="sb-sidenav-menu-nested nav">
		<a class="nav-link" href="{{ route('reports.sales_report') }}">{{ _lang('Sales Report') }}</a>
		<a class="nav-link" href="{{ route('reports.order_report') }}">{{ _lang('Order Report') }}</a>
	</nav>
</div>


<div class="sb-sidenav-menu-heading">{{ _lang('System Settings') }}</div>

<a class="nav-link" href="{{ route('settings.website_settings') }}">
	<div class="sb-nav-link-icon"><i class="icofont-paint"></i></div>
	{{ _lang('Website Settings') }}
</a>

<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#userManagement" aria-expanded="false" aria-controls="collapseLayouts">
	<div class="sb-nav-link-icon"><i class="icofont-users-alt-5"></i></div>
	{{ _lang('System Users') }}
	<div class="sb-sidenav-collapse-arrow"><i class="icofont-rounded-down"></i></div>
</a>
<div class="collapse" id="userManagement" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
	<nav class="sb-sidenav-menu-nested nav">
		<a class="nav-link" href="{{ route('users.index') }}">{{ _lang('All Users') }}</a>
		<a class="nav-link" href="{{ route('roles.index') }}">{{ _lang('User Roles') }}</a>
		<a class="nav-link" href="{{ route('permission.index') }}">{{ _lang('Access Control') }}</a>
	</nav>
</div>

<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#languages" aria-expanded="false" aria-controls="collapseLayouts">
	<div class="sb-nav-link-icon"><i class="icofont-earth"></i></div>
	{{ _lang('Languages') }}
	<div class="sb-sidenav-collapse-arrow"><i class="icofont-rounded-down"></i></div>
</a>
<div class="collapse" id="languages" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
	<nav class="sb-sidenav-menu-nested nav">
		<a class="nav-link" href="{{ route('languages.index') }}">{{ _lang('All Language') }}</a>
		<a class="nav-link" href="{{ route('languages.create') }}">{{ _lang('Add New') }}</a>
	</nav>
</div>

<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#administration" aria-expanded="false" aria-controls="collapseLayouts">
	<div class="sb-nav-link-icon"><i class="icofont-settings-alt"></i></div>
	{{ _lang('Administration') }}
	<div class="sb-sidenav-collapse-arrow"><i class="icofont-rounded-down"></i></div>
</a>
<div class="collapse" id="administration" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
	<nav class="sb-sidenav-menu-nested nav">
		<a class="nav-link" href="{{ route('settings.update_settings') }}">{{ _lang('General Settings') }}</a>
		<a class="nav-link" href="{{ route('email_templates.index') }}">{{ _lang('Email Template') }}</a>
		<a class="nav-link" href="{{ route('database_backups.list') }}">{{ _lang('Database Backup') }}</a>
	</nav>
</div>
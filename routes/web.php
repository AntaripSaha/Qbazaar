<?php

use App\Http\Controllers\OrderProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::group(['middleware' => ['install']], function () {

    Route::group(['prefix' => 'admin'], function () {
        Route::get('/', function () {
            return redirect()->route('login');
        });
        Auth::routes(['verify' => true, 'register' => false]);
        Route::get('/logout', 'Auth\LoginController@logout');
    });

    Route::group(['middleware' => ['auth', 'verified'], 'prefix' => 'admin'], function () {

        Route::get('dashboard', 'DashboardController@index');

        //Profile Controller
        Route::get('profile', 'ProfileController@index')->name('profile.index');
        Route::get('profile/edit', 'ProfileController@edit')->name('profile.edit');
        Route::post('profile/update', 'ProfileController@update')->name('profile.update');
        Route::get('profile/change_password', 'ProfileController@change_password')->name('profile.change_password');
        Route::post('profile/update_password', 'ProfileController@update_password')->name('profile.update_password');

        /** Admin Only Route **/
        Route::group(['middleware' => ['admin']], function () {
            //User Management
            Route::resource('users', 'UserController');

            //User Roles
            Route::resource('roles', 'RoleController');

            //Permission Controller
            Route::get('permission/control/{user_id?}', 'PermissionController@index')->name('permission.index');
            Route::post('permission/store', 'PermissionController@store')->name('permission.store');

            //Language Controller
            Route::match(['get', 'post'], 'languages/update_website_language/{language}', 'LanguageController@update_website_language')->name('languages.update_website_language');
            Route::resource('languages', 'LanguageController');

            //Utility Controller
            Route::match(['get', 'post'], 'administration/general_settings/{store?}', 'UtilityController@settings')->name('settings.update_settings');
            Route::match(['get', 'post'], 'administration/website_settings/{store?}', 'UtilityController@website_settings')->name('settings.website_settings');
            Route::post('administration/upload_logo', 'UtilityController@upload_logo')->name('settings.uplaod_logo');
            Route::post('administration/upload_website_logo', 'UtilityController@upload_website_logo')->name('settings.upload_website_logo');
            Route::get('administration/database_backup_list', 'UtilityController@database_backup_list')->name('database_backups.list');
            Route::get('administration/create_database_backup', 'UtilityController@create_database_backup')->name('database_backups.create');
            Route::delete('administration/destroy_database_backup/{id}', 'UtilityController@destroy_database_backup');
            Route::get('administration/download_database_backup/{id}', 'UtilityController@download_database_backup')->name('database_backups.download');
            Route::post('administration/remove_cache', 'UtilityController@remove_cache')->name('settings.remove_cache');

            //Email Template
            Route::resource('email_templates', 'EmailTemplateController')->only([
                'index', 'show', 'edit', 'update',
            ]);
        });

        /** Dynamic Permission **/
        Route::group(['middleware' => ['permission']], function () {

            Route::get('dashboard/total_sales_widget', 'DashboardController@total_sales_widget')->name('dashboard.total_sales_widget');
            Route::get('dashboard/current_day_sales_widget', 'DashboardController@current_day_sales_widget')->name('dashboard.current_day_sales_widget');
            Route::get('dashboard/pending_payments_widget', 'DashboardController@pending_payments_widget')->name('dashboard.pending_payments_widget');
            Route::get('dashboard/order_processing_widget', 'DashboardController@order_processing_widget')->name('dashboard.order_processing_widget');
            Route::get('dashboard/month_wise_sales_widget', 'DashboardController@month_wise_sales_widget')->name('dashboard.month_wise_sales_widget');
            Route::get('dashboard/recent_order_widget', 'DashboardController@recent_order_widget')->name('dashboard.recent_order_widget');

            //Product Controller
            Route::get('products/get_table_data', 'ProductController@get_table_data');
            Route::get('products/search_products', 'ProductController@search_products');
            Route::resource('products', 'ProductController')->except('show');

            //Order Controller
            Route::match(['get', 'post'], 'orders/assign_delivery_boy/{id?}', 'OrderController@assign_delivery_boy')->name('orders.assign_delivery_boy');
            Route::post('orders/trigger_assign_delivery_boy/{order_id}', 'OrderController@trigger_assign_delivery_boy');
            Route::get('orders/get_table_data', 'OrderController@get_table_data');
            Route::resource('orders', 'OrderController')->except([
                'create', 'store', 'edit',
            ]);

            //Order Product Controller
            Route::get('/all/product', [OrderProductController::class, 'index'])->name('order.product');
            //Order Product Search
            Route::get('/all/product/search', [OrderProductController::class, 'search'])->name('order.product.search');
            //Order Product Download Pdf
            Route::get('/all/product/download', [OrderProductController::class, 'download'])->name('order.product.download');
            //Searched Product's List Download Pdf
            Route::get('/all/product/search/download', 'OrderProductController@search_download')->name('order.product.search.download');


            //Transaction Controller
            Route::get('transactions/get_table_data', 'TransactionController@get_table_data');
            Route::get('transactions', 'TransactionController@index')->name('transactions.index');

            //Product Category
            Route::get('categories/search_categories', 'CategoryController@search_categories');
            Route::resource('categories', 'CategoryController');

            //Offer Products
            Route::get('offers/get_table_data', 'OfferController@get_table_data');
            Route::resource('offers', 'OfferController')->except('show');

            //Product Units
            Route::resource('units', 'UnitController')->except('show');

            //Coupon Controller
            Route::resource('coupons', 'CouponController')->except('show');

            //DeliveryBoy Management
            Route::resource('delivery_boys', 'DeliveryBoyController');

            //Customer Management
            Route::resource('customers', 'CustomerController');

            //Page Management
            Route::resource('pages', 'PageController');

            //Reports Controller
            Route::match(['get', 'post'], 'reports/order_report', 'ReportController@order_report')->name('reports.order_report');
            Route::match(['get', 'post'], 'reports/sales_report', 'ReportController@sales_report')->name('reports.sales_report');
        });
    });

    if (env('APP_INSTALLED', true) == true) {
        Route::get('{any}', function () {
            return view('theme.website');
        })->where('any', '.*');
    } else {
        Route::get('/', function () {
            return view('theme.website');
        });
    }
});

//Ajax Select2 Controller
Route::get('ajax/get_table_data', 'Select2Controller@get_table_data');

Route::get('installation', 'Install\InstallController@index');
Route::get('install/database', 'Install\InstallController@database');
Route::post('install/process_install', 'Install\InstallController@process_install');
Route::get('install/create_user', 'Install\InstallController@create_user');
Route::post('install/store_user', 'Install\InstallController@store_user');
Route::get('install/system_settings', 'Install\InstallController@system_settings');
Route::post('install/finish', 'Install\InstallController@final_touch');

//Update System
Route::get('migration/update', 'Install\UpdateController@update_migration');

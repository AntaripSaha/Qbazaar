@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-sm-3">
        <ul class="nav flex-column nav-tabs settings-tab" role="tablist">
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#general"><i
						class="icofont-settings-alt"></i> {{ _lang('General Settings') }}</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#system"><i
                        class="icofont-settings"></i> {{ _lang('System Settings') }}</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#email"><i
                        class="icofont-envelope"></i> {{ _lang('Email Settings') }}</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#payment_gateway"><i
                        class="icofont-credit-card"></i> {{ _lang('Payment Gateway') }}</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#social_login"><i
                        class="icofont-google-plus"></i> {{ _lang('Social Login') }}</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#logo"><i class="icofont-image"></i>
                    {{ _lang('Logo and Favicon') }}</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#cache"><i class="icofont-server"></i>
                    {{ _lang('Cache Control') }}</a></li>
        </ul>
    </div>

    @php $settings = \App\Models\Setting::all(); @endphp

    <div class="col-sm-9">
        <div class="tab-content">
            <div id="general" class="tab-pane active">
                <div class="card">

                    <div class="card-header">
                        <h4 class="header-title">{{ _lang('General Settings') }}</h4>
                    </div>

                    <div class="card-body">
                        <form method="post" class="settings-submit params-panel" autocomplete="off"
                            action="{{ route('settings.update_settings','store') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Company Name') }}</label>
                                        <input type="text" class="form-control" name="company_name"
                                            value="{{ get_setting($settings, 'company_name') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Site Title') }}</label>
                                        <input type="text" class="form-control" name="site_title"
                                            value="{{ get_setting($settings, 'site_title') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Phone') }}</label>
                                        <input type="text" class="form-control" name="phone"
                                            value="{{ get_setting($settings, 'phone') }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Email') }}</label>
                                        <input type="email" class="form-control" name="email"
                                            value="{{ get_setting($settings, 'email') }}">
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Timezone') }}</label>
                                        <select class="form-control select2" name="timezone" required>
                                            <option value="">{{ _lang('-- Select One --') }}</option>
                                            {{ create_timezone_option(get_setting($settings, 'timezone')) }}
                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Language') }}</label>
                                        <select class="form-control select2" name="language">
                                            <option value="">{{ _lang('-- Select One --') }}</option>
                                            {{ load_language( get_setting($settings, 'language') ) }}
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Address') }}</label>
                                        <textarea class="form-control"
                                            name="address">{{ get_setting($settings, 'address') }}</textarea>
                                    </div>
                                </div>


                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-lg"><i
                                                class="icofont-check-circled"></i> {{ _lang('Save Settings') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div id="system" class="tab-pane">
                <div class="card">
                    <div class="card-header">
                        <h4 class="header-title">{{ _lang('System Settings') }}</h4>
                    </div>

                    <div class="card-body">

                        <form method="post" class="settings-submit params-panel" autocomplete="off"
                            action="{{ route('settings.update_settings','store') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Backend Direction') }}</label>
                                        <select class="form-control" name="backend_direction" required>
                                            <option value="ltr"
                                                {{ get_setting($settings, 'backend_direction') == 'ltr' ? 'selected' : '' }}>
                                                {{ _lang('LTR') }}</option>
                                            <option value="rtl"
                                                {{ get_setting($settings, 'backend_direction') == 'rtl' ? 'selected' : '' }}>
                                                {{ _lang('RTL') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Currency') }}</label>
                                        <select class="form-control select2 auto-select"
                                            data-selected="{{ get_setting($settings, 'currency') }}" name="currency"
                                            id="currency" required>
                                            <option value="">{{ _lang('Select One') }}</option>
                                            {{ get_currency_list() }}
                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Currency Position') }}</label>
                                        <select class="form-control" name="currency_position" required>
                                            <option value="left"
                                                {{ get_setting($settings, 'currency_position') == 'left' ? 'selected' : '' }}>
                                                {{ _lang('Left') }}</option>
                                            <option value="right"
                                                {{ get_setting($settings, 'currency_position') == 'right' ? 'selected' : '' }}>
                                                {{ _lang('Right') }}</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Date Format') }}</label>
                                        <select class="form-control auto-select" name="date_format"
                                            data-selected="{{ get_setting($settings, 'date_format','Y-m-d') }}"
                                            required>
                                            <option value="Y-m-d">{{ date('Y-m-d') }}</option>
                                            <option value="d-m-Y">{{ date('d-m-Y') }}</option>
                                            <option value="d/m/Y">{{ date('d/m/Y') }}</option>
                                            <option value="m-d-Y">{{ date('m-d-Y') }}</option>
                                            <option value="m.d.Y">{{ date('m.d.Y') }}</option>
                                            <option value="m/d/Y">{{ date('m/d/Y') }}</option>
                                            <option value="d.m.Y">{{ date('d.m.Y') }}</option>
                                            <option value="d/M/Y">{{ date('d/M/Y') }}</option>
                                            <option value="d/M/Y">{{ date('M/d/Y') }}</option>
                                            <option value="d M, Y">{{ date('d M, Y') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Time Format') }}</label>
                                        <select class="form-control auto-select" name="time_format"
                                            data-selected="{{ get_setting($settings, 'time_format',24) }}" required>
                                            <option value="24">{{ _lang('24 Hours') }}</option>
                                            <option value="12">{{ _lang('12 Hours') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Email Verification') }}</label>
                                        <select class="form-control" name="email_verification" required>
                                            <option value="enabled"
                                                {{ get_setting($settings, 'email_verification') == 'enabled' ? 'selected' : '' }}>
                                                {{ _lang('Enable') }}</option>
                                            <option value="disabled"
                                                {{ get_setting($settings, 'email_verification') == 'disabled' ? 'selected' : '' }}>
                                                {{ _lang('Disable') }}</option>
                                        </select>
                                    </div>
                                </div>

								<div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Delivery Charge') }} ({{ currency() }})</label>
                                        <input type="text" class="form-control" name="delivery_charge"
                                            value="{{ get_setting($settings, 'delivery_charge', 10) }}">
                                    </div>
                                </div>

								<div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Free Delivery Amount') }} ({{ currency() }})</label>
                                        <input type="text" class="form-control" name="free_delivery_amount"
                                            value="{{ get_setting($settings, 'free_delivery_amount', 100) }}">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-lg"><i
                                                class="icofont-check-circled"></i> {{ _lang('Save Settings') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            <div id="email" class="tab-pane fade">
                <div class="card">
                    <div class="card-header">
                        <h4 class="header-title">{{ _lang('Email Settings') }}</h4>
                    </div>

                    <div class="card-body">
                        <form method="post" class="settings-submit params-panel" autocomplete="off"
                            action="{{ route('settings.update_settings','store') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Mail Type') }}</label>
                                        <select class="form-control niceselect wide" name="mail_type" id="mail_type"
                                            required>
                                            <option value="smtp"
                                                {{ get_setting($settings, 'mail_type')=="smtp" ? "selected" : "" }}>
                                                {{ _lang('SMTP') }}</option>
                                            <option value="sendmail"
                                                {{ get_setting($settings, 'mail_type')=="sendmail" ? "selected" : "" }}>
                                                {{ _lang('Sendmail') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('From Email') }}</label>
                                        <input type="text" class="form-control" name="from_email"
                                            value="{{ get_setting($settings, 'from_email') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('From Name') }}</label>
                                        <input type="text" class="form-control" name="from_name"
                                            value="{{ get_setting($settings, 'from_name') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('SMTP Host') }}</label>
                                        <input type="text" class="form-control smtp" name="smtp_host"
                                            value="{{ get_setting($settings, 'smtp_host') }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('SMTP Port') }}</label>
                                        <input type="text" class="form-control smtp" name="smtp_port"
                                            value="{{ get_setting($settings, 'smtp_port') }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('SMTP Username') }}</label>
                                        <input type="text" class="form-control smtp" autocomplete="off"
                                            name="smtp_username" value="{{ get_setting($settings, 'smtp_username') }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('SMTP Password') }}</label>
                                        <input type="password" class="form-control smtp" autocomplete="off"
                                            name="smtp_password" value="{{ get_setting($settings, 'smtp_password') }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('SMTP Encryption') }}</label>
                                        <select class="form-control smtp" name="smtp_encryption">
                                            <option value="">{{ _lang('None') }}</option>
                                            <option value="ssl"
                                                {{ get_setting($settings, 'smtp_encryption')=="ssl" ? "selected" : "" }}>
                                                {{ _lang('SSL') }}</option>
                                            <option value="tls"
                                                {{ get_setting($settings, 'smtp_encryption')=="tls" ? "selected" : "" }}>
                                                {{ _lang('TLS') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-lg"><i
                                                class="icofont-check-circled"></i> {{ _lang('Save Settings') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div id="payment_gateway" class="tab-pane fade">

                <div class="card">
                    <div class="card-header">
                        <h4 class="header-title">{{ _lang('Payment Gateway') }}</h4>
                    </div>

                    <div class="card-body">

                        <div class="accordion" id="payment_gateway">
                            <div class="card">
                                <div class="card-header params-panel" data-toggle="collapse" data-target="#collapseOne"
                                    aria-expanded="true" aria-controls="collapseOne">
                                    <span>{{ _lang('PayPal') }}</span>
                                </div>

                                <div id="collapseOne" class="collapse" aria-labelledby="headingOne"
                                    data-parent="#payment_gateway">
                                    <div class="card-body">
                                        <form method="post" class="settings-submit params-panel" autocomplete="off"
                                            action="{{ route('settings.update_settings','store') }}"
                                            enctype="multipart/form-data">

                                            {{ csrf_field() }}
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label
                                                            class="control-label">{{ _lang('Enable PayPal') }}</label>
                                                        <select class="form-control auto-select"
                                                            data-selected="{{ get_setting($settings, 'paypal_active','No') }}"
                                                            name="paypal_active" id="paypal_active" required>
                                                            <option value="No">{{ _lang('No') }}</option>
                                                            <option value="Yes">{{ _lang('Yes') }}</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">{{ _lang('Label') }}</label>
                                                        <input type="text" class="form-control" name="paypal_label"
                                                            value="{{ get_setting($settings, 'paypal_label','PayPal') }}"
                                                            required>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">{{ _lang('PayPal Mode') }}</label>
                                                        <select class="form-control auto-select"
                                                            data-selected="{{ get_setting($settings, 'paypal_mode','sandbox') }}"
                                                            name="paypal_mode" required>
                                                            <option value="sandbox">{{ _lang('Sandbox') }}</option>
                                                            <option value="production">{{ _lang('Production') }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">{{ _lang('Client ID') }}</label>
                                                        <input type="text" class="form-control" name="paypal_client_id"
                                                            value="{{ get_setting($settings, 'paypal_client_id') }}"
                                                            required>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">{{ _lang('Secret') }}</label>
                                                        <input type="text" class="form-control" name="paypal_secret"
                                                            value="{{ get_setting($settings, 'paypal_secret') }}"
                                                            required>
                                                    </div>
                                                </div>


                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-primary btn-lg"><i
                                                                class="icofont-check-circled"></i>
                                                            {{ _lang('Save Settings') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="card mt-2">
                                <div class="card-header params-panel" data-toggle="collapse" data-target="#collapseTwo"
                                    aria-expanded="true" aria-controls="collapseTwo">
                                    <span>{{ _lang('Stripe') }}</span>
                                </div>

                                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                                    data-parent="#payment_gateway">
                                    <div class="card-body">
                                        <form method="post" class="settings-submit params-panel" autocomplete="off"
                                            action="{{ route('settings.update_settings','store') }}"
                                            enctype="multipart/form-data">

                                            {{ csrf_field() }}
                                            <div class="row">

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label
                                                            class="control-label">{{ _lang('Enable Stripe') }}</label>
                                                        <select class="form-control auto-select"
                                                            data-selected="{{ get_setting($settings, 'stripe_active','No') }}"
                                                            name="stripe_active" required>
                                                            <option value="Yes">{{ _lang('Yes') }}</option>
                                                            <option value="No">{{ _lang('No') }}</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">{{ _lang('Label') }}</label>
                                                        <input type="text" class="form-control" name="stripe_label"
                                                            value="{{ get_setting($settings, 'stripe_label','Stripe') }}"
                                                            required>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">{{ _lang('Secret Key') }}</label>
                                                        <input type="text" class="form-control" name="stripe_secret_key"
                                                            value="{{ get_setting($settings, 'stripe_secret_key') }}"
                                                            required>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label
                                                            class="control-label">{{ _lang('Publishable Key') }}</label>
                                                        <input type="text" class="form-control"
                                                            name="stripe_publishable_key"
                                                            value="{{ get_setting($settings, 'stripe_publishable_key') }}"
                                                            required>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-primary btn-lg"><i
                                                                class="icofont-check-circled"></i>
                                                            {{ _lang('Save Settings') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!--End Stripe -->

                        </div>
                    </div>
                </div>
            </div>


            <div id="social_login" class="tab-pane fade">
                <div class="card">
                    <div class="card-header">
                        <h4 class="header-title">{{ _lang('Social Login') }}</h4>
                    </div>
                    <div class="card-body">
                        <form method="post" class="settings-submit params-panel" autocomplete="off"
                            action="{{ route('settings.update_settings','store') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <h5 class="header-title">{{ _lang('Google Login') }}</h5>
                            <div class="params-panel border border-dark p-3">
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ _lang('Google Login') }}</label>
                                            <select class="form-control select2 auto-select"
                                                data-selected="{{ get_setting($settings, 'google_login','disabled') }}"
                                                name="google_login" required>
                                                <option value="disabled">{{ _lang('Disable') }}</option>
                                                <option value="enabled">{{ _lang('Enable') }}</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ _lang('GOOGLE CLIENT ID') }}</label> <a
                                                href="https://console.developers.google.com/apis/credentials"
                                                target="_blank"
                                                class="btn-link float-right">{{ _lang('GET API KEY') }}</a>
                                            <input type="text" class="form-control" name="GOOGLE_CLIENT_ID"
                                                value="{{ get_setting($settings, 'GOOGLE_CLIENT_ID') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ _lang('GOOGLE CLIENT SECRET') }}</label>
                                            <input type="text" class="form-control" name="GOOGLE_CLIENT_SECRET"
                                                value="{{ get_setting($settings, 'GOOGLE_CLIENT_SECRET') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ _lang('GOOGLE REDIRECT URL') }}</label>
                                            <input type="text" class="form-control"
                                                value="{{ url('') }}" readOnly="true">
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <br>
                            <h5 class="header-title">{{ _lang('Facebook Login') }}</h5>
                            <div class="params-panel border border-dark p-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ _lang('Facebook Login') }}</label>
                                            <select class="form-control select2 auto-select"
                                                data-selected="{{ get_setting($settings, 'facebook_login','disabled') }}"
                                                name="facebook_login" required>
                                                <option value="disabled">{{ _lang('Disable') }}</option>
                                                <option value="enabled">{{ _lang('Enable') }}</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ _lang('FACEBOOK APP ID') }}</label> <a
                                                href="https://developers.facebook.com/apps" target="_blank"
                                                class="btn-link float-right">{{ _lang('GET API KEY') }}</a>
                                            <input type="text" class="form-control" name="FACEBOOK_CLIENT_ID"
                                                value="{{ get_setting($settings, 'FACEBOOK_CLIENT_ID') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ _lang('FACEBOOK APP SECRET') }}</label>
                                            <input type="text" class="form-control" name="FACEBOOK_CLIENT_SECRET"
                                                value="{{ get_setting($settings, 'FACEBOOK_CLIENT_SECRET') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ _lang('FACEBOOK REDIRECT URL') }}</label>
                                            <input type="text" class="form-control"
                                                value="{{ url('') }}" readOnly="true">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-lg"><i
                                                class="icofont-check-circled"></i> {{ _lang('Save Settings') }}</button>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            <div id="logo" class="tab-pane fade">
                <div class="card">
                    <div class="card-header">
                        <h4 class="header-title">{{ _lang('Logo and Favicon') }}</h4>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <form method="post" class="settings-submit params-panel" autocomplete="off"
                                    action="{{ route('settings.upload_website_logo') }}" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label">{{ _lang('Website Logo') }}
                                                    (PNG)</label>
                                                <input type="file" class="form-control dropify" name="website_logo"
                                                    data-max-file-size="1M" data-allowed-file-extensions="png"
                                                    data-default-file="{{ get_website_logo() }}" required>
                                            </div>
                                        </div>

                                        <br>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <button type="submit"
                                                    class="btn btn-primary btn-block">{{ _lang('Upload') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="col-lg-4">
                                <form method="post" class="settings-submit params-panel" autocomplete="off"
                                    action="{{ route('settings.uplaod_logo') }}" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label">{{ _lang('Launcher Icon') }}
                                                    (192px X 192px - PNG)</label>
                                                <input type="file" class="form-control dropify" name="logo"
                                                    data-max-file-size="1M" data-allowed-file-extensions="png"
                                                    data-default-file="{{ get_logo() }}" required>
                                            </div>
                                        </div>

                                        <br>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <button type="submit"
                                                    class="btn btn-primary btn-block">{{ _lang('Upload') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="col-lg-4">
                                <form method="post" class="settings-submit params-panel" autocomplete="off"
                                    action="{{ route('settings.update_settings','store') }}"
                                    enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label">{{ _lang('Favicon') }} (PNG)</label>
                                                <input type="file" class="form-control dropify" name="favicon"
                                                    data-max-file-size="1M" data-allowed-file-extensions="png"
                                                    data-default-file="{{ get_favicon() }}" required>
                                            </div>
                                        </div>

                                        <br>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <button type="submit"
                                                    class="btn btn-primary btn-block">{{ _lang('Upload') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--End Logo Tab-->


            <div id="cache" class="tab-pane fade">
                <div class="card">
                    <div class="card-header">
                        <h4 class="header-title">{{ _lang('Cache Control') }}</h4>
                    </div>

                    <div class="card-body">
                        <form method="post" class="params-panel" autocomplete="off"
                            action="{{ route('settings.remove_cache') }}">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="checkbox">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="cache[view_cache]"
                                                value="view_cache" id="view_cache">
                                            <label class="custom-control-label"
                                                for="view_cache">{{ _lang('View Cache') }}</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="checkbox">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input"
                                                name="cache[application_cache]" value="application_cache"
                                                id="application_cache">
                                            <label class="custom-control-label"
                                                for="application_cache">{{ _lang('Application Cache') }}</label>
                                        </div>
                                    </div>
                                </div>

                                <br>
                                <br>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit"
                                            class="btn btn-primary btn-lg">{{ _lang('Remove Cache') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--End Cache Tab-->
        </div>
    </div>
</div>
@endsection
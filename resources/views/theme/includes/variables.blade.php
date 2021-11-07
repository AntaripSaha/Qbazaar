<style type="text/css">
:root {--primary-color: {{ get_option('primary_color','#009f7f') }};}
#sidebar .nav-link.active {color: {{ get_option('sidebar_active_color','#009f7f;') }};}
.footer-top {background: {{ get_option('footer_top_color','#2f3640;') }};}
.footer-bottom {background: {{ get_option('footer_bottom_color','#26282b;') }};}
</style>

<script type="text/javascript">
let _url = "{{ url('') }}";let _currency_symbol = "{{ currency() }}";let _currency = "{{ get_option('currency','USD') }}";let _dc = "{{ get_option('delivery_charge') }}";let _fda = "{{ get_option('free_delivery_amount') }}";let _paypal_active= "{{ get_option('paypal_active','No') }}";let _stripe_active = "{{ get_option('stripe_active','No') }}";
</script>
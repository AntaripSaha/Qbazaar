<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PageResource;
use App\Models\Page;
use App\Models\Setting;

class SettingsController extends Controller {

    public function get_settings() {

        $columns  = ['company_name', 'site_title', 'phone', 'email', 'timezone', 'address', 'currency', 'currency_position', 'date_format', 'delivery_charge', 'free_delivery_amount', 'paypal_active', 'paypal_label', 'paypal_mode', 'paypal_client_id', 'stripe_active', 'stripe_label', 'stripe_publishable_key', 'google_login', 'GOOGLE_CLIENT_ID', 'facebook_login', 'FACEBOOK_CLIENT_ID', 'main_heading', 'home_banner', 'about_us', 'copyright', 'footer_popular_category'];
        $fields   = array();
        $settings = Setting::whereIn('name', $columns)->get();

        foreach ($columns as $column) {
            $setting = $settings->where('name', $column)->first();
            if ($setting) {
                $fields[$column] = $setting->value;
            } else {
                $fields[$column] = '';
            }
        }

        //Get Pages
        $pages = [];
        if (get_option('footer_important_links') != '') {
            $links = is_array(get_option('footer_important_links')) ? get_option('footer_important_links') : json_decode(get_option('footer_important_links'));
            $pages = PageResource::collection(Page::whereIn('id', $links)->get());
        }

        //Get Language
        $language = get_language();

        if (file_exists(resource_path() . "/language/$language.php")) {
            require resource_path() . "/language/website/$language.php";
        } else {
            require resource_path() . "/language/website/language.php";
        }

        return response()->json(['data' => $fields, 'language' => $language, 'pages' => $pages]);
    }

}

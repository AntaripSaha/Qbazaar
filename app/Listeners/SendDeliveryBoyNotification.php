<?php

namespace App\Listeners;

use App\Events\AssignDeliveryBoy;
use App\Mail\GeneralMail;
use App\Models\EmailTemplate;
use App\Utilities\Overrider;
use Illuminate\Support\Facades\Mail;

class SendDeliveryBoyNotification {
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct() {
        Overrider::load("Settings");
    }

    /**
     * Handle the event.
     *
     * @param  OrderUpdated  $event
     * @return void
     */
    public function handle(AssignDeliveryBoy $event) {
        $order = $event->order;

        //Replace paremeter
        $replace = array(
            '{name}'             => $order->delivery_boy->name,
            '{order_id}'         => $order->id,
            '{delivery_status}'  => $order->getDeliveryStatus(),
            '{payment_method}'   => ucwords(str_replace('_', ' ', $order->payment_method)),
            '{payment_status}'   => $order->getPaymentStatus(),
            '{customer_name}'    => $order->customer_name,
            '{customer_email}'   => $order->customer_email,
            '{customer_phone}'   => $order->customer_phone,
            '{shipping_address}' => $order->shipping_address,
        );

        $template = EmailTemplate::where('name', 'assign_delivery_boy')->first();

        $template->body = process_string($replace, $template->body);

        try {
            Mail::to($order->delivery_boy->email)->send(new GeneralMail($template));
        } catch (\Exception $e) {}
    }
}

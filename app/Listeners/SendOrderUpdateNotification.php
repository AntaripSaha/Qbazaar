<?php

namespace App\Listeners;

use App\Events\OrderUpdated;
use App\Mail\GeneralMail;
use App\Models\EmailTemplate;
use App\Utilities\Overrider;
use Illuminate\Support\Facades\Mail;

class SendOrderUpdateNotification {
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
    public function handle(OrderUpdated $event) {
        $order = $event->order;

        //Replace paremeter
        $replace = array(
            '{name}'            => $order->customer_name,
            '{order_id}'        => $order->id,
            '{delivery_status}' => $order->getDeliveryStatus(),
            '{payment_method}'  => ucwords(str_replace('_', ' ', $order->payment_method)),
            '{payment_status}'  => $order->getPaymentStatus(),
        );

        //Send Welcome email
        if ($order->delivery_status == 'canceled') {
            $template = EmailTemplate::where('name', 'order_canceled')->first();
        } else if ($order->delivery_status == 'completed') {
            $template = EmailTemplate::where('name', 'order_completed')->first();
        } else if ($order->delivery_status == 'processing') {
            $template = EmailTemplate::where('name', 'order_processing')->first();
        }

        $template->body = process_string($replace, $template->body);

        try {
            Mail::to($order->customer_email)->send(new GeneralMail($template));
        } catch (\Exception $e) {
            //Nothing
        }
    }
}

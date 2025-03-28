<?php

namespace App\Services;

use App\Models\Order;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPalService
{
    protected $provider;

    public function __construct()
    {
        $this->provider = new PaypalClient;
        $this->provider->setApiCredentials(config('paypal'));
        $this->provider->getAccessToken();
    }

    public function createOrder(Order $order)
    {
        $return_url = route('checkout.paypal.success');
        $cancel_url = route('checkout.paypal.cancel');

        $items = [];

        // Add each order item to PayPal order
        foreach ($order->orderItems as $item) {
            if ($item->product) {
                $items[] = [
                    'name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'unit_amount' => [
                        'currency_code' => 'USD',
                        'value' => number_format($item->price, 2, '.', ''),
                    ]
                ];
            }
        }

        // Create Paypal order
        $paypal_order = $this->provider->createOrder([
            'intent' => 'CAPTURE',
            'application_context' => [
                'return_url' => $return_url,
                'cancel_url' => $cancel_url,
                'branch_name' => config('app.name'),
                'shipping_preference' => 'SET_PROVIDED_ADDRESS'
            ],
            'purchase_units' => [
                [
                    'reference_id' => $order->id,
                    'description' => 'Order #' . $order->id,
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => number_format($order->total_amount, 2, '.', ','),
                        'breakdown' => [
                            'item_total' => [
                                'currency_code' => 'USD',
                                'value' => number_format($order->total_amount, 2, '.', '')
                            ]
                        ]
                    ],
                    'items' => $items,
                    'shipping' => [
                        'address' => $this->formatAddress($order->shipping_address)
                    ]
                ]
            ]
        ]);

        // If order creation was successful, update our order with PayPal info
        if(!empty($paypal_order['id'])) {
            $order->update([
                'payment_id' => $paypal_order['id']
            ]);

            // Return the approval URl to redirect user
            foreach($paypal_order['links'] as $link) {
                if($link['rel'] == 'approve') {
                    return $link['href'];
                }
            }
        }

        throw new \Exception('Failed to create PayPal order: ' . json_encode($paypal_order));
    }

    private function formatAddress($address)
    {
        $address_parts = explode(',', $address);

        return [
            'address_line_1' => trim($address_parts[0] ?? ''),
            'address_line_2' => trim($address_parts[1] ?? ''),
            'admin_area_2' => trim($address_parts[2] ?? 'City'), // City
            'admin_area_1' => trim($address_parts[3] ?? 'State'), // State
            'postal_code' => trim($address_parts[4] ?? '12345'),
            'country_code' => 'SA',
        ];
    }

    public function captureOrder($paypalOrderId) {
        $result = $this->provider->capturePaymentOrder($paypalOrderId);

        if($result['status'] === 'COMPLETED') {
            return $result;
        }

        throw new \Exception('Failed to capture PayPal payment: ' . json_encode($result));
    }

}

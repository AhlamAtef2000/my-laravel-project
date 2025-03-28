<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Checkout') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">{{ __('Order Summary') }}</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Product') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Price') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Quantity') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Total') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cartItems as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex item-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    @if ($item->product->image)
                                                        <img class="h-10 w-10 rounded-full object-cover"
                                                            src="{{ asset('storage/' . $item->product->image) }}"
                                                            alt="{{ $item->product->name }}">
                                                    @else
                                                        <div
                                                            class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                            <span class="text-xs text-gray-500">No IMG</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $item->product->name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                ${{ number_format($item->product->price, 2) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $item->quantity }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                ${{ number_format($item->product->price * $item->quantity, 2) }}</div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td class="px-6 py-4 text-right font-medium">
                                        {{ __('Total') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-lg font-bold text-gray-900">${{ number_format($total, 2) }}
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">{{ __('Shipping And Payment') }}</h3>
                    <form action="{{ route('checkout.process') }}" method="POST">
                        @csrf

                        <div class="mb-6">
                            <label for="shipping_address"
                                class="block text-sm font-medium text-gray-700">{{ __('Shipping Address') }}</label>
                            <textarea name="shipping_address" id="shipping_address" rows="3"
                                class="mt-1 focus:ring-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('shipping_address', Auth::user()->address) }}</textarea>
                            @error('shipping_address')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="payment_method"
                                class="block text-sm font-medium text-gray-700">{{ __('Payment Method') }}</label>
                            <div class="mt-2 space-y-2">
                                <div class="relative flex items-start">
                                    <div class="flex items-center h-5">
                                        <input
                                            id="payment_method_paypal"
                                            name="payment_method"
                                            type="radio"
                                            value="paypal" {{ old('payment_method') == 'paypal' ? 'checked' : '' }}
                                            class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="payment_method_paypal" class="font-medium text-gray-700 flex items-center">
                                            {{ __('PayPal') }}
                                            <img src="https://www.paypalobjects.com/webstatic/mktg/logo/pp_cc_mark_37x23.jpg" alt="PayPal Logo" class="h-6 ml-2">
                                        </label>
                                        <p class="text-gray-500">{{ __('Pay securely via PayPal. You can use your PayPal balance, bank account, or credit card.') }}</p>
                                    </div>
                                </div>

                                <div class="relative flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="payment_method_credit_card" name="payment_method" type="radio" value="credit_card" {{ old('payment_method', 'credit_card') == 'credit_card' ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="payment_method_credit_card" class="font-medium text-gray-700 flex items-center">
                                            {{ __('Credit Card') }}
                                            <div class="flex space-x-1 ml-2">
                                                <svg class="h-6 w-10" viewBox="0 0 40 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <rect width="40" height="24" rx="4" fill="#1434CB" />
                                                    <path d="M21.3344 7.17602L17.9926 16.824H15.2832L13.6382 9.40179C13.5538 9.04802 13.4352 8.89079 13.1337 8.75C12.6422 8.52143 11.8006 8.30714 11.0586 8.17602L11.1047 7.17602H15.3614C15.9232 7.17602 16.4321 7.59587 16.5639 8.17602L17.3824 13.5159L19.6015 7.17602H21.3344Z" fill="white" />
                                                    <path d="M29.4022 13.9196C29.4022 15.8215 27.8952 17.0018 25.7935 17.0018C24.9286 17.0337 24.0704 16.8133 23.3384 16.3679L23.8218 14.8113C24.4158 15.1888 25.0911 15.3932 25.7833 15.4062C26.4702 15.4062 27.0905 15.0923 27.0905 14.5448C27.0905 14.1104 26.6705 13.8379 25.857 13.4312C25.0462 13.0269 23.9227 12.3794 23.9227 11.1218C23.9227 9.32924 25.428 8.0141 27.3789 8.0141C28.1279 8.00382 28.8659 8.18513 29.531 8.54166L29.0732 10.042C28.5613 9.77822 27.9941 9.64052 27.4196 9.64102C26.8173 9.64102 26.2344 9.90602 26.2344 10.3853C26.2344 10.8044 26.7581 11.0769 27.499 11.4685C28.6072 12.0134 29.4022 12.6761 29.4022 13.9196Z" fill="white" />
                                                    <path d="M30.1562 12.6135C30.1562 9.70407 32.3327 7.0141 35.5663 7.0141C38.8 7.0141 40.9664 9.70407 40.9664 12.6135C40.9664 15.5229 38.79 18.1972 35.5663 18.1972C32.3428 18.1972 30.1562 15.5229 30.1562 12.6135ZM38.4176 12.6135C38.4176 10.9708 37.2689 9.47992 35.5663 9.47992C33.8638 9.47992 32.705 10.9708 32.705 12.6135C32.705 14.2562 33.8638 15.7471 35.5663 15.7471C37.2689 15.7471 38.4176 14.2562 38.4176 12.6135Z" fill="white" />
                                                </svg>
                                                <svg class="h-6 w-10" viewBox="0 0 40 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <rect width="40" height="24" rx="4" fill="#252525" />
                                                    <path d="M22.2657 16.3801C20.3925 18.0295 17.2815 18.6418 14.1392 18.6418C10.8033 18.6418 8.09495 17.9045 7 16.8285L7.6239 12.6498C8.71886 13.7259 11.6724 14.7582 14.4783 14.7582C16.4207 14.7582 17.744 14.341 17.744 13.5134C17.744 12.1326 14.0441 12.0864 10.8789 10.1931C7.92094 8.43439 7.5818 5.7467 11.0343 4.16427C12.7781 3.43396 15.2837 3 17.5903 3C20.4697 3 22.5724 3.66729 23.5 4.62624L22.8761 8.43439C21.7811 7.45845 19.4361 6.82817 17.2512 6.82817C15.2837 6.82817 14.3512 7.31181 14.3512 7.95907C14.3512 9.15823 17.899 9.06198 21.0627 10.8639C24.1105 12.5597 24.5293 15.0634 22.2657 16.3801Z" fill="white" />
                                                    <path d="M25.5 18.5L29.4417 5H33L28.9722 18.5H25.5Z" fill="white" />
                                                </svg>
                                                <svg class="h-6 w-10" viewBox="0 0 40 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <rect width="40" height="24" rx="4" fill="#FF5F00" />
                                                    <path d="M15.5355 16.5984C14.0424 15.1054 14.0424 12.7118 15.5355 11.2187L23.5747 3.17944C27.1549 6.75969 27.1549 12.5835 23.5747 16.1637L15.5355 16.5984Z" fill="#EB001B" />
                                                    <path d="M25.0678 16.5984C26.5608 15.1054 26.5608 12.7118 25.0678 11.2187L17.0286 3.17944C13.4483 6.75969 13.4483 12.5835 17.0286 16.1637L25.0678 16.5984Z" fill="#F79E1B" />
                                                </svg>
                                            </div>
                                        </label>
                                        <p class="text-gray-500">{{__('Pay with your credit or debit card.')}}</p>
                                    </div>
                                </div>

                                <div class="relative flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="payment_method_bank_transfer" name="payment_method" type="radio" value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="payment_method_bank_transfer" class="font-medium text-gray-700">{{__('Bank Transfer')}}</label>
                                        <p class="text-gray-500">{{__('Make your payment directly into our bank account. Please use your Order ID as the payment reference.')}}</p>
                                    </div>
                                </div>

                                <div class="relative flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="payment_method_cash_on_delivery" name="payment_method" type="radio" value="cash_on_delivery" {{ old('payment_method') == 'cash_on_delivery' ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="payment_method_cash_on_delivery" class="font-medium text-gray-700">{{__('Cash on Delivery')}}</label>
                                        <p class="text-gray-500">{{__('Pay with cash upon delivery.')}}</p>
                                    </div>
                                </div>
                            </div>
                            @error('payment_method')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex justify-between items-center">
                            <a href="{{ route('cart.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Back to Cart') }}
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Place Order') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

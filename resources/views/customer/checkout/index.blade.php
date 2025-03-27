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
                                                R{{ number_format($item->product->price, 2) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $item->quantity }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                R{{ number_format($item->product->price * $item->quantity, 2) }}</div>
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
                                        <div class="text-lg font-bold text-gray-900">R{{ number_format($total, 2) }}
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
                            <label for="payment_method" class="block text-sm font-medium text-gray-700">{{__('Payment Method')}}</label>
                            <select name="payment_method" id="payment_method" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="credit_card" {{old('payment_method') == 'credit_card' ? 'selected' : ''}}>{{__('Credit Card')}}</option>
                                <option value="paypal" {{old('payment_method') == 'paypal' ? 'selected' : ''}}>{{__('Paypal')}}</option>
                                <option value="bank_transfer" {{old('payment_method') == 'bank_transfer' ? 'selected' : ''}}>{{__('Back Transfer')}}</option>
                                <option value="cash_on_delivery" {{old('payment_method') == 'cash_on_delivery' ? 'selected' : ''}}>{{__('Cash on delivery')}}</option>
                            </select>
                            @error('payment_method')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex justify-between items-center">
                            <a href="{{route('cart.index')}}"  class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{__('Back to Cart')}}
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{__('Place Order')}}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

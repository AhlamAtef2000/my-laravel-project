<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Shopping Cart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($cartItems->isEmpty())
                        <div class="text-center py-8">
                            <p class="text-lg text-gray-600 mb-6">{{ __('Your cart is empty.') }}</p>
                            <a href="{{ route('products.index') }}"
                                class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Continue Shopping') }}
                            </a>
                        </div>
                    @else
                        <div class="flex flex-col">
                            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                                    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
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
                                                    <th scope="col"
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        {{ __('Action') }}
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach ($cartItems as $item)
                                                    <tr>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="flex items-center">
                                                                <div class="flex-shrink-0 h-10 w-10">
                                                                    @if ($item->product->image)
                                                                        <img class="h-10 w-10 rounded-full object-cover"
                                                                            src="{{ asset('storage/' . $item->product->image) }}"
                                                                            alt="{{ $item->product->name }}">
                                                                    @else
                                                                        <div
                                                                            class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                                            <span class="text-xs text-gray-500">No
                                                                                IMG</span>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="ml-4">
                                                                    <div class="text-sm font-medium text-gray-900">
                                                                        <a href="{{ route('products.show', $item->product) }}"
                                                                            class="hover:underline">
                                                                            {{ $item->product->name }}
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-900">
                                                                ${{ number_format($item->product->price, 2) }}</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <form action="{{ route('cart.update', $item) }}"
                                                                method="POST" class="flex items-center">
                                                                @csrf
                                                                @method('PUT')
                                                                <select name="quantity"
                                                                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                                    @for ($i = 1; $i <= min(10, $item->product->stock); $i++)
                                                                        <option value="{{ $i }}"
                                                                            {{ $item->quantity == $i ? 'selected' : '' }}>
                                                                            {{ $i }}</option>
                                                                    @endfor
                                                                </select>
                                                                <button type="submit"
                                                                    class="ml-2 text-xs text-indigo-600 hover:text-indigo-900">
                                                                    {{ __('Update') }}
                                                                </button>
                                                            </form>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-900">
                                                                ${{ number_format($item->product->price * $item->quantity, 2) }}
                                                            </div>
                                                        </td>
                                                        <td
                                                            class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                            <form action="{{ route('cart.destroy', $item) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="text-red-600 hover:text-red-900">{{ __('Remove') }}</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8 flex justify-between items-start">
                                <div>
                                    <form action="{{ route('cart.clear') }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            {{ __('Clear Cart') }}
                                        </button>
                                    </form>
                                </div>
                                <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                                    <div class="flex justify-between mb-2">
                                        <span class="text-gray-600">{{ __('Subtotal:') }}</span>
                                        <span class="text-gray-900">R{{ number_format($total, 2) }}</span>
                                    </div>
                                    <div class="border-t border-gray-200 pt-2 flex justify-between mb-2">
                                        <span class="text-lg font-semibold">{{ __('Total:') }}</span>
                                        <span class="text-lg font-bold">R{{ number_format($total, 2) }}</span>
                                    </div>
                                    <div class="mt-4">
                                        <a href="{{ route('checkout.index') }}" class="w-full block text-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            {{ __('Proceed to Checkout') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

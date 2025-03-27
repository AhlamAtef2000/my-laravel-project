<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Order details') }}: #{{ $order->id }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.orders.edit', $order) }}"
                    class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Edit Order') }}
                </a>
                <a href="{{ route('admin.orders.index') }}"
                    class="px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Back to List') }}
                </a>
            </div>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- order info --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">{{ __('Order Information') }}
                            </h3>
                            <div class="border-t border-gray-200 py-3">
                                <dl>
                                    <div class="grid grid-cols-3 gap-4 py-2">
                                        <dt class="text-sm font-medium text-gray-500">Order ID:</dt>
                                        <dd class="text-sm text-gray-900 col-span-2">#{{ $order->id }}</dd>
                                    </div>
                                    <div class="grid grid-cols-3 gap-4 py-2">
                                        <dt class="text-sm font-medium text-gray-500">Date:</dt>
                                        <dd class="text-sm text-gray-900 col-span-2">
                                            {{ $order->created_at->format('M d, Y h:i A') }}</dd>
                                    </div>
                                    <div class="grid grid-cols-3 gap-4 py-2">
                                        <dt class="text-sm font-medium text-gray-500">Status:</dt>
                                        <dd class="text-sm text-gray-900 col-span-2">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if ($order->status == 'completed') bg-green-100 text-green-800
                                                @elseif($order->status == 'cancelled')
                                                    bg-red-100 text-red-800
                                                @elseif($order->status == 'processing')
                                                    bg-blue-100 text-blue-800
                                                @else
                                                    bg-yellow-100 text-yellow-800 @endif
                                            ">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </dd>
                                    </div>
                                    <div class="grid grid-cols-3 gap-4 py-2">
                                        <dt class="text-sm font-medium text-gray-500">Payment Method:</dt>
                                        <dd class="text-sm text-gray-900 col-span-2">
                                            {{ $order->payment_method ?? 'Not specified' }}</dd>
                                    </div>
                                    <div class="grid grid-cols-3 gap-4 py-2">
                                        <dt class="text-sm font-medium text-gray-500">Payment Status:</dt>
                                        <dd class="text-sm text-gray-900 col-span-2">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if ($order->payment_status == 'paid') bg-green-100 text-green-800
                                                @else
                                                    bg-yellow-100 text-yellow-800 @endif
                                            ">
                                                {{ ucfirst($order->payment_status) }}
                                            </span>
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">
                                {{ __('Customer Information') }}</h3>
                            <div class="border-t border-gray-200 py-3">
                                <dl>
                                    <div class="grid grid-cols-3 gap-4 py-2">
                                        <dt class="text-sm font-medium text-gray-500">Name:</dt>
                                        <dd class="text-sm text-gray-900 col-span-2">
                                            <a href="{{ route('admin.customers.show', $order->user_id) }}"
                                                class="text-indigo-600 hover:text-indigo-900">
                                                {{ $order->user->name }}
                                            </a>
                                        </dd>
                                    </div>
                                    <div class="grid grid-cols-3 gap-4 py-2">
                                        <dt class="text-sm font-medium text-gray-500">Email:</dt>
                                        <dd class="text-sm text-gray-900 col-span-2">{{ $order->user->email }}</dd>
                                    </div>
                                    <div class="grid grid-cols-3 gap-4 py-2">
                                        <dt class="text-sm font-medium text-gray-500">Phone:</dt>
                                        <dd class="text-sm text-gray-900 col-span-2">
                                            {{ $order->user->phone ?? 'Not provided' }}</dd>
                                    </div>
                                    <div class="grid grid-cols-3 gap-4 py-2">
                                        <dt class="text-sm font-medium text-gray-500">Shipping Address:</dt>
                                        <dd class="text-sm text-gray-900 col-span-2">
                                            {{ $order->shipping_address ?? 'Not provided' }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Order Items --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">{{ __('Order Items') }}</h3>
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
                                        {{ __('Subtotal') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($order->orderItems as $item)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    @if ($item->product && $item->product->image)
                                                        <img class="h-10 w-10 rounded-md object-cover"
                                                            src="{{ asset('storage/' . $item->product->image) }}"
                                                            alt="{{ $tem->product->name }}" />
                                                    @else
                                                        <div
                                                            class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                            <span class="text-xs tex-gray-500">No IMG</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        @if ($item->product)
                                                            <a href="{{ route('admin.products.show', $item->product) }}"
                                                                class="hover:underline">
                                                                {{ $item->product->name }}
                                                            </a>
                                                        @else
                                                            <span class="text-red-500">Product Deleted</span>
                                                        @endif
                                                    </div>
                                                    @if ($item->product)
                                                        <div class="text-sm text-gray-500">SKU:
                                                            {{ $item->product->id }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">R{{ number_format($item->price, 2) }}
                                            </div>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $item->quantity }}</div>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                               R{{ number_format($item->price * $item->quantity, 2) }}</div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-right font-medium">
                                        {{ __('Total:') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-lg font-bold text-gray-900">
                                            R{{ number_format($order->total_amount, 2) }}
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center">
                        <form action="{{ route('admin.orders.destroy', $order) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this order? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Delete Order') }}
                            </button>
                            <a href="{{ route('admin.orders.edit', $order) }}"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Update Order Status') }}
                            </a>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

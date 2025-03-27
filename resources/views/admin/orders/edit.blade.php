<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Order') }}: #{{ $order->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
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
                                        <dt class="text-sm font-medium text-gray-500">Customer:</dt>
                                        <dd class="text-sm text-gray-900 col-span-2">
                                            <a href="{{ route('admin.customers.show', $order->user_id) }}"
                                                class="text-indigo-600 hover:text-indigo-900">
                                                {{ $order->user->name }}
                                            </a>
                                        </dd>
                                    </div>
                                    <div class="grid grid-cols-3 gap-4 py-2">
                                        <dt class="text-sm font-medium text-gray-500">Total:</dt>
                                        <dd class="text-sm font-bod text-gray-900 col-span-2">
                                            ${{ number_format($order->total_amount, 2) }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="status"
                                    class="block text-sm font-medium text-gray-700">{{ __('Order Status') }}</label>
                                <select name="status" id="status"
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:tex-sm rounded-md">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>
                                        Processing</option>
                                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>
                                        Completed</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>
                                        Cancelled</option>
                                </select>
                                @error('status')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="payment_status"
                                    class="block text-sm font-medium text-gray-700">{{ __('Payment Status') }}</label>
                                <select name="payment_status" id="payment_status"
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="pending"
                                        {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>
                                        Paid</option>
                                    <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>
                                        Failed</option>
                                    <option value="refunded"
                                        {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                                </select>
                                @error('payment_status')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="flex justify-between">
                            <a href="{{route('admin.orders.show', $order)}}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{__('Cancel')}}
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{__('Update Order')}}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

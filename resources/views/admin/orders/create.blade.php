<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Order') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.orders.store') }}" method="POST">
                        @csrf

                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Customer Information') }}</h3>
                            <div class="mb-4">
                                <label for="user_id"
                                    class="block text-sm font-medium text-gray-700">{{ __('Select Customer') }}</label>
                                <select name="user_id" id="user_id"
                                    class="mt-1 w-full pl-3 pr-10 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}"
                                            {{ old('user_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }} ({{ $customer->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="shipping_address"
                                    class="block text-sm font-medium text-gray-700">{{ __('Shipping Address') }}</label>
                                <textarea name="shipping_address" id="shipping_address" rows="3"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('shipping_address') }}</textarea>
                                @error('shipping_address')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">{{ __('Order Information') }}
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="payment_method"
                                        class="block text-sm font-medium text-gray-700">{{ __('Payment Method') }}</label>
                                    <input type="text" name="payment_method" id="payment_method"
                                        value="{{ old('payment_method') }}"
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                                    @error('payment_method')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="payment_status"
                                        class="block text-sm font-medium text-gray-700">{{ __('Payment Status') }}</label>
                                    <select name="payment_status" id="payment_status"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                        <option value="pending"
                                            {{ old('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="paid" {{ old('payment_status') == 'paid' ? 'selected' : '' }}>
                                            Paid</option>
                                        <option value="failed"
                                            {{ old('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                    </select>
                                    @error('payment_status')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="status" class="block text-sm font-medium text-gray-700">{{__('Order Status')}}</label>
                                <select name="status" id="status">
                                    <option value="pending" {{old('status') == 'pending' ? 'selected' : ''}}>Pending</option>
                                    <option value="processing" {{old('status') == 'processing' ? 'selected' : ''}}>Processing</option>
                                    <option value="completed" {{old('status') == 'completed' ? 'selected' : ''}}>Completed</option>
                                </select>
                                @error('status')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">{{__('Order Items')}}</h3>
                            <div id="order-items">
                                <div class="order-item bg-gray-50 p-4 rounded-md mb-4">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label for="items[0][product_id]" class="block text-sm font-medium text-gray-700">{{__('Product')}}</label>
                                            <select name="items[0][product_id]" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                                <option value="">-- Select Product --</option>
                                                @foreach($products as $product)
                                                <option value="{{$product->id}}" {{old('items.0.product_id') == $product->id ? 'selected' : ''}}>
                                                    {{$product->name}} - ${{number_format($product->price, 2)}} ({{$product->stock}} in stock)
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('items.0.product_id')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="items[0][quantity]" class="block text-sm font-medium text-gray-700">{{ __('Quantity') }}</label>
                                            <input type="number" name="items[0][quantity]" min="1" value="{{old('items.0.quantity', 1)}}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            @error('items.0.quantity')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <button type="button" id="add-item" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                    {{ __('Add Another Item') }}
                                </button>
                            </div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const addItemBtn = document.getElementById('add-item');
                                    const orderItemsContainer = document.getElementById('order-items');
                                    let itemCount = 1;

                                    addItemBtn.addEventListener('click', function() {
                                        const newItem = document.createElement('div');
                                        newItem.className = 'order-item bg-gray-50 p-4 rounded-md mb-4';
                                        newItem.innerHTML = `
                                            <div class="flex justify-end mb-2">
                                                <button type="button" class="remove-item text-sm text-red-600 hover:text-red-900">
                                                    Remove
                                                </button>
                                            </div>
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                <div>
                                                    <label for="items[${itemCount}][product_id]" class="block text-sm font-medium text-gray-700">{{ __('Product') }}</label>
                                                    <select name="items[${itemCount}][product_id]" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                                        <option value="">-- Select Product --</option>
                                                        @foreach($products as $product)
                                                            <option value="{{ $product->id }}">
                                                                {{ $product->name }} - ${{ number_format($product->price, 2) }} ({{ $product->stock }} in stock)
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div>
                                                    <label for="items[${itemCount}][quantity]" class="block text-sm font-medium text-gray-700">{{ __('Quantity') }}</label>
                                                    <input type="number" name="items[${itemCount}][quantity]" min="1" value="1" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                                </div>
                                            </div>
                                        `;
                                        orderItemsContainer.appendChild(newItem);

                                        // add event listener to the remove button
                                        const removeBtn = newItem.querySelector('.remove-item');
                                        removeBtn.addEventListener('click', function() {
                                            orderItemsContainer.removeChild(newItem);
                                        })

                                        itemCount++;
                                    });
                                });
                            </script>
                        </div>
                        <div class="flex justify-between">
                            <a href="{{route('admin.orders.index')}}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{__('Cancel')}}
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2  bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{__('Create Order')}}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

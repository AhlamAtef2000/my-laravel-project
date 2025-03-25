<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(Auth::user()->isAdmin())
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold">{{ __('Admin Dashboard') }}</h3>
                            <p class="text-gray-600">{{ __('Welcome to the admin dashboard. Here you can manage products, customers, and orders.') }}</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-indigo-100 p-6 rounded-lg shadow">
                                <h4 class="font-semibold text-lg mb-2">{{__('Products')}}</h4>
                                <p class="mb-4">{{__('Manage your product inventory')}}</p>
                                <a href="{{ route('admin.products.index')}}" class='inline-block px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700'>
                                    {{ __('Manage Products')}}
                                </a>
                            </div>
                            <div class="bg-green-100 p-6 rounded-lg shadow">
                                <h4 class="font-semibold text-lg mb-2">{{__('Customers')}}</h4>
                                <p class="mb-4">{{__('View and manage customer accounts')}}</p>
                                <a href="{{ route('admin.customers.index')}}" class="inline-block px-4 py-2 bg-green-600 text-white rounded hover:bg-green-70">
                                    {{ __('Manage Customers')}}
                                </a>
                            </div>
                            <div class="bg-amber-100 p-6 rounded-lg shadow">
                                <h4 class="font-semibold text-lg mb-2">{{__('Orders')}}</h4>
                                <p class="mb-4">{{__('Process and manage orders')}}</p>
                                <a href="{{ route('admin.orders.index')}}" class="inline-block px-4 py-2 bg-amber-600 text-white rounded hover:bg-amber-70">
                                    {{__('Manage Orders')}}
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold">{{ __('Welcome, ') . Auth::user()->name }}</h3>
                            <p class="text-gray-600">{{ __('Thank you for shopping with us. From your account dashboard you can view your orders, manage your shipping address, and update your account details.') }}</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-blue-100 p-6 rounded-lg shadow">
                                <h4 class="font-semibold text-lg mb-2">{{ __('Recent Orders') }}</h4>
                                <p class="mb-4">{{ __('View your order history and track shipments') }}</p>
                                <a href="{{ route('orders.index') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                    {{ __('View Orders') }}
                                </a>
                            </div>

                            <div class="bg-purple-100 p-6 rounded-lg shadow">
                                <h4 class="font-semibold text-lg mb-2">{{ __('Your Cart') }}</h4>
                                <p class="mb-4">{{ __('View your shopping cart and proceed to checkout') }}</p>
                                <a href="{{ route('cart.index') }}" class="inline-block px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
                                    {{ __('View Cart') }}
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

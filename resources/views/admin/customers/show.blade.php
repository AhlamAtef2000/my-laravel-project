<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{__('Customer Details')}}: {{$customer->name}}
            </h2>
            <div class="flex space-x-2">
                <a href="{{route('admin.customers.edit', $customer)}}" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900  focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{__('Edit')}}
                </a>
                <a href="{{route('admin.customers.index')}}" class="px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900  focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{__('Back to List')}}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="md:grid md:grid-cols-3 md:gap-6">
                        <div class="md:col-span-1">
                            <div class="px-4 sm:px-0">
                                <h3 class="text-lg font-medium leading-6 text-gray-600">{{__('Customer Information')}}</h3>
                                <p class="mt-1 text-sm text-gray-600">
                                    {{__('Personal details and contact information.')}}
                                </p>
                            </div>
                        </div>
                        <div class="mt-5 md:mt-0 md:col-span-2">
                            <div class="shadow sm:rounded-md sm:overflow-hidden">
                                <div class="px-4 py-5 bg-white space-y gap-6">
                                    <div class="grid grid-cols-2 gap-6">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-500">{{__('Name')}}</h4>
                                            <p class="mt-1 text-sm text-gray-900">{{$customer->name}}</p>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-500">{{__('Email')}}</h4>
                                            <p class="mt-1 text-sm text-gray-900">{{$customer->email}}</p>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-6">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-500">{{__('Phone')}}</h4>
                                            <p class="mt-1 text-sm text-gray-900">{{$customer->phone ?? 'Not provided'}}</p>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-500">{{__('Address')}}</h4>
                                            <p class="mt-1 text-sm text-gray-900">{{$customer->address ?? 'Not provided'}}</p>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-6">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-500">{{__('Registration Date')}}</h4>
                                            <p class="mt-1 text-sm text-gray-900">{{ $customer->created_at->format('M d, Y H:i') }}</p>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-500">{{__('Last Updated')}}</h4>
                                            <p class="mt-1 text-sm text-gray-900">{{ $customer->updated_at->format('M d, Y H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6">
                                <form action="{{route('admin.customers.destroy', $customer)}}" method="POST" onsubmit="return confirm('Are you sure you want to delete this customer? This action cannot be undone and will also delete all related orders.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        {{__('Delete Customer')}}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Customer orders section --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">
                        {{__('Customer Orders')}}
                    </h3>
                    @if($orders->isEmpty())
                        <p class="text-center py-4">{{ __('No orders found for this customer.')}}</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{__('Order ID')}}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{__('Date')}}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{__('Total')}}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{__('Status')}}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{__('Payment')}}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{__('Actions')}}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($orders as $order)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">#{{$order->id}}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{$order->created_at->format('M d, Y')}}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{number_format($order->total_amount, 2)}}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($order->status == 'completed')
                                                    bg-green-100 text-green-800
                                                @elseif($order->status == 'cancelled')
                                                    bg-red-100 text-red-800
                                                @elseif($order->status == 'processing')
                                                    bg-blue-100 text-blue-800
                                                @else
                                                    bg-yellow-100 text-yellow-800
                                                @endif
                                            ">
                                            {{ucfirst($order->status)}}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($order->payment_status == 'paid')
                                                    bg-green-100 text-green-800
                                                @else
                                                    bg-yellow-100 text-yellow-800
                                                @endif
                                                ">
                                                {{ucfirst($order->payment_status)}}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{route('admin.orders.show', $order)}}" class="text-indigo-600 hover:text-indigo-900">
                                                {{__('View')}}
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

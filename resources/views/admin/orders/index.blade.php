<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{__('Orders Management')}}
            </h2>
            <a href="{{route('admin.orders.create')}}" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{__('Create New Order')}}
            </a>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($orders->isEmpty())
                        <p class="text-center py-4">{{__('No orders found.')}}</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{__('Order ID')}}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{__('Customer')}}
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
                                                <div class="text-sm font-medium text-gray-900">
                                                    <a href="{{route('admin.customers.show', $order->user_id)}}" class="hover:underline">
                                                        {{$order->user->name}}
                                                    </a>
                                                </div>
                                                <div class="text-sm text-gray-500">{{$order->user->email}}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm  text-gray-900">{{$order->created_at->format('M d, Y')}}</div>
                                                <div class="text-sm  text-gray-500">{{$order->created_at->format('h:i A')}}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm  text-gray-900">{{number_format($order->total_amount, 2)}}</div>
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
                                                    {{ ucfirst($order->payment_status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{route('admin.orders.edit', $order)}}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                    <a href="{{route('admin.orders.show', $order)}}" class="text-blue-600 hover:text-blue-900">View</a>
                                                    <form action="{{route('admin.orders.destroy', $order)}}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this order? This will return stock to inventory.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{$orders->links()}}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

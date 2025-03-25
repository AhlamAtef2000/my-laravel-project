<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Product Details') }}: {{ $product->name }}
            </h2>
            <div class="flex space-x-2">"
                <a href="{{ route('admin.products.edit', $product) }}"
                    class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Edit') }}
                </a>
                <a href="{{ route('admin.products.index') }}"
                    class="px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Back to List') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="md:grid md:grid-cols-3 md:gap-6">
                        <div class="md:col-span-1">
                            <div class="px-4 sm:px-0">
                                <h3 class="text-lg font-medium leading-6 text-gray-900">{{ __('Product Information') }}
                                </h3>
                                <p class="mt-1 text-sm text-gray-600">
                                    {{ __('Detailed information about this product.') }}
                                </p>
                            </div>
                            <div class="mt-5 px-4 sm:px-0">
                                @if ($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                        class="w-full h-auto object-cover rounded-lg shadow-md" />
                                @else
                                    <div class="w-full h-64 bg-gray-200 rounded-lg shadow-md flex items-center justify-center">
                                        <span class="text-gray-500">{{__('No Image Available')}}</span>
                                    </div>
                                @endif

                                <div class="mt-4">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{$product->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}}">
                                        {{$product->active ? 'Active' : 'Inactive'}}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-5 md:mt-0 md:col-span-2">
                            <div class="shadow sm:rounded-md sm:overflow-hidden">
                                <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                                    <div>
                                        <h4 class="text-lg font-medium text-gray-900">{{$product->name}}</h4>
                                        <p class="mt-2 text-3xl font-bold text-gray-900">${{number_format($product->price, 2)}}</p>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500">{{ __('Description')}}</h4>
                                        <p class="mt-1 text-sm text-gray-900">{{$product->description}}</p>
                                    </div>
                                    <div class="grid grid-col-2 gap-4">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-500">{{__('Stock')}}</h4>
                                            <p class="mt-1 text-sm text-gray-900">{{$product->stock}} {{__('units')}}</p>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-500">{{__('Last Updated')}}</h4>
                                            <p class="mt-1 text-sm text-gray-900">{{$product->updated_at->format('M d, Y H:i')}}</p>
                                        </div>
                                    </div>
                                    <div class="grid grid-col-2 gap-4">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-500">{{__('Created At')}}</h4>
                                            <p class="mt-1 text-sm text-gray-900">{{$product->created_at->format('M d, Y H:i')}}</p>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-500">{{__('Product ID')}}</h4>
                                            <p class="mt-1 text-sm text-gray-900">{{$product->id}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6">
                                <form action="{{route('admin.products.destroy', $product)}}" method="POST" onsubmit="return confirm('Are you sure you want to delete  this product? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-4 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        {{__('Delete Product')}}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

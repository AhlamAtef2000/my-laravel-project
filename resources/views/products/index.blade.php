<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Products')}}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($products->isEmpty())
                        <p class="text-center py-8">{{ __('No products available at the moment.')}}</p>
                    @else
                        <div class="grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                            @foreach($products as $product)
                                <div class="group relative">
                                    <div class="w-full min-h-80 bg-gray-200 aspect-w-1 aspect-h-1 rounded-md overflow-hidden group-hover:opacity-75 lg:h-80 lg:aspect-none">
                                        @if($product->image)
                                            <img
                                                src="{{ asset('storage/' . $product->image)}}"
                                                alt="{{ $product->name}}"
                                                class="w-full h-full object-center object-cover lg:w-full lg:h-full"
                                                >
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <span class="text-gray-500">No Image</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="mt-4 flex justify-between">
                                        <div>
                                            <h3 class="text-sm text-gray-700">
                                                <a href="{{ route('products.show', $product)}}">
                                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                                    {{$product->name}}
                                                </a>
                                            </h3>
                                            <p class="mt-1 text-sm text-gray-500">
                                                {{ Str::limit($product->description, 50)}}
                                            </p>
                                        </div>
                                        <p class="text-sm font-medium text-gray-900">
                                            ${{number_format($product->price, 2)}}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-0">
                            {{$products->link()}}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

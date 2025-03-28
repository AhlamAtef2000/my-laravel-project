<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{$product->name}}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="md:flex md:items-start" style="gap: 2%">
                        <div class="md:w-1/2">
                            <div class="w-full h-96 bg-gray-200 rounded-mb overflow-hidden">
                                <div
                                class="w-full bg-gray-200 aspect-square rounded-md overflow-hidden group-hover:opacity-75">
                                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('assets/images/products/default/default.jpg') }}"
                                    alt="{{ $product->name }}" class="w-full h-full object-center object-cover" style="width: 300px">
                            </div>
                            </div>
                        </div>
                        <div class="mt-8 md:mt-0 md:ml-8 md:w-1/2">
                            <h1 class="text-2xl font-bold text-gray-900">{{ $product->name}}</h1>
                            <div class="mt-4">
                                <p class="text-3xl text-gray-900">${{number_format($product->price, 2)}}</p>
                                <p class="mt-4 text-sm text-gray-500">
                                    Availability:
                                    @if($product->stock > 0)
                                        <span class="text-green-600">In Stock ({{$product->stock}} available)</span>
                                    @else
                                        <span class="text-red-600">Out of stock</span>
                                    @endif
                                </p>
                            </div>
                            <div class="mt-6">
                                <h2 class="text-lg font-medium text-gray-900">Description</h2>
                                <div class="mt-2 prose prose-sm text-gray-500">
                                    {{$product->description}}
                                </div>
                            </div>
                            @auth
                                <div class="mt-8">
                                    @if($product->stock > 0)
                                        <form action="{{route('cart.store')}}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{$product->id}}">
                                            <div class="flex items-center mb-4">
                                                <label for="quantity" class="mr-4 text-sm font-medium text-gray-700">Quantity:</label>
                                                <select name="quantity" id="quantity" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                    @for($i = 1; $i <= min(10, $product->stock); $i++)
                                                        <option value="{{$i}}">{{$i}}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                            <button type='submit' class="w-full bg-indigo-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                Add To Cart
                                            </button>
                                        </form>
                                    @else
                                        <button disabled class="w-full bg-gray-400 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white">
                                            Out of Stock
                                        </button>
                                    @endif
                                </div>
                            @else
                                <div class="mt-8">
                                    <a href="{{route('login')}}" class="w-full bg-indigo-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Login to Purchase
                                    </a>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

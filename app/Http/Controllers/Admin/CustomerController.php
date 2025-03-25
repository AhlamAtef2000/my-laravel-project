<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Validator, Hash};

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $customers = User::where('role', 'customer')->latest()->paginate(10);
        return response()->json(['customers' => $customers]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $customer = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => 'customer',
        ]);

        return response()->json(['customer' => $customer], 201);
    }

    public function show(User $customer)
    {
        if ($customer->role !== 'customer') {
            return response()->json(['message' => 'Not a customer'], 404);
        }
        return response()->json(['customer' => $customer]);
    }

    public function update(Request $request, User $customer)
    {
        if ($customer->role !== 'customer') {
            return response()->json(['message' => 'Not a customer'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $customer->id,
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->except('password');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $customer->update($data);

        return response()->json(['customer' => $customer]);
    }

    public function destroy(User $customer)
    {
        if ($customer->role !== 'customer') {
            return response()->json(['message' => 'Not a customer'], 404);
        }

        $customer->delete();

        return response()->json(['message' => 'Customer deleted successfully.']);
    }
}

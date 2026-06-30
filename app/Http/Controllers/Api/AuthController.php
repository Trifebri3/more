<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'whatsapp' => 'required|string|max:20|unique:customers,whatsapp',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Create associated Customer record
        $customer = Customer::create([
            'user_id' => $user->id,
            'full_name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'whatsapp' => $request->whatsapp,
            'membership_status' => 'regular',
            'loyalty_points' => 0,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $this->getUserRole($user),
            ],
            'customer' => $customer
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Kredensial yang diberikan salah.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        $customer = Customer::where('user_id', $user->id)->first();
        $role = $this->getUserRole($user);

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $role,
            ],
            'customer' => $customer
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Berhasil logout'
        ]);
    }

    public function user(Request $request)
    {
        $user = $request->user();
        $customer = Customer::where('user_id', $user->id)->first();
        $role = $this->getUserRole($user);

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $role,
            'customer' => $customer
        ]);
    }

    private function getUserRole(User $user): string
    {
        // Simple mapping based on email or name for development
        if (Str::contains(strtolower($user->email), 'admin')) {
            return 'admin';
        }
        if (Str::contains(strtolower($user->email), 'stylist')) {
            return 'stylist';
        }
        if (Str::contains(strtolower($user->email), 'staff')) {
            return 'staff';
        }
        return 'customer';
    }
}
// Import Str helper just in case
class_exists(\Illuminate\Support\Str::class);

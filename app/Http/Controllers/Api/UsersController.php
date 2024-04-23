<?php

namespace App\Http\Controllers\Api;

use Exception;
use Ramsey\Uuid\Uuid;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ApiHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UsersController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|max:50|unique:users',
                'password' => ['required', 'string', Password::defaults()],
            ]);

            if($validator->fails())
            {
                return ApiHelpers::error($validator->errors(), 'Ada data yang tidak valid!');
            }

            $validated = $validator->validated();

            $validated['password'] = Hash::make($validated['password']);

            User::create($validated);
            event(new Registered($validated));

            $user = User::where('email', $validated['email'])->first();
            $token = $user->createToken('users')->plainTextToken;

            $data = [
                'access_token' => "Bearer $token",
                'user' => $user
            ];

            return ApiHelpers::success($data, 'Berhasil Mendaftarkan Pengguna Baru!');
        } catch (Exception $e) {
            return ApiHelpers::error($e, 'Terjadi Kesalahan');
        }
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string',
                'password' => 'required|string',
            ]);

            if ($validator->fails())
            {
                return ApiHelpers::error($validator->errors(), 'Ada data yang tidak valid!');
            }

            $validated = $validator->validated();

            $user = User::where('email', $validated['email'])->first();

            if (!$user || !Hash::check($validated['password'], $user->password)) {
                return ApiHelpers::error([], 'Data Tidak Ditemukan atau Password Salah!', 401);
            }

            $user->tokens()->delete();
            $token = $user->createToken('users')->plainTextToken;

            $data = [
                'access_token' => "Bearer $token",
                'user' => $user
            ];

            return ApiHelpers::success($data, 'Berhasil Masuk!');
        } catch (\Exception $e) {
            return ApiHelpers::error($e, 'Terjadi Kesalahan');
        }
    }

    public function profile(Request $request)
    {
        try{
            $user = Auth::user();

            if (!$user)
            {
                return ApiHelpers::error([], 'Unauthorized', 401);
            }

            return ApiHelpers::success($user, 'Mohon Simpan Token Anda!');
        } catch (Exception $e) {
            return ApiHelpers::error($e, 'Terjadi Kesalahan');
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user)
            {
                return ApiHelpers::error([], 'Unauthorized', 401);
            }

            $data = $request->user()->currentAccessToken()->delete();

            return ApiHelpers::success($data, 'Token Dihapus!');
        } catch (\Exception $error) {
            return ApiHelpers::error($error, 'Terjadi Kesalahan');
        }
    }
}

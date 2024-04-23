<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Exception;
use App\Models\Devices;
use Illuminate\Http\Request;
use App\Helpers\ApiHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;

class DevicesController extends Controller
{
    public function register(Request $request)
    {
        try{
            $user = User::find($request->input('user_id'));

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:125',
                'category' => 'required|string|max:125',
                'population'=> 'required|string|max:125',
                'status' => 'required|string|max:125',
                'automatic' => 'required|boolean',
                'relay_a' => 'required|boolean',
                'relay_b' => 'required|boolean'
            ]);

            if($validator->fails())
            {
                return ApiHelpers::error($validator->errors(), 'Ada data yang tidak valid!');
            }

            $validated = $validator->validated();

            $existingDevice = Devices::where('name', $validated['name'])->first();

            if ($existingDevice) {
                return ApiHelpers::error([], 'Device dengan nama tersebut sudah terdaftar!', 400);
            }

            $user->devices()->create($validated);
            event(new Registered($validated));

            $devices = Devices::where('name', $validated['name'])->first();
            $token = $devices->createToken('authToken')->plainTextToken;

            $data = [
                'access_token' => "Bearer $token",
                'devices' => $devices
            ];

            return ApiHelpers::success($data, 'Berhasil Mendaftarkan Device Baru!');
        } catch (Exception $e) {
            return ApiHelpers::error($e, 'Terjadi Kesalahan');
        }
    }

    public function renew(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
            ]);

            if ($validator->fails())
            {
                return ApiHelpers::error($validator->errors(), 'Ada data yang tidak valid!');
            }

            $validated = $validator->validated();

            $devices = Devices::where('name', $validated['name'])->first();

            if(!$devices)
            {
                return ApiHelpers::error([], 'Data Tidak Ditemukan atau Password Salah!', 401);
            }

            $devices->tokens()->delete();
            $token = $devices->createToken('authToken')->plainTextToken;

            $data = [
                'access_token' => "Bearer $token",
            ];

            return ApiHelpers::success($data, 'Token di update!');
        } catch (Exception $e) {
            return ApiHelpers::error($e, 'Terjadi Kesalahan');
        }
    }

    public function all(Request $request) {
        try{
            $user = User::find($request->header('user_id'));
            $devices = $user->devices;

            return ApiHelpers::success($devices, 'Berhasil Memperbarui Data Sensor!');
        } catch (Exception $e) {
            return ApiHelpers::error($e, 'Terjadi Kesalahan');
        }
    }


    public function update(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user)
            {
                return ApiHelpers::error([], 'Unauthorized', 401);
            }

            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
                'automatic' => 'required|boolean',
                'relay_a' => 'required|boolean',
                'relay_b' => 'required|boolean'
            ]);

            if ($validator->fails()) {
                return ApiHelpers::error($validator->errors(), 'Ada data yang tidak valid!');
            }

            $validated = $validator->validated();

            $devices = Devices::where('id', $validated['id'])->first();

            if (!$devices) {
                return ApiHelpers::error([], 'Device tidak ditemukan atau tidak memiliki izin!', 404);
            }

            $devices->update([
                'automatic' => $validated['automatic'],
                'relay_a' => $validated['relay_a'],
                'relay_b' => $validated['relay_b']
            ]);

            $data = [
                'id' => $devices->id,
                'automatic' => $devices->automatic,
                'relay_a' => $devices->relay_a,
                'relay_b' => $devices->relay_b,
            ];

            return ApiHelpers::success($data, 'Berhasil Memperbarui Data Sensor!');
        } catch (Exception $e) {
            return ApiHelpers::error($e, 'Terjadi Kesalahan');
        }
    }

    public function details(Request $request)
    {
        try{
            $data = Devices::findOrFail($request->header('device_id'));

            return ApiHelpers::success($data, 'Ini adalah Detail Devices!');
        } catch (Exception $e) {
            return ApiHelpers::error($e, 'Terjadi Kesalahan');
        }
    }

    public function sensor(Request $request)
    {
        try{
            $devices = Auth::user();

            if (!$devices)
            {
                return ApiHelpers::error([], 'Unauthorized', 401);
            }

            $data = [
                'automatic' => $devices->automatic,
                'relay_a' => $devices->relay_a,
                'relay_b' => $devices->relay_b,
            ];

            return ApiHelpers::success($data, 'Ini adalah Detail Devices!');
        } catch (Exception $e) {
            return ApiHelpers::error($e, 'Terjadi Kesalahan');
        }
    }
}

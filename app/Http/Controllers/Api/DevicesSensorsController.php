<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Devices;
use App\Models\DevicesSensors;
use Illuminate\Http\Request;
use App\Helpers\ApiHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DevicesSensorsController extends Controller
{
    public function add(Request $request)
    {
        try {
            $auth = Auth::user();

            if (!$auth)
            {
                return ApiHelpers::error([], 'Unauthorized', 401);
            }

            $devices = Devices::find($request->input('device_id'));

            $dateTime = now();

            $year = $dateTime->year;
            $month = $dateTime->month;
            $day = $dateTime->day;
            $time = $dateTime->toTimeString();

            $validated = [
                'year' => $year,
                'month' => $month,
                'day' => $day,
                'timestamp' => $time,
                'temperature' => $request->input('temperature'),
                'humidity' => $request->input('humidity'),
                'ammonia' => $request->input('ammonia'),
            ];

            $data = $devices->sensor()->create($validated);

            return ApiHelpers::success($data, 'Berhasil mengirim data!');
        } catch (Exception $e) {
            return ApiHelpers::error($e, 'Terjadi Kesalahan');
        }
    }

    public function summary(Request $request)
    {
        try{
            $devices = Devices::find($request->header('device_id'));

            $data = $devices->sensor()->get();

            return ApiHelpers::success($data, 'Berhasil mengambil seluruh data!');
        } catch (Exception $e) {
            return ApiHelpers::error($e, 'Terjadi Kesalahan');
        }
    }

    public function current(Request $request)
    {
        try {
            $devices = Devices::find($request->header('device_id'));

            $data = $devices->sensor()->orderBy('created_at', 'desc')->first();

            return ApiHelpers::success($data, 'Berhasil mengambil terkini data!');
        } catch (Exception $e) {
            return ApiHelpers::error($e, 'Terjadi Kesalahan');
        }
    }
}

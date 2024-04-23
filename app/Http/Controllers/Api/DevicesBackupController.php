<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\DevicesBackup;
use Illuminate\Http\Request;
use App\Helpers\ApiHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Spatie\FlareClient\Api;
class DevicesBackupController extends Controller
{
    public function backup(Request $request)
    {
        try{

            $data = [
                'Notes' => 'FUNGSI INI BELUM BERJALAN',
            ];

            return ApiHelpers::success($data, 'Data di Backup!');
        } catch (Exception $e) {
            return ApiHelpers::error($e, 'Terjadi Kesalahan');
        }
    }

    public function data()
    {
        try{

            $data = [
                'Notes' => 'FUNGSI INI BELUM BERJALAN',
            ];

            return ApiHelpers::success($data, 'Menampilkan seluruh data!');
        } catch (Exception $e) {
            return ApiHelpers::error($e, 'Terjadi Kesalahan');
        }
    }
}

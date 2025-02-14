<?php

namespace App\Services\WorkerService\WorkerLoginService;

use App\Models\Worker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WorkerLoginService
{
    protected $worker;

    public function __construct($model = Worker::class)
    {
        $this->worker = $model;
    }

    function validation($request)
    {
        $validator = Validator::make($request->all(), $request->rules());
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        return $validator;
    }

    function isValidData($data)
    {
        $credentials = $data->only('email', 'password');

        $token = Auth::guard('worker')->attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'in Valid Data',
            ], 401);
        }
        return $token;
    }

    function ckeckStatus($email)
    {
        $worker = $this->worker->whereEmail($email)->first();
        $status = $worker->status;


        return $status;
    }

    protected function createToken($token)
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::guard('worker')->user(),
            'authorisation' => [
                'token' => Auth::guard('worker')->refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    function login($request)
    {
        $data = $this->validation($request);
        $token = $this->isValidData($data);
        if ($this->ckeckStatus($request->email) == 0) {
            return response()->json(['Your Account is not Pending']);
        };
        return $this->createToken($token);
    }

}

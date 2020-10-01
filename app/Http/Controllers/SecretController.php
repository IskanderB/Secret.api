<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Secret;

/**
 * Controller for requests to getting and posting secrets
 *
 * This controller validate data, call model functions,
 * pass data to model, transform data from model
 * for response, send response
 *
 * @author Alexandr Khurtin <axurtin.rep@gmail.com>
 * @version 1.0
 */

class SecretController extends Controller
{
    /**
     *
     * @var Secret;
     */
    private $model;

    public function __construct() {
        $this->model = new Secret();
    }

    /**
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOne(Request $request) : \Illuminate\Http\JsonResponse {
        $rules = [
            'secret_key' => 'required|integer',
            'code' => 'required|string|max:255'
        ];
        $validator = $this->validator([
            'secret_key' => $request->secret_key,
            'code' => $request->code,
        ], $rules);
        if ($validator) {
            return $validator;
        }

        $result = $this->model->getOne($request->secret_key, $request->code);

        if ($result) {
            return response()->json([
                'data' => [
                    'secret' => $result
                ],
            ], 200);
        }
        return response()->json([
                'data' => '',
            ], 404);
    }

    /**
     * Method validate data and call model method insert
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function generate(Request $request) : \Illuminate\Http\JsonResponse {
        $rules = [
            'secret' => 'required|string|max:1000',
            'code' => 'required|string|max:255',
            'time' => 'integer|max:30'
        ];
        $validator = $this->validator($request->all(), $rules);
        if ($validator) {
            return $validator;
        }

        $id = $this->model->create($request->all());
        if ($id) {
            return response()->json([
                'data' => [
                    'secret_key' => $id
                ],
            ], 201);
        }
        else {
            return response()->json([
                'data' => ''
            ], 400);
        }
    }

    /**
     *
     * @param array $data
     * @param array $rules
     * @return boolean|\Illuminate\Http\JsonResponse
     */

    private function validator(array $data, array $rules) {
        $validator = \Validator::make($data, $rules);
        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors(),
            ], 422);
        }
        return false;
    }
}

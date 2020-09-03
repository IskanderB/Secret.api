<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Secret;

class SecretController extends Controller
{
    private $model;
    
    public function __construct() {
        $this->model = new Secret();
    }
    
    public function getOne(Request $request) {
        $rules = [
            'secret_key' => 'requered|integer',
            'code' => 'required|string|max:255'
        ];
        $validator = $this->validator($request->all(), $rules);
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
     * @return \Illuminate\Http\Response
     */
    public function generate(Request $request) {
        $rules = [
            'secret' => 'required|string|max:1000',
            'code' => 'required|string|max:255'
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
     * @return boolean|\Illuminate\Http\Response
     */
    
    private function validator($data, $rules) {
        $validator = \Validator::make($data, $rules);
        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors(),
            ], 400);
        }
        return false;
    }
}

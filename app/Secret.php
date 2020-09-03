<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Secret extends Model
{
    public function create($data) {
        return $this->insertGetId($data);
    }
    
    public function getOne($id, $code) {
       $secret = $this->select('secret', 'code')
               ->where('id', '=', $id)
               ->first();

       if ($secret and $secret->code == $code){
           return $secret->secret;
       }
       return false;
    }
}

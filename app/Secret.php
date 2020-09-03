<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Secret extends Model
{
    public function create($data) {

        $data['created_at'] = time();
        $data['secret'] = encrypt($data['secret']);
        $data['code'] = \Hash::make($data['code']);
        
        return $this->insertGetId($data);
    }
    
    public function getOne($id, $code) {
       $secret = $this->select('secret', 'code', 'created_at', 'time')
               ->where('id', '=', $id)
               ->first();
       
       if (!$secret) {
           return false;
       }
       
       $time_c = 24*3600;
       $time_c = 60;
       $endTime = $secret->created_at->timestamp + $time_c*$secret->time;
       
       if (\Hash::check($code, $secret->code) and $endTime > time()){
           $this->where('id', '=', $id)
                   ->delete();
           return decrypt($secret->secret);
       }
       return false;
    }
}

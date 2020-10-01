<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Model for insert and select from/to secrets table
 * 
 * This model insert, select rows to DB, 
 * encrypt and hash data to insert to DB,
 * check selected data to match time limits and
 * hash rules
 * 
 * @author Alexandr Khurtin <axurtin.rep@gmail.com>
 * @version 1.0
 */

class Secret extends Model
{
    /**
     * Create row in DB (secrets) and 
     * return id by new row
     * 
     * @param array $data
     * @return int
     */
    public function create(array $data) : int {

        $transformed = $this->transformData($data);
        return $this->insertGetId($transformed);
    }
    
    /**
     * 
     * @param array $data
     * @return array
     */
    
    private function transformData(array $data) : array {
        $data['created_at'] = time();
        $data['secret'] = encrypt($data['secret']);
        $data['code'] = \Hash::make($data['code']);
        return $data;
    }
    
    /**
     * Getting row from DB (secrets)
     * by id, check conformity code and
     * time limits
     * 
     * @param int $id
     * @param string $code
     * @return boolean|string
     */
    
    public function getOne(int $id, string $code) {
       $secret = $this->select('secret', 'code', 'created_at', 'time')
               ->where('id', '=', $id)
               ->first();
       if (!$secret) {
           return false;
       }
             
       if (\Hash::check($code, $secret->code) and $this->checkTime($secret->time, $secret->created_at->timestamp)){
           $this->where('id', '=', $id)
                   ->delete();
           return decrypt($secret->secret);
       }
       return false;
    }
    
    /**
     * This method check conformity time limits getting this row.
     * Method add $created_at (row was created : unix) and 
     * time (count days after creating are allowed get this row).
     * Next this compare current date : unix and sum : unix.
     * 
     * @param type $period
     * @param int $created_at
     * @return bool
     */
    
    private function checkTime($period, int $created_at) : bool {
        if ($period) {
           $time_c = 24*3600;
           $endTime = $created_at + $time_c*$period;
           return $endTime > time();
       }
       else {
           return true;
       }
    }
}

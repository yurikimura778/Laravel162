<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
   protected $guarded = array('id');
  
     public static $rules = array(
        'name' => 'required',
        'gender' => 'required',
    );
       // 以下を追記
    // prlfileモデルに関連付けを行う
    public function profile_histories()
    {
      return $this->hasMany('App\ProfileHistory');  

    }
    
 
}

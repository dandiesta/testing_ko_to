<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPass extends Model {

    protected $table = 'user_pass';

    public function application() {
        return $this->belongsToMany('App\Application', 'application_owner', 'owner_email', 'app_id');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    protected $table = 'user_info';
    protected $primaryKey = 'user_info_id';

    public $timestamps = false;
}

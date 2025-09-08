<?php

namespace App\Models;
use Helpers\BaseModel;

class User extends BaseModel {
    
    // Protected table Users
    public $table = 'users';
    protected $primaryKey = 'users_id';
}
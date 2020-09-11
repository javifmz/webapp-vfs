<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model {

  protected $table = 'user';
  protected $hidden = array('password', 'token');
  public $timestamps = false;
}

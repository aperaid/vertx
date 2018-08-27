<?php

namespace App\Model\Settings;

use Illuminate\Database\Eloquent\Model;

class ControllerDevice extends Model
{
  protected $primaryKey = 'iid';
  public $incrementing = false;
  public $timestamps = false;
}

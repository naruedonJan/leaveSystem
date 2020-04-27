<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events';
    protected $fillable = ['uid', 'reason', 'type', 'status', 'start_date', 'end_date', 'note'];
}

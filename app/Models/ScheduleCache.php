<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleCache extends Model
{
    protected $table = 'schedule_cache';

    protected $fillable = [
        'room_code',
        'course_code',
        'course_name',
        'lecturer_name',
        'schedule_date',
        'start_time',
        'end_time',
    ];
}
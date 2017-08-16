<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function attendanceType(){
        return $this->belongsTo(AttendanceType::class);
    }
}

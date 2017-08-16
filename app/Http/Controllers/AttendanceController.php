<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\AttendancePasswords;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function store($password){
        $password = AttendancePasswords::where('password', $password)->first();
        $initial = $password->created_at;
        $final   = $password->updated_at;
        $diff    = $initial->diff($final)->format("%H:%I:%S");

        $attendance = new Attendance();
        $attendance->user_id = Auth::user()->id;
        $attendance->attendance_password_id = $password->id;
        $attendance->attendance_type_id = 1;
        $attendance->skipped = false;
        $attendance->wait_time = $diff;
        $attendance->save();
    }

    public function updateInEnd($id, $diff){
        $attendance = Attendance::where('attendance_password_id', $id)->first();
        $attendance->attendance_time = $diff;
        $attendance->save();
    }
}

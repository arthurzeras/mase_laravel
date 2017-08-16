<?php

namespace App\Http\Controllers;

use App\AttendancePasswords;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct(){
        $this->middleware('auth', ['except' => ['show']]);
    }

    public function index(){
        //get last passwords to show in home at the load page.
        $passwords = new AttendancePasswordsController();
        $p = $passwords->getPasswords();
        return view('home', compact('p'));
    }

    public function show(){
        return view('call_screen');
    }
}

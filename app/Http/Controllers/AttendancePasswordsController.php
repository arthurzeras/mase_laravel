<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\AttendanceType;
use App\Events\ShowPasswordCalled;
use App\Events\UpdateNextPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\AttendancePasswords;

class AttendancePasswordsController extends Controller
{
    public function __construct(){
        $this->middleware('auth', ['except' => ['request', 'show', 'newEvent', 'getPasswords']]);
    }

    /**********************************
    * show view to request a password *
    ***********************************/
    public function show(){
        $at = AttendanceType::all();
        return view('/request_password', compact('at'));
    }

    /*************************
     * ask/request password *
     ************************/
    public function request(){
        $ap = AttendancePasswords::all();

        //verify if is preferential
        if(request('type') == 'on'){
            $type = "Preferential";
        }else{
            $type = "Normal";
        }

        //if has zero passwords requested insert as first password
        if(count($ap) == 0){
            $this->store(1, $type);
            $this->newEvent();
            return redirect('/request_password')
                ->with('message', 'O número da sua senha é: 1')
                ->with('password', 1);
        }else{
            $last = $ap->last()->password;
            $last += 1;
            $this->store($last, $type);
            $this->newEvent();
            return redirect('/request_password')
                ->with('message', 'O número da sua senha é: ' . $last)
                ->with('password', $last);
        }
    }

    /***********************************
     * save a new password in database *
     ***********************************/
    public function store($password, $pt){
        $ap = new AttendancePasswords();
        $ap->password = $password;
        $ap->status = 'Waiting';
        $ap->password_type = $pt;
        $ap->save();
    }

    /************************************************
     * registry event to show passwords in the view *
     ************************************************/
    public function newEvent(){
        $passwords = $this->getPasswords();
        event(new UpdateNextPasswords($passwords));
    }

    /***********************
     * bring all passwords *
     ***********************/
    public function getPasswords(){
        $data = AttendancePasswords::all('password', 'id')->sortByDesc('id')->take(3);

        foreach($data as $i => $v){
            $items[] .= $v['password'];
        }

        if($items == null){
            $data = 'Ainda não há nenhuma senha.';
        }else{
            $data = implode(', ', $items);
        }

        return $data;
    }


    /*****
     *****
     *****  AT THIS POINT, METHODS IS USED FROM ATTENDANTS
     *****
     *****/

    /*****************
     * call password *
     *****************/
    public function call(){
        if($this->isAvailable() == false){
            return redirect('/')->with('message_err', 'Não há nenhuma senha para ser chamada!');
        }else{
            $ap = AttendancePasswords::where('id', $this->findByType())->first();
            $ap->user_id = Auth::user()->id;
            $ap->status = 'Attending';
            $ap->save();

            //call event to show in call screen
            event(new ShowPasswordCalled($ap->password, $this->findByStatus()));

            //save attendance in attendances table
            $at = new AttendanceController();
            $at->store($ap->password);

            return redirect('/')->with('message', 'Você chamou a senha ' . $ap->password)
                                ->with('button', 'Chamar '. $ap->password . ' novamente');
        }
    }

    /***********************
     * call password again *
     ***********************/
    public function callAgain(){
        $ap = AttendancePasswords::where([
            ['status', 'Attending'],
            ['user_id', Auth::user()->id],
        ])->first();
        $ap->updated_at = date("Y-m-d H:m:s");
        $ap->save();
        return redirect('/')
            ->with('message', 'A senha ' . $ap->password . ' foi chamada novamente')
            ->with('button', 'Chamar ' . $ap->password . ' novamente');
    }

    /***********************************
     * verify if has passwords to call *
     ***********************************/
    public function isAvailable(){
        if(count(AttendancePasswords::all()) == 0){
            return false;
        }else if(count(AttendancePasswords::where('status', 'Waiting')->get()) == 0){
            return false;
        }else{
            return true;
        }
    }

    /************************
     * get password by type *
     ************************/
    public function findByType(){
        $ap = AttendancePasswords::where([
            ['status', 'Waiting'],
            ['password_type', 'Preferential'],
        ])->first();

        //get normal
        if(count($ap) == 0){
            $ap = AttendancePasswords::where([
                ['status', 'Waiting'],
                ['password_type', 'Normal'],
            ])->first();
            return $ap->id;
        }else{
            //get preferential
            return $ap->id;
        }
    }

    /********************************
     * get passwords already called *
     ********************************/
    public function findByStatus(){
        $data = AttendancePasswords::where('status', 'ended')
                            ->orWhere('status', 'attending')
                            ->get()->sortBy('update_at')->take(6);
        $passwords = array();

        foreach ($data as $d){
            $passwords[] .= $d->password;
        }

        $passwords = implode(",", $passwords);

        return $passwords;
    }


    /*******************
     * ends attendance *
     *******************/
    public function end(){
        $ap = AttendancePasswords::where([
            ['status', 'Attending'],
            ['user_id', Auth::user()->id],
        ])->first();
        $initial = $ap->updated_at;
        $ap->status = 'Ended';
        $ap->save();
        $final = AttendancePasswords::where('id', $ap->id)->first(['updated_at']);
        $diff = $initial->diff($final->updated_at)->format("%H:%I:%S");
        $ac = new AttendanceController();
        $ac->updateInEnd($ap->id, $diff);

        return redirect('/')->with('message', 'Atendimento finalizado.');
    }

    /*****************
     * skip password *
     *****************/
    public function skip(){
        $ap = AttendancePasswords::where([
            ['status', 'Attending'],
            ['user_id', Auth::user()->id],
        ])->first();
        $ap->status = 'Ended';
        $ap->save();
        return redirect('/')->with('message', 'Você pulou a senha '.$ap->password);
    }
}

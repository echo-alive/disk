<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    //
    public function index()
    {
        if (empty(session('userSession'))) {
            return view('login');
        } else {
            return redirect('/');
        }
    }

    public function login(Request $request)
    {
        $account = $request->get('account');
        $password = md5($request->get('password'));
        $res = DB::select('select * from netdisk_users where (username = ? or phone = ? or email = ? ) and password=?', [$account, $account, $account, $password]);
        if (empty($res)) {
            $data['msg'] = false;
            $data['link'] = '';
            return $data;
        } else {
            DB::table('netdisk_users')->where('id', $res[0]->id)->update(['last_login_time' => date('Y-m-d H:i:s'), 'last_login_ip' => $_SERVER['SERVER_ADDR']]);
            session()->put('userSession', $res[0]);
            $data['msg'] = true;
            $data['link'] = "/";
            return $data;
        }

    }

    public function logOut()
    {
        session()->forget('userSession');
        return redirect('/login');
    }
}

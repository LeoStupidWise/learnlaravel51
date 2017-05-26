<?php

namespace App\Http\Controllers\Auth;

use App\Service\AuthService;
use App\Service\Common\CommonService;
use App\User;
use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    protected $redirecPath = '/dashboard';
    protected $loginPath = '/login';

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function getLogin()
    {
        return view("auth.login");
    }

    public function getRegister()
    {
        return view('auth.register');
    }

    public function postRegister(Request $request)
    {
        $validator    =  Validator::make($request->all(),[
            "name"   => "required|unique:user,name",
            "password" => "confirmed"
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors([
                "message"    => "验证信息出错",
            ]);
        }
        $ath_svc      =  new AuthService();
        $pwd_encrypt  =  $ath_svc->pwdEncrypt($request->get('password'));
        $user_data    =  [
            "name" => $request->get('name'),
            "email" => $request->get('email'),
            'password' => $pwd_encrypt,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        User::create($user_data);
    }

    public function postLogin(Request $request)
    {
        $email       =  $request->get('email');
        $user_rcd    =  User::where('email', $email)->first();
        $com_svc     =  new CommonService();
        if (!$user_rcd) {
            echo $com_svc->jsAlert("用户不存在");
            return;
//            return redirect() -> back();
        }
        $pwd         =  $request->get('password');
        $ath_svc     =  new AuthService();
        if ($ath_svc->pwdEncrypt($pwd) != $user_rcd->password) {
            echo $com_svc->jsAlert("密码错误");
            return;
//            return redirect() -> back();
        }
    }
}

<?php

namespace App\Http\Controllers\API;

use Auth;
use Validator;

use App\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Auth\Events\Verified;

class UserController extends Controller
{
    use VerifiesEmails;
    public $successStatus = 200;
    /**
    * login api
    *
    * @return \Illuminate\Http\Response
    */
    public function login()
    {
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')]))
        {
            $user = Auth::user();
            if($user->email_verified_at !== NULL)
            {
                $success['message'] = "Login successfull";
                return response()->json(['success' => $success], $this->successStatus);
            }
            else
            {
                return response()->json(['error'=>'Please Verify Email'], 401);
            }
        }
        else
        {
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }

    /**
    * Register api
    *
    * @return \Illuminate\Http\Response
    */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails())
            return response()->json([‘error’=>$validator->errors()], 401);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);

        $user->sendApiEmailVerificationNotification();

        $success['message'] = 'Please confirm yourself by clicking on verify user button sent to you on your email';

        return response()->json(['success'=>$success], $this->successStatus);
    }

    /**
    * details api
    *
    * @return \Illuminate\Http\Response
    */
    public function details()
    {
        $user = Auth::user();

        if(!$user)
            return response()->json(['error' => 'Please log in'], 401);

        return response()->json(['success' => $user], $this->successStatus);
    }
}
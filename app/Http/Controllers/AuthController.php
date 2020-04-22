<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //

    public function login(Request $request){

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){

                //im using Passport Api Auth using this method
                $user=Auth::user();
                 $success['token'] = $user->createToken('appToken')->accessToken;
           //After successfull authentication, notice how I return json parameters
                        return response()->json([
                        'status' => true,
                        'token' => $success,
                        'user' => $user
                    ]);
                    } else {
                //if authentication is unsuccessfull, notice how I return json parameters
                    return response()->json([
                        'status' => false,
                        'message' => 'Invalid Email or Password',
                    ], 401);
                    }

    }
}














// ublic function login(Request $request){
//          if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
//             $user = Auth::user();
//             $success['token'] = $user->createToken('appToken')->accessToken;
//            //After successfull authentication, notice how I return json parameters
//             return response()->json([
//               'status' => true,
//               'token' => $success,
//               'user' => $user
//           ]);
//         } else {
//        //if authentication is unsuccessfull, notice how I return json parameters
//           return response()->json([
//             'status' => false,
//             'message' => 'Invalid Email or Password',
//         ], 401);
//         }
//     }

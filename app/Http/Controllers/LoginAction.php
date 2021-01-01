<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Firebase\Auth\Token\Exception\InvalidToken;
use Kreait\Firebase\Factory;


class LoginAction extends Controller
{

    public function __invoke(Request $request){
        $factory = (new Factory)->withServiceAccount( config('app.FIREBASE_CREDENTIALS'));

        $auth = $factory->createAuth();
        $idTokenString = $request->input('idToken');
        
        try {
    
            $verifiedIdToken = $auth->verifyIdToken($idTokenString);
        } catch (\InvalidArgumentException $e) {
        
            return response()->json([
                'message' => 'Unauthorized - Can\'t parse the token: ' . $e->getMessage()
            ], 401);        
            
        } catch (InvalidToken $e) {
            
            return response()->json([
                'message' => 'Unauthorized - Token is invalide: ' . $e->getMessage()
            ], 401);
            
        }

        $uid = $verifiedIdToken->claims()->get('sub');

        $firebase_user = $auth->getUser($uid);

        $user = \App\User::firstOrCreate(
                    ['firebase_uid' => $uid],
                    ['name' => $firebase_user->displayName]
                );

        $token = $user->createToken('example_token')->accessToken;

        return response()->json([
            'uid' => $uid,
            'user' => $firebase_user->displayName,
            'token' => $token,
        ]);
    }
}

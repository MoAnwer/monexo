<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use PDOException;

class AuthController extends Controller
{
    protected $channel;

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user && $user->tokens()->count() > 0) {
                return response(['status' => 403, 'message' => 'failed', 'error' => 'User already has an active token']);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response(['access_token' => $token, 'token_type' => 'Bearer']);
        }

        return response()->json(['status' => 401, 'message' => 'Invalid credentials'], 401);
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
            return response()->json(['message' => 'Logged out successfully']);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }


    public function register(Request $request) {
        $credentials = $request->validate([
            'name'  => 'string|required',
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);
        $credentials['password'] = bcrypt($request->password);

        
        try {
            $user = User::create($credentials);
            $token = $user->createToken('auth_token')->plainTextToken;

            Log::stack(['stack' => $this->channel])->info("new user ", ['data' => $request->except('password')]);

           return response(['user' => $user, 'access_token' => $token, 'token_type' => 'Bearer'], 200);

        } catch(PDOException $e) {
            
            if($e->getCode() == 23000) {
                return response(['status' => 404, 'message' => 'faild', 'error' => 'email is already exist !'], 404);
            }
        } catch(Exception $e) {

            return response(['status' => 404, 'message' => 'faild', 'error' => $e->getMessage()], 404);
            
        }
    }
}

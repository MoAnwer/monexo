<?php

namespace App\Http\Controllers;

use PDOException;
use Exception;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function profile() {return response(['status' => 200, 'message' => 'success', 'data' => auth()->user()]);}

    public function updateProfile() {
        try {
            $validateData = request()->validate([
                'name' => 'string|min:3',
            ]);
            
            $user = auth()->user();
            $user->update($validateData);
            return response(['status' => 200, 'message' => 'success', 'data' => $user], 200);
        } catch (PDOException $e) {
            return response(['status' => 404, 'message' => 'failed', 'error' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response(['status' => 404, 'message' => 'failed', 'error' => $e->getMessage()], 404);
        }
    }

    public function deleteAccount() {
        try {
            $validateData = request()->validate([
                'password' => 'string|required',
            ]);
            
            $user = auth()->user();
            if(Hash::check($validateData['password'], $user->password)) {
                // delete tokens
                $user->tokens()->delete();
                // delete user
                $user->delete();

                return response(['status' => 200, 'message' => 'success', 'data' => 'user deleted successful'], 200);
            }
        } catch (PDOException $e) {
            return response(['status' => 404, 'message' => 'failed', 'error' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response(['status' => 404, 'message' => 'failed', 'error' => $e->getMessage()], 404);
        }
    }

    public function resetPassword()
    {
        try {

            $validateData = request()->validate([
                'old_password'      => 'string|required',
                'new_password'      => 'confirmed'
            ]);

            $user = auth()->user();

            if(Hash::check($validateData['old_password'], $user->password)) {
                $user->update(['password' => bcrypt($validateData['new_password'])]);
                return response(['status' => 200, 'message' => 'success', 'data' => 'password reset successful'], 200);
            } 

            return response(['status' => 404, 'message' => 'failed', 'error' => 'Incorrect password !'], 404);

        } catch (Exception $e) {
            return response(['status' => 404, 'message' => 'failed', 'error' => $e->getMessage()], 404);
        }
    }
}

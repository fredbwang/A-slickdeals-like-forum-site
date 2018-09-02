<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use PHPUnit\Framework\Constraint\Exception;

class RegistrationController extends Controller
{
    /**
     * confirm a user's email through confirmation token
     *
     * @return Response
     */
    public function confirm()
    {
        $user = User::where('confirmation_token', request('token'))->first();
        
        if (!$user) {
            return redirect(route('threads'))->with('flash', [
                'message' => 'unknown token',
                'type' => 'warning'
            ]);
        }

        if (auth()->check() && auth()->user()->id != $user->id) auth()->logout();

        $user->confirm();

        return redirect(route('threads'))->with('flash', [
            'message' => 'Your email address is validated!'
        ]);

    }
}

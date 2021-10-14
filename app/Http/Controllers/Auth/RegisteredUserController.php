<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
// use Notification;
// use App\Notifications\ReferralNotification;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        if ($request->has('ref')) {
            session(['referrer' => $request->query('ref')]);
        }

        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'unique:users', 'alpha_dash', 'min:3', 'max:30'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $referrer = User::where('username', session()->pull('referrer'))->first();

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'referrer_id' => $referrer ? $referrer->id : null,
            'referral_token' => rand(),
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        // if ($user->referrer !== null) {

        //     Notification::send($referrer, new ReferralNotification($user));
        // }

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}

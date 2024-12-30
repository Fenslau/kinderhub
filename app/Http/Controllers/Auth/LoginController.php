<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function redirectToProvider(Request $request)
    {
        if ($this->checkDriver($request))
            return Socialite::driver($request->driver)->redirect();
        else return redirect()->route('home');
    }

    public function handleProviderCallback(Request $request)
    {
        try {
            if ($this->checkDriver($request)) {
                $socialUser = Socialite::driver($request->driver)->user();
                if (empty($socialUser->email)) {
                    session()->flash('error', 'Нет емэйла! Аккаунт вашей соцсети не содержит поля email (или он отключен для показа). В этом случае авторизоваться не получится');
                    return redirect()->route('login');
                } else {
                    $user = null;
                    DB::transaction(function () use ($socialUser, &$user) {
                        $user = User::where('email', $socialUser->email)->first();
                        if ($user) {
                            $user->update(['name' => $socialUser->getName()]);
                            if (!Str::startsWith($user->profile?->image, 'user-images')) {
                                $user->profile()->update(['image' => $socialUser->getAvatar()]);
                            }
                        } else {
                            $user = User::create([
                                'name' => $socialUser->getName(),
                                'email' => $socialUser->getEmail(),
                                'email_verified_at' => now(),
                                'password' => bcrypt(Str::random(12))
                            ]);
                            $user->profile()->create(['image' => $socialUser->getAvatar()]);
                        }
                    });
                    Auth::login($user, true);
                    session()->regenerate();
                    return redirect()->route('home');
                }
            } else return redirect()->route('home');
        } catch (\Exception $e) {
            Log::warning($e->getMessage());
            return redirect()->route('login');
        }
    }

    protected function checkDriver(Request $request)
    {
        if (!empty($request->driver) && in_array($request->driver, explode(',', env('SOCIALITE_DRIVERS'))))
            return true;
    }
}

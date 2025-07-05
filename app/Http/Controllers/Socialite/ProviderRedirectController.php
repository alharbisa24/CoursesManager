<?php

namespace App\Http\Controllers\Socialite;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class ProviderRedirectController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(String $provider)
    {
        if(!in_array($provider, ['github', 'google'])) {
            return redirect(route('auth.login'))->withErrors([
                'provider'=> 'invalid provider',
            ]);
        }
        try {
            return Socialite::driver($provider)->redirect();
        }catch (\Exception $exception){
            Log::error($exception->getMessage());
            return redirect(route('auth.login'))->withErrors([
                'provider'=> 'invalid provider',
            ]);
        }
    }
}

<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
use Cookie;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
          "Test/*","api/*", "auth/login"
    ];

    /**
	 * Determine if the session and input CSRF tokens match.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return bool
	 */
	protected function tokensMatch($request)
	{
	    $token = $request->input('_token') ? $request->input('_token') : Cookie::get('XSRF-TOKEN') ;
    
	    return $request->session()->token() == $token;
	}
}

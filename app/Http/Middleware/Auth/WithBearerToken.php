<?php

namespace App\Http\Middleware\Auth;

use Closure;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use App\Exceptions\PersonalRuntimeException;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\ResourceNotFoundException;

class WithBearerToken
{
    /**
     * The guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(AuthFactory $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $token = $request->headers->get('Authorization');
        if(strlen($token) > 0) {
            $tokenArr = explode(' ', $token);
            $token = end($tokenArr);
        }

        try {
            $user = JWT::decode($token, config('auth.auth_jwt_secret_key'), ['HS256']);
            $request->_user_ = User::findOrFail($user->user->id);
        } catch(ExpiredException $ex) {
            throw new PersonalRuntimeException($ex->getMessage());
        } catch(ModelNotFoundException $ex) {
            throw new ResourceNotFoundException(__('users.errors.not_found'));
        }

        return $next($request);
    }
}

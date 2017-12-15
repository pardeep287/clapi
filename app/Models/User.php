<?php

namespace App\Models;

use App\Helpers\VideoconSms;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use App\Events\UserRegistered;
use App\Models\UserVerification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use App\Jobs\ProcessVerificationEmails;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Exceptions\InvalidPasswordException;
use App\Http\Controllers\Api\AuthController;
use App\Exceptions\PersonalRuntimeException;
use App\Http\Resources\User as UserResource;
use App\Exceptions\ResourceNotFoundException;
use App\Http\Controllers\Api\UsersController;
use Illuminate\Validation\ValidationException;
use App\Exceptions\PersonalValidationException;
use App\Exceptions\InvalidVerificationCodeException;
use App\Http\Controllers\Api\Admin\CountriesController;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class User extends Model
{
    use Notifiable;

    protected $fillable = [
        'phone_number',
        'referral_code',
        'country_id',
        'referred_by',
    ];
    protected $hidden = ['password', 'country_id', 'state_id', 'city_id'];
    
    // eloquent events
    protected $dispatchesEvents = [
        'created' => UserRegistered::class,
    ];

    /**
     * relations
     */
    public function country() {
        return $this->belongsTo('App\Models\Country', 'country_id', 'id');
    }

    public function state() {
        return $this->belongsTo('App\Models\State', 'state_id', 'id');
    }

    public function city() {
        return $this->belongsTo('App\Models\City', 'city_id', 'id');
    }

    public function referrer() {
        return $this->hasOne(User::class, 'id', 'referred_by');
    }

    public function verificationCodes() {
        return $this->hasMany(UserVerification::class, 'user_id', 'id');
    }


    public function setReferralCodeAttribute($value) 
    {
        $this->attributes['referral_code'] = \strtoupper($value);
    }


    /**
     * Undocumented function
     *
     * @param AuthController $controllerInstance
     * @param Request $request
     * @return void
     */
    public function check(AuthController $controllerInstance, Request $request) 
    {

        try {
            $controllerInstance->validate($request, [
                'phone_number' => 'required|regex:/^[0-9]{10}$/',
                'password' => 'required'
            ]);
        } catch(ValidationException $ex) {
            throw new PersonalValidationException($ex->getMessage(), $ex->errors());
        }
        
        $phoneNumber = $request->phone_number;
        $password = $request->password;

        try {
            $user = self::with(['country', 'state', 'city'])
                ->where('phone_number', $phoneNumber)
                ->firstOrFail();
            
            if (!Hash::check($password, $user->password)) {
                throw new InvalidPasswordException(__("users.errors.invalid_password"));
            }

            $request->_tokens_ = $user->generateTokens();
        } catch(ModelNotFoundException $ex) {
            throw new ResourceNotFoundException(__("users.errors.not_found"));
        }
        return $user;
    }


    public function appAuthenticate(AuthController $controllerInstance, Request $request) 
    {
        try {
            $controllerInstance->validate($request, [
                'phone_number' => 'required|regex:/^[0-9]{10}$/',
                'country_id' => 'required|regex:/^[0-9]+$/|exists:countries,id',
                'referral_code' => 'nullable',
            ]);
        } catch(ValidationException $ex) {
            throw new PersonalValidationException($ex->getMessage(), $ex->errors());
        }

        
        try {

            $referrer = self::where('referral_code', $request->referral_code)->first();

            $user = self::firstOrCreate([
                "phone_number" => $request->phone_number
            ], [
                "referral_code" => \substr(md5(str_random(20)), 0, 6),
                "country_id" => $request->country_id,
                "referred_by" => ($referrer && isset($referrer->id)) ? $referrer->id : 0
            ]);


            $user->load(['country', 'state', 'city']);

            /**
             * generate jwt tokens and storing in request object so that it can be used
             * in resource class later
             */
            $request->_tokens_ = $user->generateTokens();

            //If User was Fetched,Event will be fired
            if (!$user->wasRecentlyCreated) {
                event(new UserRegistered($user));
            }
        } catch(QueryException $ex) {
            throw new PersonalRuntimeException(__("common.errors.invalid_query"));
        }

        return $user;
    }



    public function verification(AuthController $controllerInstance, Request $request) 
    {
        try {
            $controllerInstance->validate($request, [
                'phone_number' => 'required|regex:/^[0-9]{10}$/',
                'code' => 'required'
            ]);
        } catch(ValidationException $ex) {
            throw new PersonalValidationException($ex->getMessage(), $ex->errors());
        }

        try {
            $user = self::where('phone_number', $request->phone_number)
                ->firstOrFail();
            
            $verificationInstance = $user->verificationCodes()
                ->where('code', $request->code)
                ->first();

            if(!$verificationInstance) {
                throw new InvalidVerificationCodeException(__("users.errors.invalid_verification_code"));
            }

            $user->is_verified = 1;
            $user->is_active = 1;
            $user->saveOrFail();

            $verificationInstance->delete();
        } catch(ModelNotFoundException $ex) {
            throw new ResourceNotFoundException(__("users.errors.not_found"));
        } catch(QueryException $ex) {
            throw new PersonalRuntimeException($ex->getMessage());
        }

        return $user;
    }


    /**
     * Undocumented function
     *
     * @param UsersController $controllerInstance
     * @param Request $request
     * @return void
     */
    public function fetch(Controller $controllerInstance, Request $request) 
    {
        $r = [];
        $r["data"] = self::all();
        return $r;
    }


    /**
     * Undocumented function
     *
     * @param UsersController $controllerInstance
     * @param Request $request
     * @param boolean $id
     * @return void
     */
    public function add(UsersController $controllerInstance, Request $request, $id = false) 
    {
        $isEdit = false;
        if ($id) $isEdit = true;

        $r = [];
        $r["data"] = null;

        try {
            $controllerInstance->validate($request, [
                'name' => 'required',
                'phone_number' => 'required|regex:/^[0-9]{10}$/',
                'country_id' => 'required|regex:/^[1-9]\d*$/',
                'email_address' => 'nullable|email',
                'password' => 'required|confirmed',
                'referral_code' => 'nullable',
            ]);
        } catch(ValidationException $ex) {
            throw new PersonalValidationException($ex->getMessage(), $ex->errors());
        }

        if ($isEdit) {
            $model = self::findOrFail($id);
        } else {
            $model = new User;
            $referrer = self::where('referral_code', $request->referral_code)->first();
        }
        $model->name = $request->name;
        $model->phone_number = $request->phone_number;
        $model->password = Hash::make($request->password);
        if(!empty($request->email_address)) {
            $model->email_address = $request->email_address;
        }
        $model->country_id = $request->country_id;
        $model->referral_code = \substr(md5(str_random(20)), 0, 6);
        if (!$isEdit && $referrer && isset($referrer->id)) {
            $model->referred_by = $referrer->id;
        }

        try {
            $model->saveOrFail();
            $request->_tokens_ = $model->generateTokens();
        } catch(QueryException $ex) {
            throw new PersonalRuntimeException($ex->getMessage());
        }

        return $model;
    }


    /**
     * Undocumented function
     *
     * @param UsersController $controllerInstance
     * @param Request $request
     * @param [type] $id
     * @return void
     */
    public function fetchFirst(UsersController $controllerInstance, Request $request, $id)
    {
        $r = [];
        $r["data"] = null;

        try {
            $r["data"] = self::findOrFail($id);
        } catch(QueryException $ex) {
            throw new PersonalRuntimeException(__("common.errors.invalid_query"));
        } catch(ModelNotFoundException $ex) {
            throw new ResourceNotFoundException(__("countries.errors.not_found"));
        }
        
        return $r;
    }


    /**
     * Undocumented function
     *
     * @param UsersController $controllerInstance
     * @param [type] $id
     * @return void
     */
    public function remove(UsersController $controllerInstance, $id)
    {
        $r = [];
        $r["data"] = null;

        try {
            $r["data"] = self::findOrFail($id);
            $r["data"]->delete();
        } catch(QueryException $ex) {
            throw new PersonalRuntimeException(__("common.errors.invalid_query"));
        } catch(ModelNotFoundException $ex) {
            throw new ResourceNotFoundException(__("countries.errors.not_found"));
        }
        
        return $r;
    }


    private function generateTokens()
    {
        // generate jwt token
        $secret = config('auth.auth_jwt_secret_key');
        $payload = [];
        $payload['user'] = self::find($this->id)->toArray();
        $payload['iat'] = time();
        $payload['exp'] = time() + (3600*24*30); // 30 days

        $token = [];
        $token['access_token'] = JWT::encode($payload, $secret);
        $token['expires_on'] = $payload['exp'];

        // refresh token
        $payload["exp"] = time() + 2592000; // 1 month
        $token['refresh_token'] = JWT::encode($payload, $secret);

        return $token;
    }

    public function editDetailsAuth(AuthController $controllerInstance, Request $request)
    {
        try {
            $controllerInstance->validate($request, [
                'name' => 'required',
            ]);
        } catch(ValidationException $ex) {
            throw new PersonalValidationException($ex->getMessage(), $ex->errors());
        }

        try {

            $user = self::with(['country', 'state', 'city'])
            ->where('id', $request->id)
                ->firstOrFail();

            $user->name = $request->name?$request->name:$user->name;
            //$user->image_url = $request->image_url;
            $user->country_id = $request->country_id?$request->country_id:$user->country_id;
            $user->state_id = $request->state_id?$request->state_id:$user->state_id;
            $user->city_id = $request->city_id?$request->city_id:$user->city_id;
            $user->saveOrFail();

            $request->_tokens_ = $user->generateTokens();

        } catch(ModelNotFoundException $ex) {
            throw new ResourceNotFoundException(__("users.errors.not_found"));
        } catch(QueryException $ex) {
            throw new PersonalRuntimeException($ex->getMessage());
        }

        return $user;
    }

    public function getJbCoins(AuthController $controllerInstance, Request $request)
    {
        try {
            $controllerInstance->validate($request, [
                'id' => 'required',
            ]);
        } catch(ValidationException $ex) {
            throw new PersonalValidationException($ex->getMessage(), $ex->errors());
        }

        try {

            $user = self::where('id', $request->id)
                ->firstOrFail();

            $request->_tokens_ = $user->generateTokens();

        } catch(ModelNotFoundException $ex) {
            throw new ResourceNotFoundException(__("users.errors.not_found"));
        } catch(QueryException $ex) {
            throw new PersonalRuntimeException($ex->getMessage());
        }

        return $user;
    }
}

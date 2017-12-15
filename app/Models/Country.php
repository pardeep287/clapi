<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use App\Exceptions\PersonalRuntimeException;
use App\Exceptions\ResourceNotFoundException;
use Illuminate\Validation\ValidationException;
use App\Exceptions\PersonalValidationException;
use App\Http\Controllers\Api\Admin\CountriesController;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Country extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    //protected $table = 'countries';


    protected $fillable = [
        'name', 'dial_code','country_code'
    ];
    public $timestamps = false;

    public function states()
    {
        return $this->hasMany('App\Models\State');
    }

    public function fetch(CountriesController $controllerInstance, Request $request) 
    {
        return self::all();
    }

    public function add(CountriesController $controllerInstance, Request $request, $id = false) 
    {

        $isEdit = false;
        if ($id) $isEdit = true;


        try {
            $controllerInstance->validate($request, [
                'name' => 'required',
                'dial_code' => 'required',
                'country_code' => 'required'
            ]);
        } catch(ValidationException $ex) {
            throw new PersonalValidationException($ex->getMessage(), $ex->errors());
        }

        if ($isEdit) {
            $model = self::find($id);

            if(!$model) {
                throw new ResourceNotFoundException(__('countries.errors.not_found'));
            }
        } else {
            $model = new Country;
        }
        $model->name = $request->name;
        $model->dial_code = $request->dial_code;
        $model->country_code = $request->country_code;

        if(!$model->save()) {
            throw new PersonalRuntimeException(__('common.errors.invalid_query'));
        }

        return $model;
    }

    public function fetchFirst(CountriesController $controllerInstance, Request $request, $id)
    {
        $model = self::find($id);
        if(!$model) {
            throw new ResourceNotFoundException(__("countries.errors.not_found"));
        }
        return $model;
    }


    public function remove(CountriesController $controllerInstance, $id)
    {
        $r = self::find($id);
        if(!$r){
            throw new ResourceNotFoundException(__("countries.errors.not_found"));
        }
        if($r->delete()){
            return $r;
        }
    }


    
    /**
     * mutators
     */
    public function setCountryCodeAttribute($value)
    {
        $this->attributes['country_code'] = strtoupper($value);
    }


    public function setLocaleAttribute($value)
    {
        $this->attributes['locale'] = json_encode($value);
    }

    
    public function getLocaleAttribute($value)
    {
        return json_decode($value, true);
    }
}

<?php

namespace App\Models;

use App\Http\Controllers\Api\Admin\AdvertisementController;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use App\Exceptions\PersonalRuntimeException;
use App\Exceptions\ResourceNotFoundException;
use Illuminate\Validation\ValidationException;
use App\Exceptions\PersonalValidationException;
use App\Http\Controllers\Api\Admin\CountriesController;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Advertisement extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'advertisements';

    protected $fillable = [
        'avatar', 'active','ord','country_id'
    ];
    public $timestamps = false;

    /**
     * Scope a query to only include active users.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function states()
    {
        return $this->hasMany('App\Models\State');
    }

    public function getAdvertisement($search = null, $skip, $perPage, $isApi=false)
    {
        $take = ((int)$perPage > 0) ? $perPage : 20;
        $fields = [
            'id',
            'avatar',
            'ord',
            'country_id',
            //'country.name',
            //'country.dial_code',
            //'country.country_code',
        ];
       // $orderEntity = 'ord';
        //$orderAction = 'desc';

        $result  =  $this
            //->orderBy($orderEntity, $orderAction)
            ->skip($skip)->take($take)->get($fields);
        return $result;

    }

    public function getRandomAdvertisement()
    {

        $fields = [
            'advertisements.id',
            'avatar',
            'ord',
            //'country_id',
            //'countries.name',
            //'countries.dial_code',
            //'countries.country_code',
        ];

        $s["data"]= $this->leftJoin('countries', 'countries.id', '=', 'advertisements.country_id')
            ->inRandomOrder()
            ->limit(5)
            ->get($fields);


        $r["data"] = $s + ["Delay" => 5];

        return $r;



    }

    public function fetch(AdvertisementController $advertisementInstance, Request $request)
    {
        $r = [];
        $r["data"] = self::all();
        return $r;
    }

    public function add(CountriesController $controllerInstance, Request $request, $id = false) 
    {
        $isEdit = false;
        if ($id) $isEdit = true;

        $r = [];
        $r["data"] = null;

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
            $model = self::findOrFail($id);
        } else {
            $model = new Country;
        }
        $model->name = $request->name;
        $model->dial_code = $request->dial_code;
        $model->country_code = $request->country_code;
  
        try {
            $model->saveOrFail();
            $r["data"] = $model->toArray();
        } catch(QueryException $ex) {
            throw new PersonalRuntimeException(__('countries.errors.invalid_query'));
        }

        return $r;
    }

    public function fetchFirst(CountriesController $controllerInstance, Request $request, $id)
    {
        $r = [];
        $r["data"] = null;

        try {
            $r["data"] = self::findOrFail($id);
        } catch(QueryException $ex) {
            throw new PersonalRuntimeException(__("countries.errors.invalid_query"));
        } catch(ModelNotFoundException $ex) {
            throw new ResourceNotFoundException(__("countries.errors.not_found"));
        }
        
        return $r;
    }


    public function remove(CountriesController $controllerInstance, $id)
    {
        $r = [];
        $r["data"] = null;

        try {
            $r["data"] = self::findOrFail($id);
            $r["data"]->delete();
        } catch(QueryException $ex) {
            throw new PersonalRuntimeException(__("countries.errors.invalid_query"));
        } catch(ModelNotFoundException $ex) {
            throw new ResourceNotFoundException(__("countries.errors.not_found"));
        }
        
        return $r;
    }


    
    /**
     * mutators
     */
    public function setCountryCodeAttribute($value)
    {
        $this->attributes['country_code'] = strtoupper($value);
    }
}

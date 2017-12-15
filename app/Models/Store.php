<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use App\Exceptions\PersonalRuntimeException;
use App\Exceptions\ResourceNotFoundException;
use Illuminate\Validation\ValidationException;
use App\Exceptions\PersonalValidationException;
use App\Http\Controllers\Api\Admin\StoresController;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Store extends Model
{
    protected $fillable = [
        'country_id', 'state_id','city_id', 'area_id', 'category_id', 'passcode', 'username',
        'name', 'address1', 'address2', 'address3','zipcode','lattitude','longitude'
    ];
    protected $hidden = ['password'];

    public function fetch(StoresController $controllerInstance, Request $request) 
    {
        return self::all();
    }

    public function add(StoresController $controllerInstance, Request $request, $id = false) 
    {
        $isEdit = false;
        if ($id) $isEdit = true;

        try {
            if($request->tab == 1){
                $controllerInstance->validate($request, [
                    'country_id' => 'required',
                    'state_id' => 'required',
                    'city_id' => 'required',
                    'area_id' => 'required',
                    'category_id' => 'required',
                ]);
            }
            $controllerInstance->validate($request, [
                'country_id' => 'required',
                'state_id' => 'required',
                'city_id' => 'required',
                'area_id' => 'required',
                'category_id' => 'required',
                /*'passcode' => 'required',
                'username' => 'required',
                'password' => 'required',
                'name' => 'required',
                'address1' => 'required',
                'address2' => 'required',
                'address3' => 'required',
                'zipcode' => 'required',
                'lattitude' => 'required',
                'longitude' => 'required',*/
            ]);
        } catch(ValidationException $ex) {
            throw new PersonalValidationException($ex->getMessage(), $ex->errors());
        }

        if ($isEdit) {
            $model = self::find($id);
            if(!$model) {
                throw new ResourceNotFoundException(__('stores.errors.not_found'));
            }
        } else {
            $model = new Store;
        }
        $model->country_id = $request->country_id;
        $model->state_id = $request->state_id;
        $model->city_id = $request->city_id;
        $model->area_id = $request->area_id;
        $model->category_id = $request->category_id;
        //$model->password = Hash::make($request->password);
        //$model->passcode = str_random(6);

        if(!$model->save()) {
            throw new PersonalRuntimeException(__('common.errors.invalid_query'));
        }

        return $model;
    }

    public function fetchFirst(StoresController $controllerInstance, Request $request, $id)
    {
        $model = self::find($id);
        if(!$model) {
            throw new ResourceNotFoundException(__("stores.errors.not_found"));
        }
        return $model;
    }

    public function remove(StoresController $controllerInstance, $id)
    {
        $r = self::find($id);
        if(!$r){
            throw new ResourceNotFoundException(__("stores.errors.not_found"));
        }
        if($r->delete()){
            return $r;
        }
    }

    /**
     * mutators
     */
    public function setPasscodeAttribute($value)
    {
        $this->attributes['passcode'] = strtoupper($value);
    }
}

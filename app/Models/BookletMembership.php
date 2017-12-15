<?php

namespace App\Models;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BookletMembershipDeal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use App\Exceptions\PersonalRuntimeException;
use App\Exceptions\ResourceNotFoundException;
use Illuminate\Validation\ValidationException;
use App\Exceptions\PersonalValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Api\Admin\BookletMembershipsController;

class BookletMembership extends Model
{
    protected $fillable = [
        'name', 'price','is_visible', 'validity'
    ];

    public function deals()
    {
        return $this->hasMany(BookletMembershipDeal::class, 'booklet_membership_id', 'id');
    }

    public function fetch(Controller $controllerInstance, Request $request) 
    {
        return self::paginate();
    }

    public function add(BookletMembershipsController $controllerInstance, Request $request, $id = false) 
    {
        $isEdit = false;
        if ($id) $isEdit = true;

        $r = [];
        $r["data"] = null;

        try {
            $controllerInstance->validate($request, [
                'name' => 'required',
                'price' => 'required',
                'is_visible' => 'required',
                'validity' => 'required',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:1024',
            ]);
        } catch(ValidationException $ex) {
            throw new PersonalValidationException($ex->getMessage(), $ex->errors());
        }

        // upload image
        $image = $request->file('image');
        $imageName = sha1(str_random(20) . time()) . '.' . $image->getClientOriginalExtension();
        $destinationPath = public_path('/booklets');
        $image->move($destinationPath, $imageName);

        if ($isEdit) {
            $model = self::findOrFail($id);
        } else {
            $model = new BookletMembership;
        }
        $model->name = $request->name;
        $model->price = $request->price;
        $model->is_visible = $request->is_visible;
        $model->validity = $request->validity;
        $model->image_path = $imageName;
  
        try {
            $model->saveOrFail();
            $r["data"] = $model->toArray();
        } catch(QueryException $ex) {
            throw new PersonalRuntimeException(__('common.errors.invalid_query'));
        }

        return $r;
    }

    public function fetchFirst(Controller $controllerInstance, Request $request, $id)
    {
        try {
            $booklet = self::findOrFail($id);
        } catch(QueryException $ex) {
            throw new PersonalRuntimeException(__("common.errors.invalid_query"));
        } catch(ModelNotFoundException $ex) {
            throw new ResourceNotFoundException(__("booklets.errors.not_found"));
        }
        
        return $booklet;
    }


    public function remove(BookletMembershipsController $controllerInstance, $id)
    {
        $r = [];
        $r["data"] = [];

        try {
            $r['data'] = self::findOrFail($id);
            $r['data']->delete();
        } catch(QueryException $ex) {
            throw new PersonalRuntimeException(__("common.errors.invalid_query"));
        } catch(ModelNotFoundException $ex) {
            throw new ResourceNotFoundException(__("horoscope-categories.errors.not_found"));
        }
        
        return $r;
    }
}

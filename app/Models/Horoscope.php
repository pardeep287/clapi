<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use App\Exceptions\PersonalRuntimeException;
use App\Exceptions\ResourceNotFoundException;
use Illuminate\Validation\ValidationException;
use App\Exceptions\PersonalValidationException;
use App\Http\Controllers\Api\Admin\HoroscopesController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\HoroscopeCategory;
use Illuminate\Support\Facades\DB;

class Horoscope extends Model
{
    protected $fillable = [
        'horoscope_category_id', 'content'
    ];

    public function category() {
        return $this->hasOne(HoroscopeCategory::class, 'id', 'horoscope_category_id');
    }

    public function fetch(HoroscopesController $controllerInstance, Request $request) 
    {
        $r = [];
        $r["data"] = self::with(['category'])->get();
        return $r;
    }

    public function add(HoroscopesController $controllerInstance, Request $request, $id = false) 
    {
        $isEdit = false;
        if ($id) $isEdit = true;

        $r = [];
        $r["data"] = null;

        try {
            $controllerInstance->validate($request, [
                'horoscope_category_id' => 'required|exists:horoscope_categories,id',
                'content_01' => 'required',
                'heading_01' => 'required',
                'content_02' => 'required',
                'heading_02' => 'required',
                'content_03' => 'required',
                'heading_03' => 'required',
                'content_04' => 'required',
                'heading_04' => 'required'
            ]);
        } catch(ValidationException $ex) {
            throw new PersonalValidationException($ex->getMessage(), $ex->errors());
        }

        if ($isEdit) {
            $model = self::findOrFail($id);
        } else {
            $model = new Horoscope;
        }

        $content = [
            [
                "heading" => $request->heading_01,
                "content" => $request->content_01,
            ],
            [
                "heading" => $request->heading_02,
                "content" => $request->content_02,
            ],
            [
                "heading" => $request->heading_03,
                "content" => $request->content_03,
            ],
            [
                "heading" => $request->heading_04,
                "content" => $request->content_04,
            ]
        ];

        $model->horoscope_category_id = $request->horoscope_category_id;
        $model->content = json_encode($content);
  
        try {
            $model->saveOrFail();
            $r["data"] = $model->toArray();
        } catch(QueryException $ex) {
            throw new PersonalRuntimeException(__('common.errors.invalid_query'));
        }

        return $r;
    }

    public function fetchFirst(HoroscopesController $controllerInstance, Request $request, $id)
    {
        $r = [];
        $r["data"] = null;

        try {
            $r["data"] = self::with(['category'])->where('id', $id)->firstOrFail();
        } catch(QueryException $ex) {
            throw new PersonalRuntimeException(__("common.errors.invalid_query"));
        } catch(ModelNotFoundException $ex) {
            throw new ResourceNotFoundException(__("horoscopes.errors.not_found"));
        }
        
        return $r;
    }



    public function byCategoryId(HoroscopesController $controllerInstance, Request $request, $categoryId)
    {
        $r = [];
        $r["data"] = null;

        try {
            $r["data"] = self::where('horoscope_category_id', $categoryId)
                ->with(['category'])
                ->orderBy(DB::raw('RAND()'))
                ->firstOrFail();
        } catch(QueryException $ex) {
            throw new PersonalRuntimeException(__("common.errors.invalid_query"));
        } catch(ModelNotFoundException $ex) {
            throw new ResourceNotFoundException(__("horoscopes.errors.not_found"));
        }
        
        return $r;
    }


    public function remove(HoroscopesController $controllerInstance, $id)
    {
        $r = [];
        $r["data"] = [];

        try {
            $r['data'] = self::findOrFail($id);
            $r['data']->delete();
        } catch(QueryException $ex) {
            throw new PersonalRuntimeException(__("common.errors.invalid_query"));
        } catch(ModelNotFoundException $ex) {
            throw new ResourceNotFoundException(__("horoscopes.errors.not_found"));
        }
        
        return $r;
    }

    public function getContentAttribute($value) {
        $content = json_decode($value, true);
        return $content;
    }
}

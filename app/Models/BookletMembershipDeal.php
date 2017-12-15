<?php

namespace App\Models;

use Illuminate\Http\Request;
use App\Models\BookletDealCoupon;
use App\Models\BookletMembership;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\PersonalRuntimeException;
use App\Exceptions\ResourceNotFoundException;
use Illuminate\Validation\ValidationException;
use App\Exceptions\PersonalValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Api\Admin\BookletMembershipDealsController;

class BookletMembershipDeal extends Model
{
    protected $fillable = [
        'booklet_membership_id', 'store_name','deal_name', 'actual_price', 'discount_price', 'payable_price', 'image_path', 'coupons_quantity', 'terms'
    ];


    public function booklet()
    {
        return $this->belongsTo(BookletMembership::class, 'booklet_membership_id', 'id');
    }

    public function coupons()
    {
        return $this->hasMany(BookletDealCoupon::class, 'booklet_deal_id', 'id');
    }
    

    public function fetch(Controller $controllerInstance, Request $request, $bookletId)
    {
        try {
            $booklet = BookletMembership::findOrFail($bookletId);
        } catch(ModelNotFoundException $ex) {
            throw new ResourceNotFoundException(__('booklets.errors.not_found'));
        }

        return $booklet->deals()->paginate();
    }

    public function add(BookletMembershipDealsController $controllerInstance, Request $request, $id = false) 
    {
        $isEdit = false;
        if ($id) $isEdit = true;

        $r = [];
        $r["data"] = null;

        try {
            $controllerInstance->validate($request, [
                'store_name' => 'required',
                'deal_name' => 'required',
                'actual_price' => 'required',
                'discount_price' => 'required',
                'payable_price' => 'required',
                'coupons_quantity' => 'required',
                'terms' => 'required',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:1024',
                'deal_coupons' => 'required',
            ]);
        } catch(ValidationException $ex) {
            throw new PersonalValidationException($ex->getMessage(), $ex->errors());
        }

        if ($isEdit) {
            $model = self::findOrFail($id);
        } else {
            $model = new BookletMembershipDeal;
        }

        // upload image
        $image = $request->file('image');
        $imageName = sha1(str_random(20) . time()) . '.' . $image->getClientOriginalExtension();
        $destinationPath = public_path('/booklets/deals');
        $image->move($destinationPath, $imageName);

        // upload coupons
        $couponsFile = $request->file('deal_coupons');
        $fileName = str_random(20) . time().'.'.$couponsFile->getClientOriginalExtension();
        $destinationPath = storage_path('/temp/deal-coupons');
        $couponsFile->move($destinationPath, $fileName);

        $reader = Excel::load($destinationPath . '/' . $fileName)->get();
        $sheet = $reader->all()[0];
        foreach($sheet as $item) {
            $coupons[] = [
                "coupon_code" => $item['coupon_code'],
                "is_used" => 0
            ];
        }

        $model->booklet_membership_id = $request->booklet_membership_id;
        $model->store_name = $request->store_name;
        $model->deal_name = $request->deal_name;
        $model->actual_price = $request->actual_price;
        $model->discount_price = $request->discount_price;
        $model->payable_price = $request->payable_price;
        $model->image_path = $imageName;
        $model->coupons_quantity = $request->coupons_quantity;
        $model->terms = $request->terms;
  
        try {
            $model->saveOrFail();
            if (count($coupons) > 0) {
                $model->coupons()->createMany($coupons);
            }
            $r["data"] = $model->toArray();
        } catch(QueryException $ex) {
            throw new PersonalRuntimeException(__('countries.errors.invalid_query'));
        }

        return $r;
    }

    public function fetchFirst(Controller $controllerInstance, Request $request, $bookletId, $dealId)
    {
        try {
            $deal = self::where('booklet_membership_id', $bookletId)
                ->where('id', $dealId)
                ->firstOrFail();
        } catch(QueryException $ex) {
            throw new PersonalRuntimeException(__("common.errors.invalid_query"));
        } catch(ModelNotFoundException $ex) {
            throw new ResourceNotFoundException(__("booklet-deals.errors.not_found"));
        }
        
        return $deal;
    }


    public function remove(BookletMembershipDealsController $controllerInstance, $bookletId, $dealId)
    {
        $r = [];
        $r["data"] = [];

        try {
            $r['data'] = self::findOrFail($dealId);
            $r['data']->delete();

            $r['data']->coupons()->delete();
        } catch(QueryException $ex) {
            throw new PersonalRuntimeException(__("common.errors.invalid_query"));
        } catch(ModelNotFoundException $ex) {
            throw new ResourceNotFoundException(__("horoscope-categories.errors.not_found"));
        }
        
        return $r;
    }
}

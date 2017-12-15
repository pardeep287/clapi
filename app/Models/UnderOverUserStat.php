<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use App\Exceptions\PersonalRuntimeException;
use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\InsufficientGoldException;
use Illuminate\Validation\ValidationException;
use App\Exceptions\PersonalValidationException;
use App\Http\Controllers\Api\Games\UnderOverController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\UnderOverUserTransaction;

class UnderOverUserStat extends Model
{
    protected $fillable = [
        'user_id',
        'game_coins',
        'gold',
        'lifes',
        'purchased_lifes'
    ];
    protected $statsFields = [
        'id',
        'game_coins',
        'gold',
        'lifes',
        'purchased_lifes'
    ];

    public function transactions() {
        return $this->hasMany(UnderOverUserTransaction::class, 'under_over_user_stats_id', 'id');
    }
    

    /**
     * Finds the game stats for deal no deal of logged-in user
     * 
     * @param UnderOverController $controllerInstance
     * @return void
     */
    public function getUserStats(UnderOverController $controllerInstance, Request $request = null) 
    {
        $r = [];
        $r["data"] = null;
        $userId = $request->_user_->id;
        $fields = [];
        
        if ($request instanceof Request) {
            $fields = ($request->fields) ? explode(',', $request->fields) : null;
            if($fields) {
                $fields = array_map('trim', $fields);
    
                // validate if the requested fields are allowed to be fetched
                $validatedFields = [];
                foreach($fields as $f) {
                    if (in_array($f, $this->statsFields)) {
                        $validatedFields[] = $f;
                    }
                }
            }
        }

        try {
            $r['data'] = self::where('user_id', $userId);

            $r['data']->select($this->statsFields);
            if(count($fields) > 0) {
                $r['data']->select($validatedFields);
            }

            $r['data'] = $r['data']->firstOrFail();
        } catch(QueryException $ex) {
        } catch(ModelNotFoundException $ex) {
            $createStat = new UnderOverUserStat;
            $createStat->user_id = $userId;
            $createStat->game_coins = 1000;
            $createStat->gold = 2000;
            $createStat->lifes = 0;
            $createStat->purchased_lifes = 0;

            try {
                $createStat->saveOrFail();
                $r["data"] = $createStat;
            } catch(QueryException $ex) {
                throw new PersonalRuntimeException($ex->getMessage());
            }
        }

        return $r;
    }


    public function checkFee($amount = false) {
        if(!$amount) {
            $fee = config('games.under_over.entry_fee');
        } else {
            $fee = $amount;
        }
        
        if ($this->gold < $fee) {
            throw new InsufficientGoldException(__('games.errors.insufficient_gold', ['fee' => $fee]));
        }
    }


    public function chargeFee($amount = false) {
        // charge user for gameplay
        $this->checkFee($amount);

        if(!$amount) {
            $fee = config('games.under_over.entry_fee');
        } else {
            $fee = $amount;
        }
        
        // deduct from the gold of current user
        $this->gold = $this->gold - $fee;
        try {
            $this->saveOrFail();
        } catch(QueryException $ex) {
            throw new PersonalRuntimeException(__('common.errors.invalid_query'));
        }
    }

    public function returnFee() {
        $fee = config('games.under_over.entry_fee');

        $this->gold = $this->gold + $fee;
        try {
            $this->saveOrFail();
        } catch(QueryException $ex) {
            throw new PersonalRuntimeException($ex->getMessage());
        }
    }
}

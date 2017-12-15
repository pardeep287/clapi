<?php

namespace App\Http\Controllers\Api\Games;

use Illuminate\Http\Request;
use App\Models\UnderOverUserStat;
use App\Http\Controllers\Controller;
use App\Models\UnderOverUserTransaction;

class UnderOverController extends Controller {
    public function enterGame(Request $request)
    {
        $result = (new UnderOverUserTransaction)->enterGame($this, $request);
        return response()->json($result);
    }

    public function playGame(Request $request)
    {
        $result = (new UnderOverUserTransaction)->initializeGame($this, $request);
        return response()->json($result);
    }

    public function finishGame(Request $request)
    {
        $result = (new UnderOverUserTransaction)->finishGame($this, $request);
        return response()->json($result);
    }

    public function getUserStats(Request $request)
    {
        $result = (new UnderOverUserStat)->getUserStats($this, $request);
        return response()->json($result);
    }

    public function saveChoice(Request $request) {
        $result = (new UnderOverUserTransaction)->saveChoice($this, $request);
        return response()->json($result);
    }
}

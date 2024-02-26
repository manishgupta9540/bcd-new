<?php

namespace App\Http\Controllers\API\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    //
    //demo method
    public function list()
    {
        return response()->json(['data' => ['mihtilesh','priyanka']], 200);
    }
}

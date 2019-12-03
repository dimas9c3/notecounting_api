<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserNotes;
use Illuminate\Support\Facades\Validator;

class UserNotesController extends Controller
{
    public function index() {
        return response()->json(['result' => 'ok',]);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'title'                 => 'required|min:4',
            'description'           => 'required|min:6',
            'label' 				=> 'required',
            'type'                  => 'required',
        ]);

        if ($validator->fails()) {
            $err = array();
            foreach ($validator->errors()->toArray() as $key => $error)  {
                array_push($err, ['code' => $key, 'message' => $error[0]]);
            }

            return response()->json([
                'result'		=> 0,
                'message'		=> 'User input validation not valid',
                'reason'		=> $err,
            ], 200);
        }

        $type       = strip_tags($request->type);

        if($type == 1) {
            $status     = 1;
            $dueDate    = null;
        }elseif($type == 2) {
            $status     = 0;
            $dueDate    = strip_tags($request->due_date);
        }else {
            $status     = 1;
            $dueDate    = null;
        }

        try {
            UserNotes::create([
                'email' 	    => strip_tags($request->email),
                'title' 	    => strip_tags($request->title),
                'description'   => strip_tags($request->description),
                'label'         => strip_tags($request->label),
                'type'          => $type,
                'status'        => $status,
                'due_date'      => $dueDate,
            ]);

            return response()->json([
                'result'		=> 1,
                'message'		=> 'Data successfully saved',
            ],200);
            
        } catch (\Exception $e) {
            return response()->json([
                'result'		=> 0,
                'message'		=> 'Data fail to saved',
                'reason'		=> $e->getMessage(),
            ], 200);
        }			
    }
}

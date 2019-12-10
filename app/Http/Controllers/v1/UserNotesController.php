<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\UserNotes;

class UserNotesController extends Controller
{
    public function getByEmail(Request $request) {
        $email          = strip_tags($request->email);

        if(empty($email)) {
            return response()->json([
                'result'		=> 0,
                'message'		=> 'Email parameter required',
            ],200);
        }

        try {
            $notes = UserNotes::where('email', $email)
            ->orderBy('id', 'ASC')
            ->get();

            $data = array();
            foreach ($notes as $note) {
                $description = strip_tags($note->description);
                // if (strlen($description) > 50) {
                //     $stringCut  = substr($description, 0, 50);
                //     $endPoint   = strrpos($stringCut, ' ');

                //     $description = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                //     $description .= '...';
                // }

                switch ($note->label) {
                    case 1:
                        $label = 'Personal';
                    break;
                    case 2:
                        $label = 'Work';
                    break;
                    case 3:
                        $label = 'Events';
                    break;
                    case 4:
                        $label = 'Friends';
                    case 5:
                        $label = 'Others';
                    break;
                    default:
                        $label = 'Others';
                    break;
                }

                switch ($note->type) {
                    case 1:
                        $type = 'Regular';
                    break;
                    case 2:
                        $type = 'Deadline';
                    break;
                    default:
                        $type = 'Regular';
                    break;
                }

                switch ($note->status) {
                    case 1:
                        $status = 'Completed';
                    break;
                    case 2:
                        $status = 'OnProgress';
                    break;
                    case 3: 
                        $status = 'Pending';
                    break;
                    default:
                        $status = 'Completed';
                    break;
                }

                if(empty($note->due_date)) {
                    $dueDate = null;
                }else {
                    $dueDate = date('Y-m-d', strtotime($note->due_date));
                }

                array_push($data, [
                    'id'            => $note->id,
                    'title'         => $note->title,
                    'description'   => $description,
                    'label'         => $label,
                    'type'          => $type,
                    'status'        => $status,
                    'due_date'      => $dueDate,
                    'created_at'    => date('d-M-Y', strtotime($note->created_at)),
                ]);
            }

            return response()->json([
                'result'		=> 1,
                'data'          => $data,
                'message'		=> 'Data successfully retrieved',
            ],200);
        } catch (\Exception $e) {
            return response()->json([
                'result'		=> 0,
                'reason'        => $e,
                'message'		=> 'Data fail to retrieved',
            ],200);
        }
    }

    public function store(Request $request) {
        $email          = strip_tags($request->email);

        if(empty($email)) {
            return response()->json([
                'result'		=> 0,
                'message'		=> 'Email parameter required',
            ],200);
        }
        
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
            $status     = 2;
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

    public function destroy($id) {
        $id          = strip_tags($id);

        if(empty($id)) {
            return response()->json([
                'result'		=> 0,
                'message'		=> 'Id notes parameter required',
            ],200);
        }

        try {
            $deletedNotes = UserNotes::where('id', $id)->delete();

            return response()->json([
                'result'		=> 1,
                'message'		=> 'Data successfully deleted',
            ],200);            
        } catch (\Exception $e) {
            return response()->json([
                'result'		=> 0,
                'message'		=> 'Data fail to deleted',
                'reason'        => $e,
            ],200);
        }
    }

    public function update(Request $request) {
        $noteId          = strip_tags($request->noteId);

        if(empty($noteId)) {
            return response()->json([
                'result'		=> 0,
                'message'		=> 'Note id parameter required',
            ],200);
        }

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
            $status     = 2;
            $dueDate    = strip_tags($request->due_date);
        }else {
            $status     = 1;
            $dueDate    = null;
        }

        try {
            UserNotes::where('id', $noteId)
            ->update([
                'title' 	    => strip_tags($request->title),
                'description'   => strip_tags($request->description),
                'label'         => strip_tags($request->label),
                'type'          => $type,
                'status'        => $status,
                'due_date'      => $dueDate,
            ]);

            return response()->json([
                'result'		=> 1,
                'message'		=> 'Data successfully updated',
            ],200);
            
        } catch (\Exception $e) {
            return response()->json([
                'result'		=> 0,
                'message'		=> 'Data fail to updated',
                'reason'		=> $e->getMessage(),
            ], 200);
        }
    }
}

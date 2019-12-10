<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
	return response()->json([
        'result'		=> 0,
        'message'   => 'No route found'
    ], 404);
});

$router->post('jwt/generateToken', ['uses' => 'JwtController@generate']);

$router->group(['middleware' => 'jwt.auth'], function() use ($router) {

    $router->group(['namespace' => 'v1'], function() use ($router) {
        $router->group(['prefix' => 'v1'], function() use ($router) {

            // USER NOTES SECTION
            $router->group(['prefix' => 'usernotes'], function() use ($router) {
                
                $router->post('/getByEmail', [
                    'uses'  => 'UserNotesController@getByEmail', 
                    'as'    => 'usernotes.getByEmail',
                ]);

                $router->post('/store', [
                    'uses'  => 'UserNotesController@store',
                    'as'    => 'usernotes.store',
                ]);

                $router->get('/delete/{id}', [
                    'uses'  => 'UserNotesController@destroy',
                    'as'    => 'usernotes.delete',
                ]);

                $router->post('/update', [
                    'uses'  => 'UserNotesController@update',
                    'as'    => 'usernotes.update',
                ]);

                $router->get('/changeNoteStatus/{action}/{id}', [
                    'uses'  => 'UserNotesController@changeNoteStatus',
                    'as'    => 'usernotes.changeNoteStatus',
                ]);
                
            });

        });
    });

		// $router->get('users', function() {
		// 	$users = \App\User::orderBy('id', 'ASC')->get();
		// 	return response()->json([
		// 		'result'		=> 1,
		// 		'data'			=> $users,
		// 	], 200);
		// });

		// $router->post('users', function(Request $request) {

		// 	$validator = Validator::make($request->all(), [
		// 		'name' 					=> 'required',
		// 		'email' 				=> 'required|email|unique:users',
		// 		'password' 				=> 'required|confirmed|min:6',
		// 	]);

		// 	if ($validator->fails()) {
		// 		$err = array();
		// 		foreach ($validator->errors()->toArray() as $error)  {
		// 			foreach($error as $sub_error){
		// 				array_push($err, $sub_error);
		// 			}
		// 		}

		// 		return response()->json([
		// 			'result'		=> 0,
		// 			'message'		=> 'Data fail to saved',
		// 			'reason'		=> $err,
		// 			'messageCode'	=> 2,
		// 		], 200);
		// 	}


		// 	try {

		// 		DB::table('users')->insert([
		// 			'name' 		=> $request->name,
		// 			'email' 	=> $request->email,
		// 			'password' 	=> Hash::make($request->password),
		// 		]);

		// 		return response()->json([
		// 			'result'		=> 1,
		// 			'message'		=> 'Data successfully saved',
		// 			'messageCode'	=> 1,
		// 		],200);
				
		// 	} catch (\Exception $e) {
		// 		return response()->json([
		// 			'result'		=> 0,
		// 			'message'		=> 'Data fail to saved',
		// 			'reason'		=> $e->getMessage(),
		// 			'messageCode'	=> 2,
		// 		], 200);
		// 	}			
		// });

		// $router->put('users', function(Request $request) {
		// 	$this->validate($request, [
		// 		'id'					=> 'required',
		// 		'name' 					=> 'required',
		// 		'email' 				=> 'required|email',
		// 	]);

		// 	try {

		// 		$QFind = DB::table('users')->where('id', $request->id)->exists();

		// 		if (!$QFind) {
		// 			return response()->json(array(
		// 				'result'		=> 0,
		// 				'message'		=> 'Data not found',
		// 				'messageCode'	=> 3,
		// 			));
		// 		}

		// 		DB::table('users')
		// 		->where('id', $request->id)
		// 		->update([
		// 			'name' 		=> $request->name,
		// 			'email'		=> $request->email,
		// 		]);

		// 		return response()->json(array(
		// 			'result'		=> 1,
		// 			'message'		=> 'Data successfully updated',
		// 			'messageCode'	=> 1,
		// 		));
				
		// 	} catch (\Exception $e) {
		// 		return response()->json(array(
		// 			'result'		=> 0,
		// 			'message'		=> 'Data fail to updated',
		// 			'reason'		=> $e->getMessage(),
		// 			'messageCode'	=> 2,
		// 		));
		// 	}			
		// });

		// $router->post('users/delete', function(Request $request) {
		// 	$this->validate($request, [
		// 		'email' 				=> 'required|email',
		// 	]);

		// 	try {

		// 		$QFind = DB::table('users')->where('email', $request->email)->exists();

		// 		if (!$QFind) {
		// 			return response()->json(array(
		// 				'result'		=> 0,
		// 				'message'		=> 'Data not found',
		// 				'messageCode'	=> 3,
		// 			));
		// 		}
				
		// 		DB::table('users')
		// 		->where('email', $request->email)
		// 		->delete();

		// 		return response()->json(array(
		// 			'result'		=> 1,
		// 			'message'		=> 'Data successfully deleted',
		// 			'messageCode'	=> 1,
		// 		));
				
		// 	} catch (\Exception $e) {
		// 		return response()->json(array(
		// 			'result'		=> 0,
		// 			'message'		=> 'Data fail to deleted',
		// 			'reason'		=> $e->getMessage(),
		// 			'messageCode'	=> 2,
		// 		));
		// 	}			
		// });

		// $router->patch('users', function(Request $request) {
		// 	$QFind = DB::table('users')->where('email', $request->email)->exists();

		// 	if (!$QFind) {
		// 		return response()->json(array(
		// 			'result'		=> 0,
		// 			'message'		=> 'Data not found',
		// 			'messageCode'	=> 2,
		// 		));
		// 	}

		// 	$details['email'] = $request->email;
		// 	dispatch(new App\Jobs\SendEmail($details));

		// 	return response()->json(array(
		// 		'result'		=> 1,
		// 		'message'		=> 'Data found and email has been sent',
		// 		'messageCode'	=> 1,
		// 	));
			
		// });
	}
);

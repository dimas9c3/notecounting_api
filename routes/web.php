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
        'message'       => 'No route found',
        'status'        => app()->environment(),
    ], 404);
});

$router->post('jwt/generateToken', ['uses' => 'JwtController@generate']);

$router->group(['namespace' => 'v1'], function() use ($router) {
    $router->get('/shit/{user}', [
        'uses'  => 'UserNotesController@getShit',
        'as'    => 'usernotes.getShit',
    ]);
});

$router->group(['middleware' => 'jwt.auth'], function() use ($router) {
    $router->group(['namespace' => 'v1'], function() use ($router) {
        $router->group(['prefix' => 'v1'], function() use ($router) {

            // USER NOTES SECTION
            $router->group(['prefix' => 'usernotes'], function() use ($router) {
                
                $router->post('/getByEmail', [
                    'uses'  => 'UserNotesController@getByEmail', 
                    'as'    => 'usernotes.getByEmail',
                ]);

                $router->post('/countNotesWithStatusByEmail', [
                    'uses'  => 'UserNotesController@countNotesWithStatusByEmail',
                    'as'    => 'usernotes.countNotesWithStatusByEmail',
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
});

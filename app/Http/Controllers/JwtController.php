<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Validator;
use App\Models\JwtModel;

class JwtController extends Controller {
    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    private $request;
    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Request $request) {
        $this->request = $request;
    }
   
    protected function jwt($email) {
        $payload = [
            'iss' => "notecountingapps", // Issuer of the token
            'sub' => $email, // Subject of the token
            'iat' => time(), // Time when JWT was issued. 
            // 'exp' => time() // Expiration time
        ];
        
        return JWT::encode($payload, env('JWT_SECRET'));
    } 

    public function generate() {
        $this->validate($this->request, [
            'email'     => 'required|email',
        ]);

        $token      = $this->jwt($this->request->input('email'));

        try {
            $insert     = JwtModel::create([
                'email'     => $this->request->input('email'),
                'token'     => $token,
            ]);
    
            return response()->json([
                'result'        => 1,
                'message'       => 'Token successfully created',
                'data'          => $token,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'result'        => 0,
                'message'       => 'Token failed to create',
                'reason'        => $e,
            ], 200);
        }
        
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\JwtAuth;
use Illuminate\Support\Facades\DB;
use App\User;


class UserController extends Controller
{
    public function register(Request $request){
    	//recoger variables que llegan por post
    	$json = $request->input('json', null);
    	$params = json_decode($json);

    	$email = (!is_null($json) && isset($params->email)) ? $params->email : null;
    	$name = (!is_null($json) && isset($params->name)) ? $params->name : null;
    	$surname = (!is_null($json) && isset($params->surname)) ? $params->surname : null;
    	$role = 'ROLE_USER';
    	$password = (!is_null($json) && isset($params->password)) ? $params->password : null;
    	
    	if (!is_null($email) && !is_null($password) && !is_null($name)) {
    		
    		//Crear usuario
    		$user = new User();
    		$user->email = $email;
    		$user->name = $name;
    		$user->surname = $surname;
    		$user->role = $role;

    		$pwd = hash('sha256', $password);
    		$user->password = $pwd;

    		//Evaluar si ya existe el usuario duplicado
    		$isset_user = User::where('email', '=', $email)->count();    		

    		if ($isset_user == 0) {
    			# Guardar usuario
    			$user->save();

    			$data = array(
	    			'status' => 'success',
	    			'code' => 200,
	    			'message' => 'usuario registrado'
	    		);

    		} else {
    			# No guardar usuario
    			$data = array(
	    			'status' => 'error',
	    			'code' => 400,
	    			'message' => 'usuario duplicado, no puede registrarse'
	    		);
    		}
    		

    	} else {
    		$data = array(
    			'status' => 'error',
    			'code' => 400,
    			'message' => 'usuario no creado'
    		);
    	}

    	return response()->json($data, 200);
    	

    }

    public function login(Request $request){

    	$jwtAuth = new JwtAuth();

        //Recibir datos por POST
        $json = $request->input('json', null);
        $params = json_decode($json);
        
        $email = (!is_null($json) && isset($params->email)) ? $params->email : null;
        $password = (!is_null($json) && isset($params->password)) ? $params->password : null;
        $getToken = (!is_null($json) && isset($params->gettoken)) ? $params->gettoken : null;

        //Cifrar password
        $pwd = hash('sha256', $password);

        if (!is_null($email) && !is_null($password) && ($getToken == null || $getToken == 'false')) {
            $signup = $jwtAuth->signup($email, $pwd);            

        } elseif ($getToken != null) {
            $signup = $jwtAuth->signup($email, $pwd, $getToken);

        } else {
            $signup = array(
                'status' => 'error',
                'message' => 'Envía tus datos por POST'
            );
        }

        return response()->json($signup, 200);
    	
    }

}

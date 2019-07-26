<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JwtAuth;

use App\Guitar;

class GuitarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){

        $guitars = Guitar::all()->load('brand')->load('user');
        return response()->json(array(
            'guitars' => $guitars,
            'status' => 'success'
        ), 200 );
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $hash = $request->header('Authorization', null);
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);

        if ($checkToken) {
            // Recoger datos que vienen por POST
            $json = $request->input('json', null);
            $params = json_decode($json);

            // Covertir el json en array
            $params_array = json_decode($json, true);

            // Conseguir el usuario identificado
            $user = $jwtAuth->checkToken($hash, true);
            

            //Validación
            $validate = \Validator::make($params_array, [
                'model' =>'required|min:1',
                'type' =>'required',
                'cords' =>'required',
                'price' =>'required',
                'description' =>'required'
            ]);
              
            if ($validate->fails()) {
                return response()->json($validate->errors(), 400);
            }
            

            // Guardar coche            
            $guitar = new Guitar();
            $guitar->user_id = $user->sub;
            $guitar->brand_id = $params->brand_id;
            $guitar->model = $params->model;
            $guitar->type = $params->type;
            $guitar->cords = $params->cords;
            $guitar->price = $params->price;
            $guitar->description = $params->description;

            $guitar->save();

            $data = array(
                'guitar' => $guitar,
                'status' => 'success',
                'code' => 200,
            );

        } else {
            // Devolver un error

            $data = array(
                'message' => 'Login incorrecto',
                'status' => 'error',
                'code' => 400,
            );

        }

        return response()->json($data, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       $guitar = Guitar::find($id);

        if (is_object($guitar)) {
            # code...
            $guitar = Guitar::find($id)->load('brand')->load('user');
            return response()->json(array(
                'guitar' =>$guitar,
                'status' => 'success'
            ), 200 );
        } else {
            # code...
            return response()->json(array(
                'message' => 'La guitarra no existe',
                'status' => 'error'
            ), 200 );
        }        
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $hash = $request->header('Authorization', null);
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);
        if ($checkToken) {
            // Recoger parámetros que llegan por POST
            $json = $request->input('json', null);
            $params = json_decode($json);

            // Covertir el json en array
            $params_array = json_decode($json, true);   

            // Validar los datos
            $validate = \Validator::make($params_array, [
                'model' => 'required|min:1',
                'type' => 'required',
                'cords' => 'required',
                'price' => 'required',
                'description' => 'required'
            ]);
              
            if ($validate->fails()) {
                return response()->json($validate->errors(), 400);
            }

            // Quitar las variables que no quiere actualizar

            unset($params_array['id']);
            unset($params_array['user_id']); 
            unset($params_array['created_at']);
            unset($params_array['user']);
            unset($params_array['brand']);

            // Actualizar el coche
            $guitar = Guitar::where('id', $id)->update($params_array);          

            // Devolver una respuesta            

            $data = array(
                'guitar' => $params,
                'status' => 'success',
                'code' => 200,
            );

        } else {
            // Devolver un error
            $data = array(
                'message' => 'Login incorrecto',
                'status' => 'error',
                'code' => 300,
            );
        }

        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $hash = $request->header('Authorization', null);
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($hash);

        if ($checkToken) {
            // Comprobar que existe el registro
            $guitar = Guitar::find($id);

            // Eliminarlo
            $guitar->delete();

            //Devolverlo
            $data = array(
                'guitar' => $guitar,
                'status' => 'success',
                'code' => 200
            );


        } else {
            // Devolver un error
            $data = array(
                'message' => 'Login incorrecto',
                'status' => 'success',
                'code' => 300,
            );
        }

        return response()->json($data, 200);
    }
}

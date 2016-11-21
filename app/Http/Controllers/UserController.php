<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

class UserController extends Controller
{
    public function __construct()
    {
        //utilizamos los middlewares auth y refresh para tokens actualizados
        $this->middleware(['jwt.auth', 'jwt.refresh']); 
    }

    /**
     * obtenemos todos los usuarios de la app
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::all();
    }

     /**
     * obtenemos todos los usuarios paginados
     *
     * @return \Illuminate\Http\Response
     */
    public function paginated($page = 1)
    {
        $limit = 1;
        return response()->json([
            "count"=> User::count(),
            "data" => User::skip(($page - 1) * $limit)->take($limit)->get()->toArray()
        ]);
    }

    /**
     * guardamos un nuevo usuario
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = new User;
        $inserted = $user->create($request->input());
        return response()->json([
            "res" => "El usuario con id {$inserted->id} ha sido guardado correctamente"
        ]);
    }

    /**
     * devolvemos un usuario por su id
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return User::find($id) ?: [];
    }

    /**
     * actualizamos un usuario
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->fill($request->all())->save();
        return response()->json([
            "res" => "Usuario con id {$id} actualizado"
        ]);
    }

    /**
     * eliminamos un usuario
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::destroy($id);
        return response()->json([
            "res" => "Usuario con id {$id} eliminado"
        ]);
    }
}

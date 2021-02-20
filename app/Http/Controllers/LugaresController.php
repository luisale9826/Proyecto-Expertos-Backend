<?php

namespace App\Http\Controllers;

use App\Models\lugares;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LugaresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all
        return lugares::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $categoria = null;
        switch ($request->get('categoria')) {
            case '1':
                $categoria="Montaña";
                break;
            case '2':
                $categoria="Playa";
                break;
            case '3':
                $categoria="Rural";
                break;
            case '4':
                $categoria="Ciudad";
                break;
        }

        DB::table('lugares')->insert([
            'nombre' => $request->get('nombre'),
            'descripcion' => $request->get('descripcion'),
            'categoria' => $categoria,
            'latitud' => $request->get('latitud'),
            'longitud' => $request->get('longitud'),
            'url_foto' => $request->get('url_foto'),
            'url_video' => $request->get('url_video')
        ]);
        
        return 'Insertado';
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        return lugares::find($id);
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
        //
        $lugar = lugares::find($id);
        $lugar->update($request->all());
        return $lugar;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        return lugares::destroy($id);
    }
}

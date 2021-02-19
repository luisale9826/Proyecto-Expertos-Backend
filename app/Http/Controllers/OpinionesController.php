<?php

namespace App\Http\Controllers;

use App\Models\lugares;
use App\Models\opiniones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OpinionesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return opiniones::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        return opiniones::created($request->all());
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
        return opiniones::find($id);
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
        $opinion = opiniones::find($id);
        $opinion->update($request->all());
        return $opinion;
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
        return opiniones::destroy($id);
    }

    public function euclides(Request $request)
    {
        $determinaResultado = 1;

        /** LLENA LAS VARIABLES DESDE EL FORMULARIO */

        // 'mejor_mes', 'alojamiento', 'accesibilidad', 'precio', 'clima', 'comida', 'conexion_internet', 'id_lugar'
        $mejor_mes_php = $request->get('mejor_mes');
        $alojamiento_php = $request->get('alojamiento');
        $accesibilidad_php = $request->get('accesibilidad');
        $precio_php = $request->get('precio');
        $clima_php = $request->get('clima');
        $comida_php = $request->get('comida');
        $conexion_internet_php = $request->get('conexion_internet');

        $estudianteAEvaluar[0] = $mejor_mes_php;
        $estudianteAEvaluar[1] = $alojamiento_php;
        $estudianteAEvaluar[2] = $accesibilidad_php;
        $estudianteAEvaluar[3] = $precio_php;
        $estudianteAEvaluar[4] = $clima_php;
        $estudianteAEvaluar[5] = $comida_php;
        $estudianteAEvaluar[6] = $conexion_internet_php;

        $todas_opciones = opiniones::all();

        //Calcula la cantidad de elementos que va a comparar
        $cantidad = count($estudianteAEvaluar);

        /** RECORRE LOS ELEMENTOS DE LA CONSULTA Y LOS ENVÍA A EVALUAR AL ALGORITMO DE EUCLIDES, RETORNA LAS DISTANCIAS Y ESTILOS */
        $distanciasEuclides = [];
        $distancias = [];
        $dist = [];
        $result = [];

        foreach ($todas_opciones as $opcion) {
            $opcionA[0] = $opcion->mejor_mes;
            $opcionA[1] = $opcion->alojamiento;
            $opcionA[2] = $opcion->accesibilidad;
            $opcionA[3] = $opcion->precio;
            $opcionA[4] = $opcion->clima;
            $opcionA[5] = $opcion->comida;
            $opcionA[6] = $opcion->conexion_internet;
            $opcionA[7] = $opcion->id_lugar;
            $distanciasEuclides[] = $this->calcularDistanciaEuclides($opcionA, $cantidad, $estudianteAEvaluar);
        }

        /** SEPARA DEL ARREGLO ANTERIOR, UNO PARA LAS DISTANCIAS Y OTRO PARA LOS resultados */
        $cant = count($distanciasEuclides);
        for ($i = 0; $i < $cant; $i++) {
            $distancias = $distanciasEuclides[$i];

            for ($j = 0; $j < count($distancias); $j++) {
                if ($j == 0) {
                    $dist[] = $distancias[$j];
                } else {
                    $result[] = $distancias[$j];
                }
            }
        }

        /** DETERMINA LA DISTANCIA MÁS CORTA*/
        $min = min($dist);
        $posMin = 0;
        //determina la posición de la distancia mínima, para ubicar el nombre del dato a buscar
        for ($i = 0; $i < count($dist); $i++) {
            if ($dist[$i] == $min)
                $posMin = $i;
        }

        //Manda a imprimir el resultado final.
        //RESULTADO
        if ($determinaResultado == 1) {
            $determinaResultado = 0;
        }

        $lugar = DB::table('lugares')->where('id_lugar', $result[$posMin])->first();
        return json_encode($lugar);
    }

    public function bayes(Request $request)
    {
        $latitud = $request->get('latitud');
        $longitud = $request->get('longitud');
        $id_lugar = $request->get('id_lugar');

        $rows = DB::select("SELECT `lug`, `freq`, `lati`, `longi` FROM `tablaFrecuenciasLugar` 
                WHERE `classId`=:id", ['id' => $id_lugar]);

        $lugares_distancias = array();
        foreach ($rows as $value) {
            $distancia = $this->distance($latitud, $longitud, $value->lati, $value->longi, 'K');
            array_push($lugares_distancias, ['lug' => $value->lug, 'distancia' => $distancia, 'freq' => $value->freq]);
        }

        $best_dist = array_column($lugares_distancias, 'distancia');

        array_multisort($best_dist, SORT_DESC, $lugares_distancias);

        $filtered = array();
        $results = array();
        foreach ($lugares_distancias as $key => $value) {
            if (!in_array($value['lug'], $filtered)) {
                array_push($filtered, $value['lug']);
                array_push($results, $value);
            }
        }

        $finales = array();
        foreach ($filtered as $value) {
            $row = DB::select("SELECT * FROM `lugares` WHERE `id_lugar`=:id", ['id' => $value]);
            array_push($finales, $row[0]);
        }

        return json_encode($finales);
    }

    private function calcularDistanciaEuclides($fila, $cantidad, $estudianteAEvaluar)
    {

        if ($fila != NULL) {

            $res = 0;

            for ($i = 0; $i < $cantidad - 1; $i++) {
                if ($i < $cantidad) {
                    //agrega en el arreglo del resultados la distancia obtenida
                    $res += pow($fila[$i] - $estudianteAEvaluar[$i], 2);
                }
            }
            $euc = sqrt($res);

            //crea un arreglo con el resultado de la distancia obtenida y el estilo en la columna evaluada
            $res = $euc;
            $distancias[] = $res;
            $distancias[] = $fila[$cantidad];
            return $distancias;
        }
    }


    private function distance($lat1, $lon1, $lat2, $lon2, $unit)
    {

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }
}

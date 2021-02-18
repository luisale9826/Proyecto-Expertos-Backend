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
        $data=null;
        if ($this->loadData() == null) {
            $data = $this->preCalculos();
        }
        $data = $this->loadData();
        //$mejor_mes = $request->get('mejor_mes');
        $alojamiento = $request->get('alojamiento');
        //$accesibilidad = $request->get('accesibilidad');
        //$precio = $request->get('precio');
        //$clima = $request->get('clima');
        //$comida = $request->get('comida');
        $conexion_internet = $request->get('conexion_internet');
        $latitud = $request->get('latitud');
        $longitud = $request->get('longitud');

        $probAtt = $data['probAtt'];
        $nClass = $data['countClass'];
        $m = $data['m'];


        $instances = $this->getClassInstances(
            $alojamiento,
            $conexion_internet,
            $latitud,
            $longitud
        );
        $probabilitiesPorFrecuencia = [];
        foreach ($instances as $key => $value) {
            $probF = [];
            foreach ($value as $key2 => $value) {
                $probF[$key2] = $this->calculoProbabilidad($value, $m, $probAtt[$key2], $nClass[$key]);
            }
            $probabilitiesPorFrecuencia[$key] = $probF;
        }

        $productoFrecuencias = [];
        foreach ($probabilitiesPorFrecuencia as $key => $value) {
            $productoFrecuencia = 1;
            foreach ($value as $keys => $value) {
                $productoFrecuencia = $productoFrecuencia * $value;
            }
            $productoFrecuencias[$key] = $productoFrecuencia;
        }

        $probClass = $data['probsClass'];
        $probabilidadesFinales = [];
        foreach ($productoFrecuencias as $key => $value) {
            $probabilidadesFinales[$key] = $value * $probClass[$key];
        }

        $resultadoFinal = null;
        $mayor = 0;
        foreach ($probabilidadesFinales as $key => $value) {
            if ($mayor == 0 || $value > $mayor) {
                $resultadoFinal = $key;
                $mayor = $value;
            }
        }

        $res = DB::table('lugares')->where('id_lugar', $resultadoFinal)->first();
        return json_encode($res);
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


    private function preCalculos()
    {
        $resultados = array();
        $countAttributes = $this->getCountAttributes();
        $resultados['countAttributes'] = $countAttributes;

        $countClass = $this->getCountClass();
        $resultados['countClass'] = $countClass;

        $rows = $this->getRowsQuantity();
        $resultados['rows'] = $rows;

        $probsClass = $this->getProbClass($rows, $countClass);
        $resultados['probsClass'] = $probsClass;

        $probAtt = $this->getProbAtt($rows, $countAttributes);
        $resultados['probAtt'] = $probAtt;

        $m = count($countAttributes);
        $resultados['m'] = $m;


        $file  = fopen('calculos-previos.json', 'w');
        fwrite($file, json_encode($resultados));
        return $resultados;
    }


    private function getProbClass($rows, $aClass)
    {
        $probClass = [];
        foreach ($aClass as $key => $value) {
            $probClass[$key] = $value / $rows;
        }
        return $probClass;
    }

    private function getProbAtt($rows, $aAtt)
    {
        $probAtt = [];
        foreach ($aAtt as $key => $value) {
            $probAtt[$key] = 1 / $value;
        }
        return $probAtt;
    }

    // Este método se encarga de cargar los datos
    // Retorna null si el número de filas en la BD cambio, sino retorna los datos
    public function loadData()
    {
        $strJsonFileContents = file_get_contents("calculos-previos.json");
        $array = json_decode($strJsonFileContents, true);

        $rows = $this->getRowsQuantity();
        if ($array == null || $rows != $array["rows"]) {
            return null;
        }
        return $array;
    }


    public function getCountAttributes()
    {
        $row = DB::select(
            "SELECT 
        COUNT(DISTINCT `alojamiento`) AS alojamiento, 
        COUNT(DISTINCT `conexion_internet`) as conexion_internet, 
        COUNT(DISTINCT latitud) AS latitud, 
        COUNT(DISTINCT longitud) AS longitud 
        FROM `opiniones` 
        INNER JOIN lugares ON lugares.id_lugar=opiniones.id_lugar"
        );

        if ($row !== null) {
            foreach ($row as $value) {
                $array = [
                    "alojamiento" => $value->alojamiento,
                    "conexion_internet" => $value->conexion_internet,
                    "latitud" => $value->latitud,
                    "longitud" => $value->longitud
                ];
            }

            return $array;
        }
        return null;
    }

    public function getCountClass()
    {
        $countClass = [];
        $rows = DB::select("SELECT COUNT(*) as filas from lugares");
        $filas = $rows[0]->filas;
        for ($i = 1; $i <= $filas; $i++) {
            $row = DB::select("SELECT COUNT(*) as r from opiniones where id_lugar = :id", ['id' => $i]);
            if ($row !== null) {
                $countClass[$i] = $row[0]->r;
            }
        }

        return $countClass;
    }

    public function getRowsQuantity()
    {

        $rows = DB::select("SELECT COUNT(*) as 'ROWS' FROM opiniones");

        if ($rows !== null) {
            return $rows[0]->ROWS;
        } else {
            echo "0 results";
        }
        return null;
    }

    public function getClassInstances(
        $alojamiento,
        $conexion_internet,
        $latitud,
        $longitud
    ) {
        $rows = DB::select("SELECT COUNT(*) as filas from lugares");
        $filas = $rows[0]->filas;
        $frequencies = [];
        $att = array(
            "alojamiento" => $alojamiento,
            "conexion_internet" => $conexion_internet,
            "latitud" => $latitud,
            "longitud" => $longitud
        );
        for ($i = 1; $i <= $filas; $i++) {
            $results = [];
            foreach ($att as $key => $value) {
                $results[$key] = $this->getInstancesByClass($i, $key, $value);
            }
            $frequencies[$i] = $results;
        }
        return $frequencies;
    }

    private function getInstancesByClass($id, $att, $attValue)
    {
        
        $row = DB::select("SELECT COUNT(opiniones.id_lugar) as id_lugar FROM opiniones 
        INNER JOIN lugares ON lugares.id_lugar=opiniones.id_lugar
         WHERE(opiniones.id_lugar = :id && {$att} = :att)", ['id' => $id, 'att' => $attValue]);
        if ($row !== null) {
            return $row[0]->id_lugar;
        }
        return null;
    }

    private function calculoProbabilidad($instacia, $m, $prob, $n) {
        return ($instacia+$m*$prob)/($n+$m);
    }
}

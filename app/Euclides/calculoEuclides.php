<?php

 /** CONEXIÓN CON LA BD ...*/
 $conexion=(mysqli_connect("remotemysql.com","ZK5BCXTBqc","rYaKhIZmr9"));
 mysqli_select_db($conexion,'ZK5BCXTBqc') or die ("no se encuentra la bd");

if(!empty($_POST)){
        // 'mejor_mes', 'alojamiento', 'accesibilidad', 'precio', 'clima', 'comida', 'conexion_internet', 'id_lugar'
    if(!empty($_POST['mejor_mes']) && !empty($_POST['alojamiento']) && !empty($_POST['accesibilidad']) && 
        !empty($_POST['precio'])&& !empty($_POST['clima'])&& !empty($_POST['comida'])&& 
        !empty($_POST['conexion_internet'])&& !empty($_POST['id_lugar'])
        ){

        //

        $determinaResultado =1; 

        /** LLENA LAS VARIABLES DESDE EL FORMULARIO */

        // 'mejor_mes', 'alojamiento', 'accesibilidad', 'precio', 'clima', 'comida', 'conexion_internet', 'id_lugar'
        $mejor_mes_php = $_POST['mejor_mes'];
        $alojamiento_php = $_POST['alojamiento'];
        $accesibilidad_php = $_POST['accesibilidad'];
        $precio_php = $_POST['precio'];
        $clima_php = $POST['clima'];
        $comida_php = $POST['comida'];
        $conexion_internet_php = $POST['conexion_internet'];
        $id_lugar_php = $POST['id_lugar'];

        $estudianteAEvaluar[0] = $mejor_mes_php;
        $estudianteAEvaluar[1] = $alojamiento_php;
        $estudianteAEvaluar[2] = $accesibilidad_php;
        $estudianteAEvaluar[3] = $precio_php;
        $estudianteAEvaluar[4] = $clima_php;
        $estudianteAEvaluar[5] = $comida_php;
        $estudianteAEvaluar[6] = $conexion_internet_php;
        $estudianteAEvaluar[7] = $id_lugar_php;

        /** CONSULTA QUE TRAE LOS DATOS A EVALUAR */
        $sql="SELECT `id_opinion`, `mejor_mes`, `alojamiento`, `accesibilidad`, `precio`, `clima`, `comida`, `conexion_internet`, `id_lugar` FROM `opiniones`";
        $consulta=mysqli_query($conexion,$sql) or die(mysqli_error());
        $row = mysqli_fetch_array($consulta, MYSQLI_ASSOC);
    }

    //Calcula la cantidad de elementos que va a comparar
    $cantidad = count($estudianteAEvaluar);

    /** RECORRE LOS ELEMENTOS DE LA CONSULTA Y LOS ENVÍA A EVALUAR AL ALGORITMO DE EUCLIDES, RETORNA LAS DISTANCIAS Y ESTILOS */
    $distanciasEuclides = [];  $distancias = [];  $dist = [];  $result = []; 
    while ($fila = mysqli_fetch_array($consulta)){
        //llama al algoritmo de euclides
    $distanciasEuclides[] = calcularDistanciaEuclides($fila, $cantidad, $estudianteAEvaluar);
    }

    /** SEPARA DEL ARREGLO ANTERIOR, UNO PARA LAS DISTANCIAS Y OTRO PARA LOS resultados */
    $cant = count($distanciasEuclides);
    for ($i=0; $i < $cant; $i++) { 
        $distancias = $distanciasEuclides[$i];

        for ($j=0; $j < count($distancias); $j++) { 
            if($j==0){
                $dist []=$distancias[$j];
            }else{
                $result[] = $distancias[$j];
            }
        }
    }

    /** DETERMINA LA DISTANCIA MÁS CORTA*/
    $min=min($dist);
    $posMin=0;
    //determina la posición de la distancia mínima, para ubicar el nombre del dato a buscar
    for($i=0;$i<count($dist);$i++){
        if($dist[$i]==$min)
            $posMin=$i;
    }

    //Manda a imprimir el resultado final.
    //RESULTADO
    if($determinaResultado==1){
        print_r("Resultado según el algoritmo de euclides es: ".$result[$posMin]);
        $determinaResultado = 0;
    }

}else{
mysqli_close($conexion);
echo "Datos invalidos";
}

function calcularDistanciaEuclides ($fila, $cantidad, $estudianteAEvaluar) {

    if ($fila !=NULL) {

        $res=0;

        for ($i=0; $i < $cantidad-1; $i++) { 
            if($i<$cantidad){
                //agrega en el arreglo del resultados la distancia obtenida
                $res += pow($fila[$i]-$estudianteAEvaluar[$i] ,2);
            }
        }
        $euc = sqrt($res);

        //crea un arreglo con el resultado de la distancia obtenida y el estilo en la columna evaluada
        $distancias [] = $res;
        $distancias []= $fila[$cantidad];
        return $distancias;
    }
}
?>
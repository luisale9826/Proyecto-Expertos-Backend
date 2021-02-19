<?php
     /** CONEXIÓN CON LA BD ...*/
    $conexion=(mysqli_connect("remotemysql.com","ZK5BCXTBqc","rYaKhIZmr9"));
    mysqli_select_db($conexion,'ZK5BCXTBqc') or die ("no se encuentra la bd");

     /** CONSULTA QUE TRAE LOS DATOS A EVALUAR */
     $sql="SELECT l.categoria, o.alojamiento, o.accesibilidad, o.precio, o.comida, o.id_lugar, l.latitud, l.longitud FROM `opiniones` o JOIN  `lugares` l ON o.`id_lugar` = l.`id_lugar`";
     $consulta=mysqli_query($conexion,$sql) or die(mysqli_error());

     $arrayInsertar[]=0;
     $arrayTuplas[]=0;

     $row=0;
     //Inicializo variables para calcular ai
     $fila[]=0;
     $ai[]=0;

     //clases
     $aiMon[]=0;
     $aiMon[]=0;
     $aiRur[]=0;

     //Atributos
     $aiAl[]=0;
     $aiAc[]=0;
     $aiPr[]=0;
     $aiCo[]=0;

     $aiIdLugar=[];
     $aiLatitud=[];
     $aiLongitud=[];

    //Para las repeticiones de los valores únicos de los atributos
    $aiAlU[]=0;
    $aiAcU[]=0;
    $aiPrU[]=0;
    $aiCoU[]=0;

    $aiIdLugarU=[];
    $aiLatitudU=[];
    $aiLongitudU=[];

    //Asigno a las clases un arreglo para cada atributo
    $aiMon=$aiAl;
    $aiMon=$aiAc;
    $aiMon=$aiPr;
    $aiMon=$aiCo;
    $aiMon=$aiIdLugar;
    $aiMon=$aiLatitud;
    $aiMon=$aiLongitud;

    $aiMon=$aiAl;
    $aiMon=$aiAc;
    $aiMon=$aiPr;
    $aiMon=$aiCo;
    $aiMon=$aiIdLugar;
    $aiMon=$aiLatitud;
    $aiMon=$aiLongitud;

    $aiRur=$aiAl;
    $aiRur=$aiAc;
    $aiRur=$aiPr;
    $aiRur=$aiCo;
    $aiRur=$aiIdLugar;
    $aiRur=$aiLatitud;
    $aiRur=$aiLongitud;

    //Asigno a las clases un arreglo para cada atributo con sus valores únicos
    $aiMon=$aiAlU;
    $aiMon=$aiAcU;
    $aiMon=$aiPrU;
    $aiMon=$aiCoU;
    $aiMon=$aiIdLugarU;
    $aiMon=$aiLatitudU;
    $aiMon=$aiLongitudU; 

    $aiMon=$aiAlU;
    $aiMon=$aiAcU;
    $aiMon=$aiPrU;
    $aiMon=$aiCoU;
    $aiMon=$aiIdLugarU;
    $aiMon=$aiLatitudU;
    $aiMon=$aiLongitudU; 



    //agrego las clases al array ai
    $ai=$aiMon;
    $ai=$aiMon;
    $ai=$aiRur;

    //Variables para calcular la cantidad de ocurrencias de cada clase (n)
    $ctMon=0;
    $ctPla=0;
    $ctRur=0;

    $contador=0;


    //Selecciona los datos de la base de datos y los categoriza
    while (($row = mysqli_fetch_array($consulta , MYSQLI_ASSOC))!=NULL){ 
        $fila[$contador]=$row;
        if($row['categoria']=='Montaña'){
            
            //Cuenta la cantidad de instancias a la clase
            $ctPla++;
            //Recolecta todos los valores del atributo, para esta clase
            $ai['aiMon']['aiAl'][$contador]=$row['alojamiento'];
            $ai['aiMon']['aiAc'][$contador]=$row['accesibilidad'];
            $ai['aiMon']['aiPr'][$contador]=$row['precio'];
            $ai['aiMon']['aiCo'][$contador]=$row['comida'];

            $ai['aiMon']['aiIdLugar'][$contador]=$row['id_lugar'];
            $ai['aiMon']['aiLatitud'][$contador]=$row['latitud'];
            $ai['aiMon']['aiLongitud'][$contador]=$row['longitud'];

            $contador++;
        }

    }


     // var_dump($ai);

        //Valores posibles para cada atributo
        $unicValPla=[];

        //Playa
        $unicValPla[]=array_unique($ai['aiMon']['aiAl']);
        $unicValPla[]=array_unique($ai['aiMon']['aiAc']);
        $unicValPla[]=array_unique($ai['aiMon']['aiPr']);
        $unicValPla[]=array_unique($ai['aiMon']['aiCo']);

        $unicValPla[]=array_unique($ai['aiMon']['aiIdLugar']);
        $unicValPla[]=array_unique($ai['aiMon']['aiLatitud']);
        $unicValPla[]=array_unique($ai['aiMon']['aiLongitud']);


        //probabilidad es 1/cantidadPosibles  $prob=1/#

        //m es la cantidad de atributos
        $m=4;

        $veces=0;
        //recorre cada clase, cuenta los valores distintos para cada atributo y calcula las frecuencias
        foreach ($ai as $posicion => $valor) {

            if($posicion=='aiMon'){
                
                //Cuenta la cantidad de valores únicos para cada atributo para los lugares de playa  
                foreach ($ai['aiMon'] as $pos => $val) {
                    if($pos=='aiAl'){
                        $probAl=1/2;
                        $valorContar=0;
                        $repeticiones=0;
                        $p=0;
                        for ($o=0; $o <= 1400; $o++) { //
                            //para solo hacer el cálculo en las de PLAYA
                            if(!empty($unicValPla[0][$o])){
                                $valorContar = $unicValPla[0][$o];
                                foreach ($ai['aiMon']['aiAl'] as $p => $v) {
                                    if(!empty($ai['aiMon']['aiAl'][$p])){
                                        if($valorContar==$ai['aiMon']['aiAl'][$p]){
                                            $repeticiones++;
                                        }
                                    }
                                }

                                foreach ($ai['aiMon']['aiAl'] as $p => $v) {
                                    if(!empty($ai['aiMon']['aiAl'][$p])){
                                        if($valorContar==$ai['aiMon']['aiAl'][$p]){
                                            //calcula la frecuencia para cada atributo de la clase, redondeado a 6 dígitos
                                            $nxp=($m*$probAl); 
                                            $ncplusnxp=$repeticiones+$nxp; 
                                            $nplusm=($ctPla+$m); 
                                            $freq=($ncplusnxp/$nplusm); 
                
                                            $ai['aiMon']['aiAlU'][$p]=round($freq,10);
                                            
                                        }
                                    }else if(empty($ai['aiMon']['aiAl'][$p])){
                                            //calcula la frecuencia para cada atributo de la clase, redondeado a 6 dígitos
                                            $nxp=($m*$probAl); 
                                            $ncplusnxp=0+$nxp; 
                                            $nplusm=($ctPla+$m); 
                                            $freq=($ncplusnxp/$nplusm); 
                
                                            $ai['aiMon']['aiAlU'][$p]=round($freq,10);
                                    }
                                }
  
                            }

                      //      print_r('Id lugar: '.$ai['aiMon']['aiIdLugar'][$o]);
                        }
                    }
                    else if($pos=='aiAc'){
                        $probAc=1/3;
                        $valorContar=0;
                        $repeticiones=0;
                        for ($o=0; $o <= 1400; $o++) { //640
                            //para solo hacer el cálculo en las de montaña
                            if(!empty($unicValPla[1][$o])){
                                $valorContar = $unicValPla[1][$o];
                                foreach ($ai['aiMon']['aiAc'] as $p => $v) {
                                    if(!empty($ai['aiMon']['aiAc'][$p])){
                                        if($valorContar==$ai['aiMon']['aiAc'][$p]){
                                            $repeticiones++;
                                        }
                                    }
                                }

                                foreach ($ai['aiMon']['aiAc'] as $p => $v) {
                                    if(!empty($ai['aiMon']['aiAc'][$p])){
                                        if($valorContar==$ai['aiMon']['aiAc'][$p]){
                                            //calcula la frecuencia para cada atributo de la clase, redondeado a 6 dígitos
                                            $nxp=($m*$probAc); 
                                            $ncplusnxp=$repeticiones+$nxp; 
                                            $nplusm=($ctPla+$m); 
                                            $freq=($ncplusnxp/$nplusm); 
                
                                            $ai['aiMon']['aiAcU'][$p]=round($freq,10);
                                        }
                                    }else if(empty($ai['aiMon']['aiAc'][$p])){
                                            //calcula la frecuencia para cada atributo de la clase, redondeado a 6 dígitos
                                            $nxp=($m*$probAc); 
                                            $ncplusnxp=0+$nxp; 
                                            $nplusm=($ctPla+$m); 
                                            $freq=($ncplusnxp/$nplusm); 
                
                                            $ai['aiMon']['aiAcU'][$p]=round($freq,10);
                                    }
                                }
  
                            }
                        }
                    }
                    else if($pos=='aiPr'){
                        $probPr=1/5;
                        $valorContar=0;
                        $repeticiones=0;
                        for ($o=0; $o <= 1400; $o++) { //640
                            //para solo hacer el cálculo en las de playa
                            if(!empty($unicValPla[2][$o])){
                                $valorContar = $unicValPla[2][$o];
                                foreach ($ai['aiMon']['aiPr'] as $p => $v) {
                                    if(!empty($ai['aiMon']['aiPr'][$p])){
                                        if($valorContar==$ai['aiMon']['aiPr'][$p]){
                                            $repeticiones++;
                                        }
                                    }
                                }

                                foreach ($ai['aiMon']['aiPr'] as $p => $v) {
                                    if(!empty($ai['aiMon']['aiPr'][$p])){
                                        if($valorContar==$ai['aiMon']['aiPr'][$p]){
                                            //calcula la frecuencia para cada atributo de la clase, redondeado a 6 dígitos
                                            $nxp=($m*$probPr); 
                                            $ncplusnxp=$repeticiones+$nxp; 
                                            $nplusm=($ctPla+$m); 
                                            $freq=($ncplusnxp/$nplusm); 
                
                                            $ai['aiMon']['aiPrU'][$p]=round($freq,10);
                                        }
                                    }else if(empty($ai['aiMon']['aiPr'][$p])){
                                            //calcula la frecuencia para cada atributo de la clase, redondeado a 6 dígitos
                                            $nxp=($m*$probPr); 
                                            $ncplusnxp=0+$nxp; 
                                            $nplusm=($ctPla+$m); 
                                            $freq=($ncplusnxp/$nplusm); 
                
                                            $ai['aiMon']['aiPrU'][$p]=round($freq,10);
                                    }
                                }
  
                            }
                        }
                    }
                    else if($pos=='aiCo'){


                        $probCo=1/3;
                        $valorContar=0;
                        $repeticiones=0;
                        for ($o=0; $o <= 10000; $o++) { //640
                            //para solo hacer el cálculo en las de playa
                            if(!empty($unicValPla[3][$o])){
                                $valorContar = $unicValPla[3][$o];
                                foreach ($ai['aiMon']['aiCo'] as $p => $v) {

                                    if(!empty($ai['aiMon']['aiCo'][$p])){
//                                        print_r('</br>'.$unicValPla[4][$p]);

                                        if($valorContar==$ai['aiMon']['aiCo'][$p]){
                                            $repeticiones++;
                                        }
                                    }
                                }

                                


                                foreach ($ai['aiMon']['aiCo'] as $p => $v) {
                                    if(!empty($ai['aiMon']['aiCo'][$p])){
                                        if($valorContar==$ai['aiMon']['aiCo'][$p]){
                                            //calcula la frecuencia para cada atributo de la clase, redondeado a 6 dígitos
                                            $nxp=($m*$probCo); 
                                            $ncplusnxp=$repeticiones+$nxp; 
                                            $nplusm=($ctPla+$m); 
                                            $freq=($ncplusnxp/$nplusm); 
                
                                            $ai['aiMon']['aiCoU'][$p]=round($freq,10);
                                        }
                                    }else if(empty($ai['aiMon']['aiCo'][$p])){
                                            //calcula la frecuencia para cada atributo de la clase, redondeado a 6 dígitos
                                            $nxp=($m*$probCo); 
                                            $ncplusnxp=0+$nxp; 
                                            $nplusm=($ctPla+$m); 
                                            $freq=($ncplusnxp/$nplusm); 
                
                                            $ai['aiMon']['aiCoU'][$p]=round($freq,10);
                                    }

                                }
  
                            }
                        }

                    }
                }
            }
        }

        //asigna los lugares
            foreach ($ai['aiMon'] as $key => $value) { //corre los 7 atributos
                foreach ($value as $k => $v) {//corre el arreglo de cada atributo
                    $ai['aiMon']['aiIdLugarU'][$v]=$ai['aiMon']['aiIdLugar'][$k];
                    $ai['aiMon']['aiLatitudU'][$v]=$ai['aiMon']['aiLatitud'][$k];
                    $ai['aiMon']['aiLongitudU'][$v]=$ai['aiMon']['aiLongitud'][$k];
                }                            
            }

        $categId=2;

        //Se recorre todos los valores para insertarlos en una tabla en la BD
        foreach ($ai as $position => $clase) {
            
            if($position=='aiMon'){
                $categId=1;
            }else if($position=='aiMon'){
                $categId=2;
            }else if($position=='aiRur'){
                $categId=3;
            }

           //accede a los 8 arrays con valores 
      
            foreach ($ai[$position] as $positi => $atributo) {
                //rellena con 0, los valores vaciós en el arreglo
                for ($x=0; $x <=1400 ; $x++) { 
                    if(empty($ai[$position][$positi][$x])){
                        $ai[$position][$positi][$x]=0;
                    }
                }

                //decodifica y asigna Id a los atributos
                if($positi=='aiAl'){
                    $atribId=1;
                }else if($positi=='aiAc'){
                    $atribId=2;
                }else if($positi=='aiPr'){
                    $atribId=3;
                }else if($positi=='aiCo'){
                    $atribId=4;
                }

                $count=0;
                //recorre los atributos y almacena en la tabla
                foreach ($ai[$position][$positi] as $key => $value) {
                    if($positi!='aiAlU'&& $positi!='aiAcU'&& $positi!='aiPrU'&& $positi!='aiCoU'
                    &&$positi!='aiIdLugarU'&&$positi!='aiLatitudU'&&$positi!='aiLongitudU'
                    &&$positi!='aiIdLugar'&&$positi!='aiLatitud'&&$positi!='aiLongitud'
                    ){
                  
                     if(!empty($ai[$position][$positi.'U'][$value])){
                        $freq=$ai[$position][$positi.'U'][$value];
                        $lug=$ai[$position]['aiIdLugarU'][$value];
                        $lati=$ai[$position]['aiLatitudU'][$value];
                        $long=$ai[$position]['aiLongitudU'][$value];

           //             print_r('</br> classId: '.$categId.' class: '.$position.' atribId: '.$atribId.' atrib: '.$positi);
             //           print_r(' val: '.$value);
               //          print_r(' val: '.$value.' freq: '.$ai[$position][$positi.'U'][$value]);

                         array_push($arrayTuplas, array( 
                         "classId" => $categId, 
                         "class" => $position, 
                         "atribId" => $atribId, 
                         "atrib" => $positi, 
                         "val" => $value, 
                         "freq" => $freq,
                         "lug" => $lug,
                         "lati" => $lati,
                         "long" => $long
                     ));
                     }

                    }
                }
                
                
            }
        }

        unset($arrayTuplas[0]);
    $arrayTuplas=array_unique($arrayTuplas, SORT_REGULAR);

    $cant=0;
    foreach ($arrayTuplas as $posi => $valu) {
        $cant++;
            
        $estiloId=$valu['classId'];
        $position=$valu['class'];
        $atribId=$valu['atribId'];
        $positi=$valu['atrib'];
        $value=$valu['val'];
        $freq=$valu['freq'];
        $lug=$valu['lug'];
        $lati=$valu['lati'];
        $longi=$valu['long'];

   // /*  
        print_r('</br></br>'.$cant.' classId: '.$estiloId.' class: '.$position.' atribId: '.$atribId.' atrib: '.$positi);
        print_r(' val: '.$value.' freq: '.$freq.' lug: '.$lug.' lati: '.$lati.' long: '.$longi);

 // */
        /** CONSULTA QUE INSERTA LAS FRECUENCIAS Y DEMÁS DATOS */
   //     $sql="INSERT INTO `tablaFrecuenciasLugar`(`classId`, `class`, `atribId`, `atrib`, `val`, `freq`, `lug`, `lati`, `longi`) VALUES ('$estiloId', '$position', '$atribId', '$positi', '$value', '$freq', '$lug', '$lati', '$longi')";
   //     $consulta=mysqli_query($conexion,$sql) or die(mysqli_error());
    }

    print_r('TODO K0');
?>
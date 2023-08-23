<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
Load::lib('upload');
Load::lib('excel');
Load::model('producto'); 
class productoController extends AppController{
    
    /**
     * Muestra el partial de las nominas generadas
     */
    public function index() {
        $producto = new producto();
        $this->result = $producto->listar();
        $this->campos = array(
            utf8_encode('ID') => 'id',
            utf8_encode('CLAVE') => 'clave',
            utf8_encode('DESCRIPCION') => 'nombre',
            utf8_encode('TIPO') => 'presentacion',
            utf8_encode('PRESENTACION') => 'calidad',
            utf8_encode('PREPARACION') => 'preparacion',
            utf8_encode('PESO BRUTO') => 'peso',
            utf8_encode('EXISTENCIA') => 'existencia'
            
        );
        $this->encabezado= "PRODUCTOS";
    }
    
    public function crear(){
         $this->accion="crear";
        
        $producto = new producto();
        $this->producto = $producto->find();

        if (Input::hasPost('producto')) {

            $this->producto = $producto->guardarDatos();
        }
    }
    
    public function editar($id = null) {
        $this->accion = 'editar';
        $producto = new producto();
        if ($id != null) {
            $this->producto = $producto->find($id);
        } else if (Input::hasPost('producto')) {
            if($producto->NUMERO_SERIE=="S"){
         $producto->lOTE="N";   
        }
         $producto->USUARIO_ID=Session::get('id');
            if ($producto->update(Input::post('producto'))) {
                
               
                Redirect::to('producto');
                Flash::valid('Registro Actualizado');
                Input::delete();
            } else {
                $this->producto = Input::post('producto');
                Flash::error('Falló Operación');
            }
        }
        view::select('crear');
    }
    public function eliminar($id) {
        
        $producto = new producto();
        $producto = $producto->find_first($id);
        $producto->estatus='0';
        $producto->update();
         Redirect::to('producto');
            
        
        
       
    }
     public function importar() {
   
    
}
 public function leerArchivo() {
     Session::delete('array_datos');
        $no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
$permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
$this->camino="";
       $this->camino.="";
        $numeroDigitos = 0;
        $contadorError = 0;
        $error = "";
        $nuevo = 0;
        $actualiza = 0;
        $msj="";
        $this->camino.="";
        if (Input::hasPost('oculto')) {  //para saber si se envió el form
            $dir = "files/upload";
            
            $nombre = $_FILES['file'];
            $new_name = $nombre['name'];
            $archivo = Upload::factory('file'); //llamamos a la libreria y le pasamos el nombre del campo file del formulario
            $archivo->setExtensions(array('xlsx', 'xls')); //le asignamos las extensiones a permitir
            if ($archivo->isUploaded()) {
                if ($archivo->save()) {
                    
                    error_reporting(E_ALL ^ E_NOTICE);
                    $reader = new Spreadsheet_Excel_Reader();
                   // $reader->setUTFEncoder('UTF8');
                   // $reader->setOutputEncoding('UTF­8');
                    $reader->read("$dir/$new_name");
                    $band = false;
                    for ($i = 6; $i <= 65535; $i++) {// EMPEZAMOS A LEER DESDE LA SEGUNDA LINEA 'POR QUE LA 1° SON LOS TITULOS'
                        if ($band == false) {
                           $this->camino.="entro a leer--";
                            //str_replace($no_permitidas, $permitidas ,$conceptoPoliza);
                            $texto1 = utf8_decode(trim($reader->sheets[0]['cells'][$i][2]));
                            $texto2 = trim($reader->sheets[0]['cells'][$i][3]);
                            $texto3 = trim($reader->sheets[0]['cells'][$i][4]);
                            $texto4 = trim($reader->sheets[0]['cells'][$i][5]);
                            $texto5=$reader->sheets[0]['cells'][$i][6];
                            $texto6 = trim($reader->sheets[0]['cells'][$i][7]);
                            $texto7=trim($reader->sheets[0]['cells'][$i][8]);
                            $texto8=trim($reader->sheets[0]['cells'][$i][9]);
                            $texto9=trim($reader->sheets[0]['cells'][$i][10]);
                            $texto10=trim($reader->sheets[0]['cells'][$i][11]);
                            $texto11=trim($reader->sheets[0]['cells'][$i][12]);
                            $texto12=trim($reader->sheets[0]['cells'][$i][13]);
                            $texto13=trim($reader->sheets[0]['cells'][$i][14]);
                            $texto14=trim($reader->sheets[0]['cells'][$i][15]);
                            $texto15=trim($reader->sheets[0]['cells'][$i][16]);
                            $texto16=trim($reader->sheets[0]['cells'][$i][17]);
                            $texto17=trim($reader->sheets[0]['cells'][$i][18]);
                             $texto18=trim($reader->sheets[0]['cells'][$i][19]);
                            

                            
                            

                           


                            $array_datos[] = array(
                            'texto1' => $texto1,
                            'texto2' => $texto2,
                            'texto3' => $texto3,
                            'texto4'=> $texto4,
                            'texto5'=> $texto5,
                            'texto6'=> $texto6, 
                            'texto7' => $texto7,
                            'texto8' => $texto8,
                            'texto9' => $texto9,
                            'texto10'=> $texto10,
                            'texto11'=> $texto11,
                            'texto12'=> $texto12, 
                            'texto13' => $texto13,
                            'texto14' => $texto14,
                            'texto15' => $texto15,
                            'texto16'=> $texto16,
                            'texto17'=> $texto17,
                                    'texto18'=> $texto18
                            
                            
                            );
                            $c = $i + 1;
                            if ($reader->sheets[0]['cells'][$c][4] == "")
                                $i = 65535; //LEEMOS HASTA EL FINAL, PERO SI ENCUENTRA UN ESPACIO BASIO TERMINA DE LEER
                        }

                        Session::set('array_datos', $array_datos);
                    }
                    
                }
              unlink("$dir/$new_name");
            }else {
                Flash::warning('No se ha Podido Subir el Archivo...!!!');
            }
        }
        
    
    }

    public function generaRegistro() {
	
        $this->insertaArreglo();
    }
   public function bloquear($id) {
	
        $producto=new producto();
       $producto= $producto->find_first($id);
       $producto->estatus='0';
       $producto->update();
       Redirect::to('producto'); 
    }
    public function insertaArreglo() {
       
        $array_datos = Session::get("array_datos");
        $tamano = count($array_datos);
       $msj="";
      $si=0;
      $no=0;
         $idEntidad =Session::get('entidad_id');  
        for ($i = 0; $i < $tamano; $i++) {
            
  $existe = new producto();
    $codigo=$array_datos[$i]['texto1'];
    $categori=trim($array_datos[$i]['texto3']);
    $presentacion=trim($array_datos[$i]['texto4']);
    $preparacio=trim($array_datos[$i]['texto5']);
    $empaqu=trim($array_datos[$i]['texto6']);
    $unidad=trim($array_datos[$i]['texto7']);
            
            if ($categori!=""){
            $existe=new presentacion();
            $existeCategoria = $existe->exists("descripcion='$categori'");
            if ($existeCategoria == 0) {
              $categoria=$existe;
              $categoria->descripcion=utf8_encode($categori);
              $categoria->estatus='1';
              $categoria->save();
            }else{
              $categoria=$existe->find_first("descripcion='$categori'");  
            }
            }
            
             if ($presentacion!=""){
            $arreglo=explode("-", $presentacion);
            $existe=new calidad();
            $existeCalidad = $existe->exists("descripcion='$arreglo[1]' and codigo='$arreglo[0]'");
            if ($existeCalidad == 0) {
              $presentacion=$existe;
              $presentacion->descripcion=utf8_encode($arreglo[1]);
              $presentacion->codigo=$arreglo[0];
              $presentacion->estatus='1';
              $presentacion->save();
            }else{
               $presentacion= $arreglo[1];
              $presentacion=$existe->find_first("descripcion='$presentacion'");  
            }
             }
             
            if ($preparacio!=""){ 
            $existe=new preparacion();
            $existePreparacion = $existe->exists("descripcion='$preparacio'");
            if ($existePreparacion == 0) {
              $preparacion=$existe;
              $preparacion->descripcion=utf8_encode($preparacio);
              $preparacion->estatus='1';
              $preparacion->save();
            }else{
              $preparacion=$existe->find_first("descripcion='$preparacio'");  
            }
            }
            
            if ($empaqu!=""){
            $existe=new embalaje();
            $existeEmpaque = $existe->exists("descripcion='$empaqu'");
            if ($existeEmpaque == 0) {
              $empaque=$existe;
              $empaque->descripcion=utf8_encode($empaqu);
              $empaque->estatus='1';
              $empaque->save();
            }else{
              $empaque=$existe->find_first("descripcion='$empaqu'");  
            }
            }
            
            if ($unidad!=""){
            $existe=new medida();
            $existeMedida = $existe->exists("descripcion='$unidad'");
            if ($existeMedida == 0) {
              $medida=$existe;
              $medida->descripcion=utf8_encode($unidad);
              $medida->estatus='1';
              $medida->save();
            }else{
              $medida=$existe->find_first("descripcion='$unidad'");  
            }
            }
            
            $existe=new producto();
            $existeCuenta = $existe->exists("clave ='$codigo'");
            if ($existeCuenta == 0) {
            $detalleplaga = new producto();
            $detalleplaga->clave = $codigo;
            $detalleplaga->descripcion = utf8_encode($array_datos[$i]['texto2']);
            $detalleplaga->presentacion_id  = $categoria->id;
            $detalleplaga->calidad_id = $presentacion->id;
            $detalleplaga->preparacion_id= $preparacion->id;;
            $detalleplaga->empaque_id = $empaque->id;
            $detalleplaga->medida_id = $medida->id;
            $detalleplaga->peso = $array_datos[$i]['texto8'];
            $detalleplaga->unidad_empaque = $array_datos[$i]['texto9'];
            $detalleplaga->peso_empaque = $array_datos[$i]['texto10'];
             $detalleplaga->precio = $array_datos[$i]['texto11'];
            $detalleplaga->impuesto = $array_datos[$i]['texto12'];
            $detalleplaga->existencia ='0';
            $detalleplaga->save();
            
            $si++;
        }else{
            $detalleplaga = new producto();
            $detalleplaga=$detalleplaga->find_first("clave='$codigo'");
            $detalleplaga->clave = $codigo;
            $detalleplaga->descripcion = utf8_encode($array_datos[$i]['texto2']);
            $detalleplaga->presentacion_id  = $categoria->id;
            $detalleplaga->calidad_id = $presentacion->id;
            $detalleplaga->preparacion_id= $preparacion->id;;
            $detalleplaga->empaque_id = $empaque->id;
            $detalleplaga->medida_id = $medida->id;
            $detalleplaga->peso = $array_datos[$i]['texto8'];
            $detalleplaga->unidad_empaque = $array_datos[$i]['texto9'];
            $detalleplaga->peso_empaque = $array_datos[$i]['texto10'];
             $detalleplaga->precio = $array_datos[$i]['texto11'];
            $detalleplaga->impuesto = $array_datos[$i]['texto12'];
            $detalleplaga->update();
           
            $no++;
        }
        
    }
    $this->msj.="Se insertaron: ".$si. "<br/> Se Actualizaron: ".$no." porque ya existe el codigo del Producto ";
        
        Session::delete('mensajeError');
        Session::delete('mensaje');
    }
}
?>

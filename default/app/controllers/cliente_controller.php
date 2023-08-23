<?php
ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

//error_reporting(E_ALL);



Load::lib('upload');
Load::lib('excel');




class clienteController extends AppController {

    protected function before_filter() {
        $this->limit_params = false;
        // Si es AJAX enviar solo el view
        if (Input::isAjax()) {
            View::template(NULL);
        }
    }

    public function index() {
        $this->cliente = Load::model('cliente')->find();
        $this->campos = array(
            utf8_encode('CLAVE') => 'clave',
            utf8_encode('RFC') => 'rfc',
            utf8_encode('NOMBRE') => 'nombrecompleto',
            utf8_encode('PROPIETARIO/REPRESENTANTE LEGAL') => 'propietario',
            utf8_encode('Email') => 'correoelectronico',
            utf8_encode('TELEFONO') => 'telefono'
        );
        $cliente=new cliente();
        $this->result = $cliente->listarInactivo();
        $this->encabezado = "";
    }

    public function crear() {
        
        $this->accion = "crear";
        $this->prefijo = "cliente";
         $this->accion="crear";
        
        $cliente = new cliente();
        $this->cliente = $cliente->find();

        if (Input::hasPost('cliente')) {

            $this->cliente = $cliente->guardarDatos();
        }
    }

    public function editar($id = null) {
        View::select('crear');
        $this->prefijo = "cliente";
        $this->accion = "editar";
        if ($id != NULL) {
            $this->cliente = Load::model('cliente')->find_first($id);
          
        } else  {
           $datosc = new cliente(Input::post('cliente'));
            
            if ($datosc->update())
                echo "<script>  alert ('Registro Actualizado....!');</script>"; 
            else
               echo "<script>  alert ('Problemas al Actualizar el Registro....!');</script>";
            
        } 
    }

    

    public function listado($page = 1) {
        $datos = new cliente();
        // partial
        $this->result = $datos->listarProcesamiento();
        $this->campos = array(
            '#' => 'id',
            'DESCRIPCION' => 'descripcion',
            'FECHA CREACION' => 'fechacreacion',
            'AVANCE' => 'dias_adicionales');
        $this->encabezado = "Plan de Manejo Orgánico (PMO) PROCESAMIENTO";

        //buscar si existe la poliza de Presupuesto de Egresos  
    }

    public function solicitud() {
        $idUsuario = Session::get('Id');
        $contenedor = new usuario();
        $buscaEntidad = $contenedor->buscaUsuario($idUsuario);
        $cliente = new cliente();

        $avanza = FALSE;
        $this->prefijo = "cliente";
        $this->{$this->prefijo} = $cliente->buscaEnte($buscaEntidad->id);
        $this->responsable = "responsable";
        $responsable = new responsableoperacion();
        $this->{$this->responsable} = $responsable->find_first("cliente_id=$buscaEntidad->id");
        $this->alterna = "alterna";

        if (Input::hasPost('responsable')) {
            $operacion = new responsableoperacion();
            $operacion = $operacion->guardarResponsable();
            $avanza = TRUE;
        }
        if (Input::hasPost('alterna')) {
            $alterna = new ubicacionalterna();
            $guarda = $alterna->guardarUbicacion();
            $avanza = TRUE;
        }
        if (Input::hasPost('tipo')) {
            $alterna = new tipoproduccion();
            $guarda = $alterna->guardarTipoProduccion();
            $avanza = TRUE;
        }
        if (Input::hasPost('estandar')) {
            $alterna = new estandares();
            $guarda = $alterna->guardarEstandar();
            $avanza = TRUE;
        }
         Input::delete();
       
       if($avanza==TRUE) Redirect::to('planGanaderia/historial/'.$idPlanOrganico);
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
                            $texto1 = trim($reader->sheets[0]['cells'][$i][2]);
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
   
    public function insertaArreglo() {
       
        $array_datos = Session::get("array_datos");
        $tamano = count($array_datos);
       $msj="";
      $si=0;
      $no=0;
         $idEntidad =Session::get('entidad_id');  
        for ($i = 0; $i < $tamano; $i++) {
            
  $existe = new cliente();
    $codigo=$array_datos[$i]['texto1'];
    $array_datos[$i]['texto2']=trim($array_datos[$i]['texto2']);
    $array_datos[$i]['texto16']=trim($array_datos[$i]['texto16']);
    $array_datos[$i]['texto17']=trim($array_datos[$i]['texto17']);
            $existeCuenta = $existe->exists("clave ='$codigo'");
            if ($existeCuenta == 0) {
            $detalleplaga = new cliente();
            $detalleplaga->clave = $codigo;
            $detalleplaga->tipoente = (($array_datos[$i]['texto2']=='Fisica')? 'F':(($array_datos[$i]['texto2']=='Moral')? 'M':''));
            $detalleplaga->rfc = $array_datos[$i]['texto3'];
            $detalleplaga->nombrecompleto = $array_datos[$i]['texto4'];
            $detalleplaga->propietario = $array_datos[$i]['texto5'];
            $detalleplaga->calle = $array_datos[$i]['texto6'];
            $detalleplaga->numeroexterior = $array_datos[$i]['texto7'];
            $detalleplaga->numerointerior = $array_datos[$i]['texto8'];
            $detalleplaga->colonia = $array_datos[$i]['texto9'];
            $detalleplaga->codigopostal = $array_datos[$i]['texto10'];
             $detalleplaga->ciudad = $array_datos[$i]['texto11'];
            $detalleplaga->estado = $array_datos[$i]['texto12'];
            $detalleplaga->pais = $array_datos[$i]['texto13'];
            $detalleplaga->telefono = $array_datos[$i]['texto14'];
            $detalleplaga->correoelectronico = $array_datos[$i]['texto15']; 
            $detalleplaga->pago_id = (($array_datos[$i]['texto16']=='Efectivo')? '1':(($array_datos[$i]['texto16']=='Cheque')? 'N':(($array_datos[$i]['texto16']=='Transferencia')? '3':'')));
            $detalleplaga->credito = (($array_datos[$i]['texto17']=='Si')? 'S':(($array_datos[$i]['texto17']=='No')? 'N':''));
            $detalleplaga->dias = $array_datos[$i]['texto18']; 
            $detalleplaga->save();
            if($codigo==""){
            $clave=new cliente();
            $clave=$clave->find_first($detalleplaga->id);
            $clave->clave="UPOBM-".str_pad($clave->id,4,"0", STR_PAD_LEFT);
            $clave->update();
            }
            
            $si++;
        }else{
            $detalleplaga = new cliente();
            $detalleplaga=$detalleplaga->find_first("clave='$codigo'");
            //$detalleplaga->clave = $codigo;
            $detalleplaga->tipoente = (($array_datos[$i]['texto2']=='Fisica')? 'F':(($array_datos[$i]['texto2']=='Moral')? 'M':''));
            $detalleplaga->rfc = $array_datos[$i]['texto3'];
            $detalleplaga->nombrecompleto = $array_datos[$i]['texto4'];
            $detalleplaga->propietario = $array_datos[$i]['texto5'];
            $detalleplaga->calle = $array_datos[$i]['texto6'];
            $detalleplaga->numexterior = $array_datos[$i]['texto7'];
            $detalleplaga->numinterior = $array_datos[$i]['texto8'];
            $detalleplaga->colonia = $array_datos[$i]['texto9'];
            $detalleplaga->codigopostal = $array_datos[$i]['texto10'];
             $detalleplaga->ciudad = $array_datos[$i]['texto11'];
            $detalleplaga->estado = $array_datos[$i]['texto12'];
            $detalleplaga->pais = $array_datos[$i]['texto13'];
            $detalleplaga->telefono = $array_datos[$i]['texto14'];
            $detalleplaga->correoelectronico = $array_datos[$i]['texto15']; 
            $detalleplaga->pago_id = (($array_datos[$i]['texto16']=='Efectivo')? '1':(($array_datos[$i]['texto16']=='Cheque')? 'N':(($array_datos[$i]['texto16']=='Transferencia')? '3':'')));
            $detalleplaga->credito = (($array_datos[$i]['texto17']=='Si')? 'S':(($array_datos[$i]['texto17']=='No')? 'N':''));
            $detalleplaga->dias = $array_datos[$i]['texto18']; 
            $detalleplaga->update();
           
            $no++;
        }
        
    }
    $this->msj.="Se insertaron: ".$si. "<br/> Se Actualizaron: ".$no." porque ya existe el codigo del Cliente ";
        
        Session::delete('mensajeError');
        Session::delete('mensaje');
    }
    
    public function general() {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '-1');
        $consulta = new venta();
        if (Input::hasPost('titulo')) {
            $cliente_id = Input::Post('cliente_id');
            $fecha_inicial = Input::Post('fecha_inicial');
            $fecha_final = Input::Post('fecha_final');
            $titulo = Input::Post('titulo');
            
            
            $this->resultado = $consulta->find_all_by_sql( "select C.*,P.concepto concepto from cliente C left join conceptos P on C.pago_id=P.id");
          
              
              $repRelacionCobros = $this->resultado;
              session::set("repRelacionCobros",$repRelacionCobros);
            ?>
            <script>
                window.open('generalPdf/<?php echo  $titulo ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
            </script>
            <?php
        
           
        }
    }
    public function generalPdf($titulo) {
        Load::lib('tcpdf');
        $this->etiquetaFecha = "";
        
        $this->titulo = $titulo;
        $resultado=  session::get("repRelacionCobros");
        $this->resultado = $resultado;
        session::delete("repRelacionCobros");
            
    }
    public function tipoCliente() {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '-1');
        $consulta = new venta();
        if (Input::hasPost('tipo_cliente')) {
            $tipo = Input::Post('tipo_cliente');
            $fecha_inicial = Input::Post('fecha_inicial');
            $fecha_final = Input::Post('fecha_final');
            $titulo = Input::Post('titulo');
            
            
            $this->resultado = $consulta->find_all_by_sql( "select C.*,P.concepto concepto from cliente C left join conceptos P on C.pago_id=P.id where tipoente='$tipo'");
          
              if (trim($titulo)!="") {
              $repRelacionCobros = $this->resultado;
              session::set("repRelacionCobros",$repRelacionCobros);
            ?>
            <script>
                window.open('generalPdf/<?php echo  $titulo ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
            </script>
            <?php
              }
           
        }
    }
    
    public function metodoPago() {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '-1');
        $consulta = new venta();
        if (Input::hasPost('metodo_pago')) {
            $metodo = Input::Post('metodo_pago');
            $fecha_inicial = Input::Post('fecha_inicial');
            $fecha_final = Input::Post('fecha_final');
            $titulo = Input::Post('titulo');
            
            
            $this->resultado = $consulta->find_all_by_sql( "select C.*,P.concepto concepto from cliente C left join conceptos P on C.pago_id=P.id where credito='$metodo'");
          
              if (trim($titulo)!="") {
              $repRelacionCobros = $this->resultado;
              session::set("repRelacionCobros",$repRelacionCobros);
            ?>
            <script>
                window.open('generalPdf/<?php echo  $titulo ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
            </script>
            <?php
              }
           
        }
    }
    
    public function remision() {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '-1');
        $consulta = new venta();
        if (Input::hasPost('metodo_pago')) {
            $metodo = Input::Post('metodo_pago');
            $fecha_inicial = Input::Post('fecha_inicial');
            $fecha_final = Input::Post('fecha_final');
            $titulo = Input::Post('titulo');
            
            
            $this->resultado = $consulta->find_all_by_sql( "select C.*,P.concepto concepto from cliente C left join conceptos P on C.pago_id=P.id where1 credito='$metodo'");
          
              if (trim($titulo)!="") {
              $repRelacionCobros = $this->resultado;
              session::set("repRelacionCobros",$repRelacionCobros);
            ?>
            <script>
                window.open('generalPdf/<?php echo  $titulo ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
            </script>
            <?php
              }
           
        }
    }
    public function cobros() {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '-1');
        $consulta = new venta();
        $condicion="";
        if (Input::hasPost('fecha_inicial')) {
            $metodo = Input::Post('pago_id');
            $fecha_inicial = Input::Post('fecha_inicial');
            $fecha_final = Input::Post('fecha_final');
            $titulo = Input::Post('titulo');
            $cliente = Input::Post('cliente');
           $condicion = "(fecha_documento >='$fecha_inicial') and (fecha_documento <='$fecha_final' )";
            if ($metodo != "")
                $condicion.=" and concepto_id='" . $metodo . "'";
            if ($cliente != "")
                $condicion.=" and cliente_id='" . $cliente . "'";
            $this->resultado = $consulta->find_all_by_sql("select CO.concepto, C.fecha_documento,C.monto,C.referencia,C.fecha_referencia,B.numero_cuenta,CL.nombrecompleto,C.estatus  from cobros C inner join cliente CL  on C.cliente_id=CL.id inner join conceptos CO on C.concepto_id=CO.id  left join bancos_cuentas B on C.bancos_cuentas_id=B.id  where $condicion ");
          
              if (trim($titulo)!="") {
              $repRelacionCobros = $this->resultado;
              session::set("repRelacionCobros",$repRelacionCobros);
            ?>
            <script>
                window.open('reporteCobrosPdf/<?php echo $fecha_inicial . '/' . $fecha_final . '/' . $titulo ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
            </script>
            <?php
              }
           
        }
    }
    public function actualizarCodigo(){
    View::select(null);
    $clave=new cliente();
            $clave=$clave->find();
            $cliente=new cliente();
            foreach ($clave as $dato ){
                
            $cliente=$cliente->find_first($dato->id);
            $cliente->clave="UPOBM-".str_pad($cliente->id,4,"0", STR_PAD_LEFT);
            $cliente->update();
            
            }
    }
    
    public function reporteCobrosPdf($fecha_inicial, $fecha_final, $titulo) {
        Load::lib('tcpdf');
        $this->etiquetaFecha = "( " . $fecha_inicial . " - " . $fecha_final . " )";

        $this->titulo = $titulo;
        $resultado = session::get("repRelacionCobros");
        $this->resultado = $resultado;
    }
    
}

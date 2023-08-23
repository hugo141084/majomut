
    <?php

Load::models('inventario','producto','lote','serie','movimiento_inventario'); 
class reportesController extends AppController{
    
   
    public function index() {
        
    }
    public function producto() {
        $this->accion="producto";
        $condicion="";
         if (Input::hasPost('producto')) {  
             $datos = Input::Post('producto');
             
             $producto=$datos['producto_id'];
             if($producto=="") $producto=0;
             $calidad=$datos['calidad_id'];
             if($calidad=="") $calidad=0;
             $empaque=$datos['empaque_id'];
              if($empaque=="") $empaque=0;
             ?>
                <script>
                    window.open('reporteProducto/<?php echo $producto; ?>/<?php echo $calidad; ?>/<?php echo $empaque; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
                </script>
                <?php
         }
    }
    public function almacen() {
        $this->accion="almacen";
        $condicion="";
         if (Input::hasPost('almacen')) {  
             $datos = Input::Post('almacen');
             $almacen=$datos['almacen_id'];
             if($almacen=="") $almacen=0;
             $ciclo=input::post("ciclo");
             $tipo=$datos['tipo'];
             
             if (Input::Post('tipoReporte')=='excel') {
              ?>
                <script>
                    window.open('reporteAlmacenExcel/<?php echo $almacen; ?>/<?php echo $tipo; ?>/<?php echo $ciclo; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
                </script>
                <?php   
             }else{
             ?>
                <script>
                    window.open('reporteAlmacen/<?php echo $almacen; ?>/<?php echo $tipo; ?>/<?php echo $ciclo; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
                </script>
                <?php
         }}
    }
    
    public function movimiento() {
     $this->accion="movimiento";
     $conceptos=new conceptomovimiento();
     $this->conceptos=$conceptos->listar();
    if (Input::hasPost('movimiento')) {  
             $datos = Input::Post('movimiento');
            $producto=$datos['producto_id'];
            $lote=$datos['lote_id'];
            if($producto==""){$producto=0;}
            if($lote==""){$lote=0;}
             $fechaInicial=strftime("%Y-%m-%d", strtotime($datos['fecha_inicio']));
             $fechaFinal=strftime("%Y-%m-%d", strtotime($datos['fecha_fin']));
             $titulo="T";
             $movimientos=  $datos['num_movimiento']; 
             if($movimientos==""){$movimientos=0;}
            ?>
           <script>
           window.open('reporteMovimientos/<?php echo $producto.'/'.$fechaInicial.'/'.$fechaFinal.'/'.$titulo.'/'.$movimientos.'/'.$lote.'/' ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
           </script>
           <?php
    }
    
    }
    public function movimiento2() {
     $this->accion="movimiento";
     
    if (Input::hasPost('movimiento')) { 
        $num_movi="";
             $datos = Input::Post('movimiento');
            $producto=$datos['producto_id'];
            $lote=$datos['lote_id'];
            if($producto==""){$producto=0;}
            if($lote==""){$lote=0;}
             $fechaInicial=strftime("%Y-%m-%d", strtotime($datos['fecha_inicial']));
             $fechaFinal=strftime("%Y-%m-%d", strtotime($datos['fecha_final']));
             $this->fechaInicial=$fechaInicial;
         $this->fechaFinal=$fechaFinal;
           //  $titulo=$datos['titulo'];
             $movimientos= $datos['movimiento_id'];
             if($movimientos==""){$movimientos=0;}
             
             
             $condicion=" and  (movi.fecha_documento >= '$fechaInicial' and movi.fecha_documento <= '$fechaFinal') ";
         if($producto!="0"){
             $condicion=$condicion." and (inv.id=$producto)";
         }
         if ($movimientos!="0")
         {
             $num_movi='movi.tipo_movimiento='.$movimientos;
            
     }
     $this->loteB=$lote;
     $this->num_movi=$num_movi;
         
        
              $existencia=new movimiento_inventario();
              $this->resultado=$existencia->buscaXmovimiento($condicion);
             $titulo="";
              
          
              
    }
    
    }
    public function inventarioInicial() {
     $this->accion="inventarioInicial";
     $condicion=""; 
     
     
         if (Input::hasPost('existencia')) {  
             $datos = Input::Post('existencia');
             $clasificacion=$datos['producto_id'];
              if($clasificacion=="") $clasificacion=0
                 
            
             ?>
                <script>
                    window.open('reporteInventarioInicial/<?php echo $clasificacion; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
                </script>
                <?php
         }
    }
    public function existencia() {
        $this->accion="existencia";
        $condicion="";
         if (Input::hasPost('existencia')) {  
             $datos = Input::Post('existencia');
             
             $clasificacion=$datos['producto_id'];
             if($clasificacion=="") $clasificacion=0;
             $opcion=$datos['opcion'];
             $tipo=$datos['tipo'];
             
             ?>
                <script>
                    window.open('reporteExistencia/<?php echo $clasificacion; ?>/<?php echo $opcion; ?>/<?php echo $tipo; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
                </script>
                <?php
         }
    }
    public function lote() {
        $this->accion="lote";
        $condicion="";
         if (Input::hasPost('producto')) {  
             $datos = Input::Post('producto');
             $clasificacion=$datos['id'];
             if($clasificacion=="") $clasificacion=0;
            
             
             ?>
                <script>
                    window.open('reporteLote/<?php echo $clasificacion; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
                </script>
                <?php
         }
    }
    public function numInventario() {
        $this->accion="numInventario";
        $condicion="";
         if (Input::hasPost('existencia')) {  
             $datos = Input::Post('existencia');
             
             $clasificacion=$datos['CLASIFICACION_PRODUCTO_ID'];
             if($clasificacion=="") $clasificacion=0;
            
             
             ?>
                <script>
                    window.open('reporteNumInventario/<?php echo $clasificacion; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
                </script>
                <?php
         }
    }
    public function serie() {
        $this->accion="serie";
        $condicion="";
         if (Input::hasPost('existencia')) {  
             $datos = Input::Post('existencia');
             
             $clasificacion=$datos['CLASIFICACION_PRODUCTO_ID'];
             if($clasificacion=="") $clasificacion=0;
             
             
             ?>
                <script>
                    window.open('reporteSerie/<?php echo $clasificacion; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
                </script>
                <?php
         }
    }
    public function reporteMovimientos($producto,$fechaInicial,$fechaFinal,$titulo,$movimientos,$lote){
         ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '-1');
         $this->fechaInicial=$fechaInicial;
         $this->fechaFinal=$fechaFinal;
         $this->tituloReporte=$titulo;
         $condicion=" and  (movi.fecha_documento >= '$fechaInicial' and movi.fecha_documento <= '$fechaFinal') ";
         if($producto!="0"){
             $condicion=$condicion." and (inv.id=$producto)";
         }
         if ($movimientos!="0")
         {
             $num_movi=str_replace('TM', 'movi.tipo_movimiento', $movimientos);
             $condicion= $condicion." and (".$num_movi.")";
     }
     $this->loteB=$lote;
     $this->num_movi=$num_movi;
         
        
              $existencia=new movimiento_inventario();
              $this->movimientos=$existencia->buscaXmovimiento($condicion);
    }
    public function reporteAlmacen($almacen=NULL,$tipo=NULL,$ciclo=NULL){
        $condicion="";
        if($almacen!="0"){
                 $condicion =" where id=".$almacen;
             }
              
              $this->tipo=$tipo;
              $this->ciclo=$ciclo;
              $existencia=new almacen();
              $this->almacen=$existencia->buscaAlmacen($condicion);
    }
    public function reporteAlmacenExcel($almacen=NULL,$tipo=NULL,$ciclo){
        Load::lib('PHPExcel');
        View::template(NULL);
        $condicion="";
        if($almacen!="0"){
                 $condicion =" where id=".$almacen;
             }
              
              $this->tipo=$tipo;
              $this->ciclo=$ciclo;
              $existencia=new almacen();
              $this->almacen=$existencia->buscaAlmacen($condicion);
    }
    public function reporteExistencia($clasificacion=NULL,$opcion=NULL,$tipo=NULL){
        
        if($clasificacion!="0"){
                 $condicion=" where id=".$clasificacion;
             }
              if($opcion=="M"){
               if($condicion==""){
                $condicion=" where existencia > 0";   
               }else{   
               $condicion=$condicion." and existencia >0";
               }
              }
              $this->tipo=$tipo;
              $existencia=new producto();
              $this->existencia=$existencia->buscaXcondicion($condicion);
    }
    public function reporteProducto($producto=NULL,$calidad=NULL,$empaque=NULL){
        $condicion="";
        if($producto!="0"){
                 $condicion.=" where p.id=".$producto;
             }
        if($calidad!="0"){
            if($condicion==""){
                $condicion .= " where p.calidad_id=".$calidad;
            }else{
              $condicion .=" and p.calidad_id=".$calidad;  
            }
        } 
        if($empaque!="0"){
            if($condicion==""){
                $condicion .=" where p.empaque_id=".$empaque;
            }else{
              $condicion .=" and p.empaque_id=".$empaque;  
            }
        } 
              
              $existencia=new producto();
              $this->productos=$existencia->buscaProductos($condicion);
    }
    public function reporteInventarioInicial($clasificacion=NULL,$tipo=NULL){
        
        if($clasificacion!="0"){
                 $condicion=" where id=".$clasificacion;
             }
              if($opcion=="M"){
               if($condicion==""){
                $condicion=" where existencia != 0";   
               }else{   
               $condicion=$condicion." and existencia != 0";
               }
              }
              $this->tipo=$tipo;
              $existencia=new producto();
              $this->existencia=$existencia->buscaXcondicion($condicion);
    }
    public function reporteLote($clasificacion=NULL){
        
        if($clasificacion!="0"){
                 $condicion="  where id='$clasificacion' and existencia > 0";
             }else{
                 $condicion="where  existencia > 0";
             }
            
              $existencia=new producto();
              $this->existencia=$existencia->buscaXcondicion($condicion);
    }
function reporteSerie($clasificacion=NULL){
        
        if($clasificacion!="0"){
                 $condicion=" where CLASIFICACION_PRODUCTO_ID=".$clasificacion." and NUMERO_SERIE='S' and EXISTENCIA <> 0";
             }else{
                 $condicion="where NUMERO_SERIE='S'";
             }
            
              $existencia=new producto();
              $this->existencia=$existencia->buscaXcondicion($condicion);
    }
    function reporteNumInventario($clasificacion=NULL){
        
        if($clasificacion!="0"){
                 $condicion=" where CLASIFICACION_PRODUCTO_ID=".$clasificacion." and NUMERO_SERIE='S' and EXISTENCIA <> 0";
             }else{
                 $condicion="where NUMERO_SERIE='S'";
             }
            
              $existencia=new producto();
              $this->existencia=$existencia->buscaXcondicion($condicion);
    }
    public function crear(){
         $this->accion="crear";
    }
    
    
    public function crearClasifproducto(){
        View::template(NULL);
        $this->accion="crearClasifProducto";
        $clasificacion = new clasificacionProducto();
        $this->clasificacionProducto = $clasificacion->find();

        if (Input::hasPost('clasificacion')) {

            $this->clasificacionProducto = $clasificacion->guardarDatos();
        }
       
    }
    public function editarClasifProducto($id = null,$var=null) {
        $this->accion = 'editarClasifProducto';
        $clasificacion = new clasificacionProducto();
        if ($id != null) {
            $this->clasificacion = $clasificacion->find($id);
        } else if (Input::hasPost('clasificacion')) {
             $clasificacion->USUARIO_ID=Session::get('id');
            if ($clasificacion->update(Input::post('clasificacion'))) {               
                $this->actualizo="0";
                Flash::valid('Registro Actualizado');
                Router::redirect('catalogo/editarPuesto/'.$clasificacion->ID.'/'.'5');   
            } else {
                $this->clasificacion = Input::post('clasificacion');
                Flash::error('Falló Operación');
            }
        }
        $this->var=$var;
        view::select('crearClasifProducto');
    }
     public function crearZona(){
        View::template(NULL);
        $this->accion="crearZona";
        $zona = new zona();
        $this->zona = $zona->find();

        if (Input::hasPost('zona')) {

            $this->zona = $zona->guardarDatos();
        }
       
    }
    
    public function listadoPuesto() {
        $puesto = new puesto();
        $this->result = $puesto->listar();
        $this->campos = array(
            utf8_encode('#') => 'ID',
            utf8_encode('DESCRIPCION') => 'DESCRIPCION',
            
        );
        $this->encabezado= "Puestos";
    }
     public function crearPuesto(){
        View::template(NULL);
        $this->accion="crearPuesto";
        $puesto = new puesto();
        $this->puesto = $puesto->find();

        if (Input::hasPost('puesto')) {

            $this->puesto = $puesto->guardarDatos();
        }
     }
       public function editarPuesto($id = null,$var=null) {
        $this->accion = 'editarPuesto';
        $puesto = new puesto();
        if ($id != null) {
            $this->puesto = $puesto->find($id);
        } else if (Input::hasPost('puesto')) {
             $puesto->USUARIO_ID=Session::get('id');
            if ($puesto->update(Input::post('puesto'))) {               
                $this->actualizo="0";
                Flash::valid('Registro Actualizado');
                Router::redirect('catalogo/editarPuesto/'.$puesto->ID.'/'.'5');   
            } else {
                $this->puesto = Input::post('puesto');
                Flash::error('Falló Operación');
            }
        }
        $this->var=$var;
        view::select('crearPuesto');
    }
     public function listadoDepartamento() {
        $departamento = new departamento();
        $this->result = $departamento->listar();
        $this->campos = array(
            utf8_encode('#') => 'ID',
            utf8_encode('DESCRIPCION') => 'DESCRIPCION',
            
        );
        $this->encabezado= "Departamentos";
    }
     public function crearDepartamento(){
        View::template(NULL);
        $this->accion="crearDepartamento";
        $departamento = new departamento();
        $this->departamento = $departamento->find();

        if (Input::hasPost('departamento')) {

            $this->departamento = $departamento->guardarDatos();
        }
     }
      public function editarDepartamento($id = null,$var=null) {
        $this->accion = 'editarDepartamento';
        $departamento = new departamento();
        if ($id != null) {
            $this->departamento = $departamento->find($id);
        } else if (Input::hasPost('departamento')) {
             $departamento->USUARIO_ID=Session::get('id');
            if ($departamento->update(Input::post('departamento'))) {               
                $this->actualizo="0";
                Flash::valid('Registro Actualizado');
                Router::redirect('catalogo/editarDepartamento/'.$departamento->ID.'/'.'5');   
            } else {
                $this->departamento = Input::post('departamento');
                Flash::error('Falló Operación');
            }
        }
        $this->var=$var;
        view::select('crearDepartamento');
    }
    
     public function seguimientoProducto() {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '-1');
        $consulta = new venta();
        if (Input::hasPost('fecha_inicial')) {
            $lote = Input::Post('lote');
            $producto = Input::Post('producto');
            $almacen = Input::Post('almacen');
            $fecha_inicial = Input::Post('fecha_inicial');
            $fecha_final = Input::Post('fecha_final');
            $condicion= " where M.fecha_documento >='$fecha_inicial' and M.fecha_documento <='$fecha_final' and M.producto_id='$producto' ";
            if(trim($lote)!=''){
               $condicion = $condicion." and M.lote_id='$lote' ";  
            }
           if(trim($almacen)!=''){
               $condicion = $condicion." and M.almacen_id='$almacen' ";  
            }
            $this->resultado = $consulta->find_all_by_sql("select M.fecha_documento, M.referencia,concat_ws(' - ',C.descripcion,C.tipo_movimiento) movimiento,C.tipo_movimiento , concat_ws(' - ',P.clave,P.descripcion) producto, L.codigo,A.descripcion almacen, M.cantidad  from movimiento_inventario M INNER join concepto C on M.tipo_movimiento=C.id INNER JOIN producto P on M.producto_id=P.id inner join lote L on M.lote_id=L.id inner join almacen A on M.almacen_id=A.id  $condicion  ORDER BY M.fecha_documento ASC");
            
        }
    }
}
?>
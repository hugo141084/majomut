<?php

Load::models('inventario','producto','lote','serie','movimiento_inventario'); 
class reportesController extends AppController{
    
    public function clasificacionProducto() {
        $producto = new clasificacionProducto();
        $this->result = $producto->listar();
        $this->campos = array(
            utf8_encode('#') => 'ID',
            utf8_encode('DESCRIPCION') => 'DESCRIPCION',
            
        );
        $this->encabezado= "Clasificacion";
    }
    public function index() {
        
    }
    
    public function departamento() {
     $this->accion="departamento"; 
     if (Input::hasPost('movimiento')) {  
             $datos = Input::Post('movimiento');
            $producto=$datos['PRODUCTO_ID'];
            $departamento=$datos['DEPARTAMENTO_ID'];
           // $agrupar=$datos['AGRUPAR'];
           $agrupar=""; 
            if($producto==""){$producto=0;}
            if($departamento==""){$departamento=0;}
            if($agrupar==""){$agrupar=0;}
             $fechaInicial=strftime("%Y-%m-%d", strtotime($datos['FECHA_INICIO']));
             $fechaFinal=strftime("%Y-%m-%d", strtotime($datos['FECHA_FIN']));
             $titulo=$datos['titulo'];             
            ?>
           <script>
           window.open('reporteDepartamento/<?php echo $producto.'/'.$departamento.'/'.$agrupar.'/'.$fechaInicial.'/'.$fechaFinal.'/'.$titulo.'/' ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
           </script>
           <?php
             Router::redirect('reportes/departamento');
    }
    }
    
    public function movimiento() {
     $this->accion="movimiento";
     $this->conceptos=Load::model('conceptoMovimiento')->listar();
     
    if (Input::hasPost('movimiento')) {  
             $datos = Input::Post('movimiento');
            $producto=$datos['producto_id'];
            if($producto==""){$producto=0;}
             $fechaInicial=strftime("%Y-%m-%d", strtotime($datos['fecha_inicio']));
             $fechaFinal=strftime("%Y-%m-%d", strtotime($datos['fecha_fin']));
             $titulo=$datos['titulo'];
             $movimientos=  $datos['num_movimiento']; 
             if($movimientos==""){$movimientos=0;}
            ?>
           <script>
           window.open('reporteMovimientos/<?php echo $producto.'/'.$fechaInicial.'/'.$fechaFinal.'/'.$titulo.'/'.$movimientos.'/' ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
           </script>
           <?php
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
         if (Input::hasPost('existencia')) {  
             $datos = Input::Post('existencia');
             
             $clasificacion=$datos['CLASIFICACION_PRODUCTO_ID'];
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
    public function reporteMovimientos($producto,$fechaInicial,$fechaFinal,$titulo,$movimientos){
         ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '-1');
         $this->fechaInicial=$fechaInicial;
         $this->fechaFinal=$fechaFinal;
         $this->tituloReporte=$titulo;
         $condicion=" and  (movi.fecha_movimiento >= '$fechaInicial' and movi.fecha_movimiento <= '$fechaFinal') ";
         if($producto!="0"){
             $condicion=$condicion." and (inv.id=$producto)";
         }
         if ($movimientos!="0")
         {
             $num_movi=str_replace('TM', 'movi.tipo_movimiento', $movimientos);
             $condicion= $condicion." and (".$num_movi.")";
     }
         
        
              $existencia=new movimiento_inventario();
              $this->movimientos=$existencia->buscaXmovimiento($condicion);
    }
    
    public function reporteDepartamento($producto,$departamento,$agrupar,$fechaInicial,$fechaFinal,$titulo){
         ini_set('memory_limit', '512M');
         ini_set('max_execution_time', '-1');
         $entrega = new entrega();
         $this->fechaInicial=$fechaInicial;
         $this->fechaFinal=$fechaFinal;
         $this->agrupar=$agrupar;
         $this->producto=$producto;
         $this->departamento=$departamento;
         $this->tituloReporte=$titulo;
         $condicion=" and  (ent.FECHA_ENTREGA >= '$fechaInicial' and ent.FECHA_ENTREGA <= '$fechaFinal' )";
        
         
         if ($agrupar!="0")
         {
             $condicionFecha=" (FECHA_ENTREGA >= '$fechaInicial' and FECHA_ENTREGA <= '$fechaFinal') ";
             if($agrupar=="Fecha"){  $entrega = new entrega(); $this->movimientos=$entrega->entregaXfecha($condicionFecha);}
        else if($agrupar=="Vale" ){  $entrega = new entrega(); $this->movimientos=$entrega->entregaXvale($condicionFecha);}
         }else{
             if($producto!="0"){
             $condicion=$condicion." and (detEn.PRODUCTO_ID=$producto)";
         }
         if ($departamento!="0")
         {
            $condicion=$condicion." and (ent.DEPARTAMENTO_ID=$departamento)";
         }
         
         $this->movimientos=$entrega->buscaProducto($condicion);
         }
         
        
              
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
                 $condicion=" where CLASIFICACION_PRODUCTO_ID=".$clasificacion." and LOTE='S' and EXISTENCIA <> 0";
             }else{
                 $condicion="where LOTE='S'";
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
}
?>
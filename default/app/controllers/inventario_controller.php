<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//Load::models('almacen','producto','inventario','claseRecepcion','presentacion','lote','movimientoInventario','usuario','entrega','claseSalida','detalleEntrega','empleado','departamento','numeroInventario'); 
class inventarioController extends AppController{
    
    /**
     * Muestra el partial de las nominas generadas
     */
    public function listadoAlmacen() {
        $almacen = new almacen();
        $this->producto = $almacen->find();

        if (Input::hasPost('almacen')) {

            $this->almacen = $almacen->guardarDatos();
        }
        $this->result = $almacen->listar();
        $this->campos = array(
            utf8_encode('#') => 'id',
            utf8_encode('CLAVE') => 'almacen',
            utf8_encode('DESCRIPCION') => 'descripcion',
            utf8_encode('TIPO') => 'tipo'
            
           
        );
        $this->encabezado= "ALMACEN";
    }
    public function editarAlmacen($id){
        
     
        $almacen = new almacen();
        $this->almacen = $almacen->find_first($id);

        if (Input::hasPost('almacen')) {

            $this->almacen = $almacen->actualizarDatos();
            Redirect::to('inventario/listadoAlmacen');
        }
       
    }
     public function eliminarAlmacen($id){
        $almacen = new almacen();
        $almacen = $almacen->find_first($id);
        $almacen->estatus='0';
        $almacen->update();
        Redirect::to('inventario/listadoAlmacen');
    }
     public function listadoRecepcion() {
        $vale = new vale();
        $this->result = $vale->listarEntrada();
        $this->campos = array(
            utf8_encode('#') => 'id',
            utf8_encode('TIPO DOCUMENTO') => 'tipo_documento',
            utf8_encode('DOCUMENTO') => 'documento',
            utf8_encode('F. DOCUMENTO') => 'fecha_documento',
            utf8_encode('F. RECEPCION') => 'fecha_recepcion',
            
            utf8_encode('ESTADO') => 'estado'
            
           
        );
        $this->encabezado= "RECEPCIÓN DE  CAFE";
    }
     public function listadoSalidaCT() 
    {
     $vale = new vale();
        $this->result = $vale->listarSalidaCT();
        $this->campos = array(
            utf8_encode('#') => 'id',
            utf8_encode('TIPO DOCUMENTO') => 'tipo_documento',
            utf8_encode('DOCUMENTO') => 'documento',
            utf8_encode('F. DOCUMENTO') => 'fecha_documento',
            utf8_encode('F. RECEPCION') => 'fecha_salida',
            
            utf8_encode('ESTADO') => 'estado'
            
           
        );
        $this->encabezado= "SALIDA DE  CAFE PARA TOSTADO";
    }
    public function listadoEntradaCT() 
    {
     $vale = new vale();
        $this->result = $vale->listarEntradaCT();
        $this->campos = array(
            utf8_encode('#') => 'id',
            utf8_encode('TIPO DOCUMENTO') => 'tipo_documento',
            utf8_encode('DOCUMENTO') => 'documento',
            utf8_encode('F. DOCUMENTO') => 'fecha_documento',
            utf8_encode('F. RECEPCION') => 'fecha_salida',
            
            utf8_encode('ESTADO') => 'estado'
            
           
        );
        $this->encabezado= "ENTRADA DE CAFE TOSTADO";
    }
    public function listadoSalida() 
    {
     $entrega = new entrega();
        $this->result = $entrega->listarEntrega();
        $this->campos = array(
            utf8_encode('#') => 'ID',
            utf8_encode('TIPO DOCUMENTO') => 'tipo_documento',
            utf8_encode('SOLICITANTE') => 'nombre',
            utf8_encode('F. SOLICITUD') => 'fecha_solicitud',
            utf8_encode('F. ENTREGA') => 'fecha_entrega',
            utf8_encode('DESTINATARIO') => 'descripcion',
            utf8_encode('ESTADO') => 'estado'
            
           
        );
        $this->encabezado= "ENTREGA DE PRODUCTOS";
    }
    public function crearEntradaCT() 
    {
     $this->accion="crearEntradaCT";
        
        $vale = new vale();
        $this->vale = $vale->find();

         if (Input::hasPost('vale')) {
            $array_productos = Session::get('array_productos');
            $num = count($array_productos);
           
            if($num == "0"){
               
                echo "<script>  alert ('Primero agrege partidas para poder guardar este movimiento....!');</script>"; 
            }else {
            $this->vale = $vale->guardarDatosECT();
            ?>
                <script>
                    window.open('valeEntradaCT/<?php echo $this->vale; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
                </script>
               <?php
                Input::delete();
                Redirect::to('inventario/crearEntradaCT');
            }
        }else{
              session::delete('array_productos');
        }
         Input::delete();
        $folios = new series_folios();
        $datoFolios = $folios->find_first("tipo = 'VALE'");
        
        $this->num="V-".str_pad(($datoFolios->consecutivo)+1,4, "0", STR_PAD_LEFT);
    }
    public function crearSalidaCT() 
    {
     $this->accion="crearSalidaCT";
        
        $vale = new vale();
        $this->vale = $vale->find();

         if (Input::hasPost('vale')) {
            $array_productos = Session::get('array_productos');
            $num = count($array_productos);
           
            if($num == "0"){
               
                echo "<script>  alert ('Primero agrege partidas para poder guardar este movimiento....!');</script>"; 
            }else {
            $this->vale = $vale->guardarDatosSCT();
            ?>
                <script>
                    window.open('valeSalidaCT/<?php echo $this->vale; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
                </script>
               <?php
                Input::delete();
                Redirect::to('inventario/crearSalidaCT');
            }
        }else{
              session::delete('array_productos');
        }
         Input::delete();
        $folios = new series_folios();
        $datoFolios = $folios->find_first("tipo = 'VALE'");
        
        $this->num="V-".str_pad(($datoFolios->consecutivo)+1,4, "0", STR_PAD_LEFT);
    }
    public function crearSalida() 
    {
     $this->accion="crearSalida";
        
        $entrega = new entrega();
        $this->entrega = $entrega->find();

       
        if (Input::hasPost('vale')) {
            $array_productos = Session::get('array_productos');
            $num = count($array_productos);
           
            if($num == "0"){
               
                echo "<script>  alert ('Primero agrege partidas para poder guardar este movimiento....!');</script>"; 
            }else {
            $this->vale = $vale->guardarDatosSCT();
            ?>
                <script>
                    window.open('valeSalidaCT/<?php echo $this->vale; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
                </script>
               <?php
                Input::delete();
                Redirect::to('inventario/crearSalida');
            }
        }else{
              session::delete('array_productos');
        }
         Input::delete();
        $folios = new series_folios();
        $datoFolios = $folios->find_first("tipo = 'VALE'");
        
        $this->num="V-".str_pad(($datoFolios->consecutivo)+1,4, "0", STR_PAD_LEFT);
    }
    
     public function eliminarSalida($id=NULL) 
    {    
     View::select(NULL);
     $salida = new entrega(); 
     $this->salida = $salida->cancelaSalida($id);
     Redirect::to('inventario/listadoSalida');
    }
    
    public function crear(){
         $this->accion="crear";
        
        $almacen = new almacen();
        $this->producto = $almacen->find();

        if (Input::hasPost('almacen')) {

            $this->almacen = $almacen->guardarDatos();
        }
    }
    public function inventarioInicial() 
    {
    $this->accion="inventarioInicial";
    }
    public function crearRecepcion(){
         $this->accion="crearRecepcion";
        
        $vale = new vale();
        $this->vale = $vale->find();

        if (Input::hasPost('vale')) {
            $array_productos = Session::get('array_productos');
            $num = count($array_productos);
           
            if($num == "0"){
               
                echo "<script>  alert ('Primero agrege partidas para poder guardar este movimiento....!');</script>"; 
            }else {
            $this->vale = $vale->guardarDatos();
            ?>
                <script>
                    window.open('valeEntrada/<?php echo $this->vale; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
                </script>
               <?php
                Input::delete();
                Redirect::to('inventario/crearRecepcion');
            }
        }else{
              session::delete('array_productos');
        }
         Input::delete();
        $folios = new series_folios();
        $datoFolios = $folios->find_first("tipo = 'VALE'");
        
        $this->num="V-".str_pad(($datoFolios->consecutivo)+1,4, "0", STR_PAD_LEFT);
   
    }
        
    public function inventarioIni(){
        
        $vale=new vale();
        $vale=$vale->count();
        if($vale>=1){
            Flash::info("<strong>" . Session::get('.')  ."Ya se ha generado un evento de -INVENTARIO INICIAL-, puede realizar un movimiento de ajuste al inventario". "<strong>");

            Redirect::to('principal/principal');
           
        }
            
        $this->accion="inventarioIni";
        
        $vale = new vale();
        $this->vale = $vale->find();

        if (Input::hasPost('vale')) {
            $array_productos = Session::get('array_productos');
            $num = count($array_productos);
           
            if($num == "0"){
               
                echo "<script>  alert ('Primero agrege partidas para poder guardar este movimiento....!');</script>"; 
            }else {
            $this->vale = $vale->guardarDatosIni();
            ?>
                <script>
                    window.open('valeInicial/<?php echo $this->vale; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
                </script>
               
                <?php
                Input::delete();
                Redirect::to('inventario/inventarioIni');
            }
        }else{
              session::delete('array_productos');
        }
         Input::delete();
        $folios = new series_folios();
        $datoFolios = $folios->find_first("tipo = 'VALE'");
        
        $this->num="V-".str_pad(($datoFolios->consecutivo)+1,4, "0", STR_PAD_LEFT);
    }
    public function eliminarEntrada($id=NULL) 
    {    
     View::select(NULL);
     $compra = new compra(); 
     $this->compra = $compra->cancelaCompra($id);
     Redirect::to('inventario/listadoRecepcion');
    }
    public function imprimirRecepcion($id){
       
      ?>
                <script>
                    window.open('valeEntrada/<?php echo $id; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
                </script>
                <?php  
    }
    public function editar($id = null) {
        $this->accion = 'editar';
        $almacen = new almacen();
        if ($id != null) {
            $this->almacen = $almacen->find($id);
        } else if (Input::hasPost('almacen')) {
             $almacen->USUARIO_ID=Session::get('id');
            if ($almacen->update(Input::post('almacen'))) {
                
               
                Redirect::to('inventario/listadoAlmacen');
                Flash::valid('Registro Actualizado');
                Input::delete();
            } else {
                $this->almacen = Input::post('inventario/listadoAlmacen');
                Flash::error('Falló Operación');
            }
        }
        view::select('crear');
    }
    public function asignar(){
         $this->accion="crear";
    }
    public function valeInicial($compraId){
     $compra=new vale();
     $this->datosCompra=$compra->listarXid($compraId);
     $datosCompra=$this->datosCompra;
     $detalleCompra=new detalle_vale();
     $this->detalleCompra=$detalleCompra->listarProductoCompra($compraId);
     $usuario=new usuario();
     $destino=new almacen();
     $this->destino=$destino->destino($compraId);
     $ciclo=new lote();
     $this->ciclo=$ciclo->ciclo($compraId);
     
     
    // $this->datosUsuario=$usuario->listarEmpleado($this->datosCompra->USUARIO_ID);
    }
    public function valeEntrada($compraId){
     $compra=new vale();
     $this->datosCompra=$compra->listarXid($compraId);
     $detalleCompra=new detalle_vale();
     $this->detalleCompra=$detalleCompra->listarProductoCompra($compraId);
     $usuario=new usuario();
     $destino=new almacen();
     $this->destino=$destino->destino($compraId);
     $ciclo=new lote();
     $this->ciclo=$ciclo->ciclo($compraId);
    // $this->datosUsuario=$usuario->listarEmpleado($this->datosCompra->USUARIO_ID);
    }
    public function valeSalidaCT($compraId){
     $compra=new vale();
     $this->datosCompra=$compra->listarXid($compraId);
     $detalleCompra=new detalle_vale();
     $this->detalleCompra=$detalleCompra->listarProductoCompra($compraId);
     $usuario=new usuario();
     $destino=new almacen();
     $this->destino=$destino->destino($compraId);
     $ciclo=new lote();
     $this->ciclo=$ciclo->ciclo($compraId);
    // $this->datosUsuario=$usuario->listarEmpleado($this->datosCompra->USUARIO_ID);
    }
    public function valeEntradaCT($compraId){
     $compra=new vale();
     $this->datosCompra=$compra->listarXid($compraId);
      $datosCompra=$this->datosCompra;
     $detalleCompra=new detalle_vale();
     $this->detalleCompra=$detalleCompra->listarProductoCompra($compraId);
     $usuario=new usuario();
     $destino=new almacen();
     $this->destino=$destino->destino($compraId);
     $ciclo=new lote();
     $this->ciclo=$ciclo->ciclo($compraId);
     
     $this->referencia=$compra->find_by_sql("select documento from vale where id='$datosCompra->referencia_id '");
    // $this->datosUsuario=$usuario->listarEmpleado($this->datosCompra->USUARIO_ID);
    }
    public function valeSalida($entregaId){
     $entrega=new entrega();
     $this->datosEntrega=$entrega->listarXid($entregaId);
     $empleado=new empleado();
     $this->datosEmpleado=$empleado->buscarEmpleado($this->datosEntrega->EMPLEADO_ID);
    $detalleEntrega=new detalle_entrega();
     $this->detalleEntrega=$detalleEntrega->listarProductoCompra($entregaId);
  //    $departamento=new departamento();
  //   $this->datosDepartamento=$departamento->listarXid($this->datosEntrega->DEPARTAMENTO_ID);
   // $usuario=new usuario();
  //  $this->datosUsuario=$usuario->listarEmpleado($this->datosEntrega->USUARIO_ID); 
    }
            function libreriaInventario() {
        View::template(NULL);

        $this->operacion = Input::POST('operacion');


        if (($this->operacion) == "MOSTRAR") {
          $producto = new producto();  
          $this->listar=$producto->listar();  
        }
         else if (($this->operacion) == "MOSTRAR_ALMACEN") {
             $id=Input::POST('almacen');
          $almacen = new almacen();  
          $this->listar=$almacen->listarProductoAlmacen($id);  
        }
        else if (($this->operacion) == "MOSTRAR_INICIAL") {
             $id=Input::POST('almacen');
          $almacen = new almacen();  
          $this->listar=$almacen->listarProductoAlmacen($id);  
        }
        else if (($this->operacion) == "AGREGAR_PRODUCTO") {
          
          $inventario = new inventario();
       

            $this->resultado= $inventario->guardarDatos(Input::POST('producto'),Input::POST('almacen'),Input::POST('minimo'),Input::POST('maxima'));
        }
        else if (($this->operacion) == "AGREGAR_PARTIDA") {
          
         $clase = new clase_recepcion();
            $this->mensaje = $clase-> introduce_movimiento(Input::POST('cantidad'), Input::POST('productoId'), Input::POST('almacenId'), Input::POST('lote'), Input::POST('fecha_caducidad'), Input::POST('serie'), Input::POST('opcion'),Input::POST('numInventario'));
           
        }
        else if (($this->operacion) == "AGREGAR_PARTIDA_SALIDA") {
          
         $clase = new claseSalida();
           $this->mensaje = $clase-> introduce_movimiento(Input::POST('cantidad'), Input::POST('productoId'), Input::POST('almacenId'), Input::POST('loteId'), Input::POST('serieId'), Input::POST('opcion'), Input::POST('numInventario'));
           
        }
        else if (($this->operacion) == "AGREGAR_INICIAL") {
        $tipoMovimiento="E";
        $numeroMovimiento="8";    
        $fechaDocumento=date('Y-m-d');
        $fechaMovimiento=date('Y-m-d');
        $referencia="INV. INICIAL";
        $empleadoId=Session::get('id'); 
        
              $lote=new lote();
              $serie=new serie();
              $movimiento=new movimientoInventario();
              $producto=new producto();
              $inventario=new inventario();
      $productoId=  Input::POST('productoId');
       $cantidad= Input::POST('cantidad');
       $opcion= Input::POST('opcion');
        $unidadXpaquete="1";
        $fechaCaducidad=Input::POST('fechaCaducidad');
     $almacenId=Input::POST('almacenId');         
         $loteSerie=Input::POST('loteSerie');  
       
        
             
              if($opcion=="lote"){
            $this->mensaje=  $lote->validaEntrada($loteSerie,$fechaCaducidad,$productoId,$almacenId,$cantidad,$unidadXpaquete,$tipoMovimiento);    
              }else if ($opcion=="serie"){
             $this->mensaje=   $serie->validaEntrada($loteSerie,$productoId,$almacenId,$cantidad,$unidadXpaquete,$tipoMovimiento);    
              }
            $this->mensaje=  $this->mensaje.  $inventario->actualizaInventario($productoId,$cantidad,$tipoMovimiento,$unidadXpaquete,$almacenId);
            $this->mensaje=  $this->mensaje.  $producto->actualizaProducto($productoId,$cantidad,$tipoMovimiento,$unidadXpaquete);
           $this->mensaje=  $this->mensaje.   $movimiento->validaMovimiento($productoId,$fechaDocumento,$referencia,$fechaMovimiento,$empleadoId, $cantidad, $unidadXpaquete, $almacenId, $loteSerie,$opcion,$numeroMovimiento);
              
        
        
        }
        else if (($this->operacion) == "QUITAR_PRODUCTO") {
          
          $inventario = new inventario();
       

            $this->resultado= $inventario->quitarProducto(Input::POST('id'));
        }
        else if (($this->operacion) == "QUITAR_PARTIDA") {
       $clase = new claseRecepcion();
            $this->mensaje = $clase-> quita_movimiento(Input::POST('linea'));
           
           
        }  
        else if (($this->operacion) == "QUITAR_PARTIDA_SALIDA") {
       $clase = new claseSalida();
            $this->mensaje = $clase-> quita_movimiento(Input::POST('linea'));
           
           
        }  
        else if (($this->operacion) == "MOSTRAR_LISTA") {
          
          
        } 
        else if (($this->operacion) == "MOSTRAR_LISTA_SALIDA") {
          
          
        } 
        else if (($this->operacion) == "BUSCA_ALMACEN") {
          
            $this->condicion='PRODUCTO_ID='.Input::POST('productoId');
          
        } 
        else if (($this->operacion) == "BUSCA_ALMACEN_SALIDA") {
          
            $this->condicion='PRODUCTO_ID='.Input::POST('productoId');
          
        } 
        else if (($this->operacion) == "MOSTRAR_DETALLE") {
        
            $productoId=Input::POST('productoId');
            $producto=new producto();
            $detalle=$producto->listarXid($productoId);
            if($detalle->NUMERO_SERIE=="S"){
                $this->var="serie";
                if($detalle->NUMERO_INVENTARIO=="S"){
                $this->var="serieInventario";    
                }
            }else if($detalle->LOTE=="S"){
                $this->var="lote";
                if($detalle->NUMERO_INVENTARIO=="S"){
                $this->var="loteInventario";    
                }
            }else{
                $this->var="ninguno";
                if($detalle->NUMERO_INVENTARIO=="S"){
                $this->var="soloInventario";    
                }
            }
            
          
        }
        else if (($this->operacion) == "MOSTRAR_DETALLE_SALIDA") {
        
            $this->productoId=Input::POST('productoId');
            $this->almacenId=Input::POST('almacenId');
            $producto=new producto();
            $this->detalle=$producto->listarXid($this->productoId);
            
            
          
        }
        else if (($this->operacion) == "BUSCA_PROVEEDOR") {
          
            $proveedorId=Input::POST('proveedorId');
     
          $this->proveedor= Load::model('proveedor')-> buscarProveedor($proveedorId);
          
        }
        else if (($this->operacion) == "BUSCA_EMPLEADO") {
          
            $empleadoId=Input::POST('empleadoId');
     
          $this->empleado= Load::model('empleado')-> buscarEmpleado($empleadoId);
          $this->departamento= Load::model('departamento')-> listarXid($this->empleado->DEPARTAMENTO_ID);
          
        }
        else if (($this->operacion) == "BUSCA_EXISTENCIA_GENERAL") {
          
            $productoId=Input::POST('productoId');
     
          $this->producto= Load::model('producto')-> listarXid($productoId);
          
          
        }
        else if (($this->operacion) == "BUSCA_EXISTENCIA_ALMACEN") {
          
            $productoId=Input::POST('productoId');
            $almacenId=Input::POST('almacenId');
          $this->inventario= Load::model('inventario')-> existenciaProducto($productoId,$almacenId);
          
          
        }
         else if (($this->operacion) == "BUSCA_EXISTENCIA_LS") {
          
            
            if((Input::POST('opcion')=="lote") || (Input::POST('opcion')=="loteInventario")){
            $loteId=Input::POST('loteId');    
          $this->resultado= Load::model('lote')-> listarXid($loteId);
            }else if((Input::POST('opcion')=="serie") || (Input::POST('opcion')=="serieInventario")){
                $serieId=Input::POST('serieId');    
          $this->resultado= Load::model('serie')-> listarXid($serieId);
            }
          
        }
        
    }
}
?>

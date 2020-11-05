<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//Load::models('conceptos'); 
class ventaController extends AppController{
    
    /**
     * Muestra el partial de las nominas generadas
     */
    
     public function listadoCotizacion() {
        $cotizacion = new cotizacion();
        $this->result = $cotizacion->listarCotizacion();
        $this->campos = array(
            utf8_encode('#') => 'id',
            utf8_encode('TIPO DOCUMENTO') => 'tipo_documento',
            utf8_encode('DOCUMENTO') => 'documento',
            utf8_encode('F. DOCUMENTO') => 'fecha_documento',
            utf8_encode('CLIENTE') => 'nombrecompleto',
            utf8_encode('RFC') => 'rfc',
            utf8_encode('ESTADO') => 'estado'
            
           
        );
        $this->encabezado= "COTIZACIONES";
    }
     public function listadoPedido() {
        $cotizacion = new pedido();
        $this->result = $cotizacion->listarPedido();
        $this->campos = array(
            utf8_encode('#') => 'id',
            utf8_encode('TIPO DOCUMENTO') => 'tipo_documento',
            utf8_encode('ORDEN COMPRA') => 'orden_compra',
            utf8_encode('DOCUMENTO') => 'documento',
            utf8_encode('F. DOCUMENTO') => 'fecha_documento',
            utf8_encode('F. ENTREGA') => 'fecha_entrega',
            utf8_encode('CLIENTE') => 'nombrecompleto',
            utf8_encode('RFC') => 'rfc'
            
           
        );
        $this->encabezado= "PEDIDOS";
    }
    public function listadoVenta() {
        $cotizacion = new venta();
        $this->result = $cotizacion->listarVenta();
        $this->campos = array(
            utf8_encode('#') => 'id',
            utf8_encode('TIPO DOCUMENTO') => 'tipo_documento',
            utf8_encode('ORIGEN') => 'origen',
            utf8_encode('DOCUMENTO') => 'documento',
            utf8_encode('F. DOCUMENTO') => 'fecha_documento',
            utf8_encode('CLIENTE') => 'nombrecompleto',
            utf8_encode('RFC') => 'rfc',
            utf8_encode('ESTADO') => 'estado',
            utf8_encode('ESTADO') => 'estado'
            
           
        );
        $this->encabezado= "VENTAS";
    }
    public function crearCotizacion() 
    {
     $this->accion="crearCotizacion";
        
        $cotizacion = new cotizacion();
        $this->cotizacion = $cotizacion->find();

         if (Input::hasPost('cotizacion')) {
            $array_productos = Session::get('array_cotizacion');
            $num = count($array_productos);
           
            if($num == "0"){
               
                echo "<script>  alert ('Primero agrege partidas para poder guardar este movimiento....!');</script>"; 
            }else {
            $this->cotizacion = $cotizacion->guardarDatos();
            ?>
                <script>
                    window.open('reporteCotizacion/<?php echo $this->cotizacion; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
                </script>
               <?php
                Input::delete();
                Redirect::to('venta/crearCotizacion');
            }
        }else{
              session::delete('array_cotizacion');
        }
         Input::delete();
        $folios = new series_folios();
        $datoFolios = $folios->find_first("tipo = 'COTIZACION'");
        
        $this->num="C-".str_pad(($datoFolios->consecutivo)+1,4, "0", STR_PAD_LEFT);
    }
    public function crearPedido() 
    {
     $this->accion="crearPedido";
        
        $pedido = new pedido();
        $this->pedido = $pedido->find();

         if (Input::hasPost('pedido')) {
            $array_productos = Session::get('array_pedido');
            $num = count($array_productos);
           
            if($num == "0"){
               
                echo "<script>  alert ('Primero agrege partidas para poder guardar este movimiento....!');</script>"; 
            }else {
            $this->pedido = $pedido->guardarDatos();
            ?>
                <script>
                    window.open('reportePedido/<?php echo $this->pedido; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
                </script>
               <?php
                Input::delete();
                Redirect::to('venta/crearPedido');
            }
        }else{
              session::delete('array_pedido');
        }
         Input::delete();
        $folios = new series_folios();
        $datoFolios = $folios->find_first("tipo = 'PEDIDO'");
        
        $this->num="P-".str_pad(($datoFolios->consecutivo)+1,4, "0", STR_PAD_LEFT);
    }
    public function crearVenta() 
    {
     $this->accion="crearVenta";
        
        $venta = new venta();
        $this->venta = $venta->find();

         if (Input::hasPost('venta')) {
            $array_productos = Session::get('array_venta');
            $num = count($array_productos);
           
            if($num == "0"){
               
                echo "<script>  alert ('Primero agrege partidas para poder guardar este movimiento....!');</script>"; 
            }else {
            $this->venta = $venta->guardarDatos();
            ?>
                <script>
                    window.open('reporteVenta/<?php echo $this->venta; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
                </script>
               <?php
                Input::delete();
                Redirect::to('venta/crearVenta');
            }
        }else{
              session::delete('array_venta');
        }
         Input::delete();
        $folios = new series_folios();
        $datoFolios = $folios->find_first("tipo = 'REMISION'");
        
        $this->num="R-".str_pad(($datoFolios->consecutivo)+1,4, "0", STR_PAD_LEFT);
    }
     public function crearVale() 
    {
     $this->accion="crearVale";
        
        $venta = new venta();
        $this->venta = $venta->find();

         if (Input::hasPost('venta')) {
            $array_productos = Session::get('array_venta');
            $num = count($array_productos);
           
            if($num == "0"){
               
                echo "<script>  alert ('Primero agrege partidas para poder guardar este movimiento....!');</script>"; 
            }else {
            $this->venta = $venta->guardarDatosVale();
            ?>
                <script>
                    window.open('reporteVenta/<?php echo $this->venta; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
                </script>
               <?php
                Input::delete();
                Redirect::to('venta/crearVale');
            }
        }else{
              session::delete('array_venta');
        }
         Input::delete();
        $folios = new series_folios();
        $datoFolios = $folios->find_first("tipo = 'REMISION'");
        
        $this->num="R-".str_pad(($datoFolios->consecutivo)+1,4, "0", STR_PAD_LEFT);
    }
    public function crearSalidaCT() 
    {
     $this->accion="crearSalidaCT";
        
        $cotizacion = new cotizacion();
        $this->cotizacion = $cotizacion->find();

         if (Input::hasPost('cotizacion')) {
            $array_productos = Session::get('array_productos');
            $num = count($array_productos);
           
            if($num == "0"){
               
                echo "<script>  alert ('Primero agrege partidas para poder guardar este movimiento....!');</script>"; 
            }else {
            $this->cotizacion = $cotizacion->guardarDatosSCT();
            ?>
                <script>
                    window.open('cotizacionSalidaCT/<?php echo $this->cotizacion; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
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

       
        if (Input::hasPost('cotizacion')) {
            $array_productos = Session::get('array_productos');
            $num = count($array_productos);
           
            if($num == "0"){
               
                echo "<script>  alert ('Primero agrege partidas para poder guardar este movimiento....!');</script>"; 
            }else {
            $this->cotizacion = $cotizacion->guardarDatosSCT();
            ?>
                <script>
                    window.open('cotizacionSalidaCT/<?php echo $this->cotizacion; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
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
        
        $cotizacion = new cotizacion();
        $this->cotizacion = $cotizacion->find();

        if (Input::hasPost('cotizacion')) {
            $array_productos = Session::get('array_productos');
            $num = count($array_productos);
           
            if($num == "0"){
               
                echo "<script>  alert ('Primero agrege partidas para poder guardar este movimiento....!');</script>"; 
            }else {
            $this->cotizacion = $cotizacion->guardarDatos();
            ?>
                <script>
                    window.open('cotizacionEntrada/<?php echo $this->cotizacion; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
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
         $this->accion="inventarioIni";
        
        $cotizacion = new cotizacion();
        $this->cotizacion = $cotizacion->find();

        if (Input::hasPost('cotizacion')) {
            $array_productos = Session::get('array_productos');
            $num = count($array_productos);
           
            if($num == "0"){
               
                echo "<script>  alert ('Primero agrege partidas para poder guardar este movimiento....!');</script>"; 
            }else {
            $this->cotizacion = $cotizacion->guardarDatosIni();
            ?>
                <script>
                    window.open('cotizacionInicial/<?php echo $this->cotizacion; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
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
                    window.open('cotizacionEntrada/<?php echo $id; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
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
    public function cotizacionInicial($compraId){
     $compra=new cotizacion();
     $this->datosCompra=$compra->listarXid($compraId);
     $datosCompra=$this->datosCompra;
     $detalleCompra=new detalle_cotizacion();
     $this->detalleCompra=$detalleCompra->listarProductoCompra($compraId);
     $usuario=new usuario();
     $destino=new almacen();
     $this->destino=$destino->destino($compraId);
     $ciclo=new lote();
     $this->ciclo=$ciclo->ciclo($compraId);
     
     
    // $this->datosUsuario=$usuario->listarEmpleado($this->datosCompra->USUARIO_ID);
    }
    public function reporteCotizacion($compraId){
     $compra=new cotizacion();
     $this->datosCompra=$compra->listarXid($compraId);
     $detalleCompra=new detalle_cotizacion();
     $this->detalleCompra=$detalleCompra->listarProductoCompra($compraId);
     $usuario=new usuario();
     $destino=new almacen();
     $this->destino=$destino->destino($compraId);
     $ciclo=new lote();
     $this->ciclo=$ciclo->ciclo($compraId);
    // $this->datosUsuario=$usuario->listarEmpleado($this->datosCompra->USUARIO_ID);
    }
    public function reportePedido($compraId){
     $compra=new pedido();
     $this->datosCompra=$compra->listarXid($compraId);
     $detalleCompra=new detalle_pedido();
     $this->detalleCompra=$detalleCompra->listarProductoCompra($compraId);
     $usuario=new usuario();
     $destino=new almacen();
     $this->destino=$destino->destino($compraId);
     $ciclo=new lote();
     $this->ciclo=$ciclo->ciclo($compraId);
    // $this->datosUsuario=$usuario->listarEmpleado($this->datosCompra->USUARIO_ID);
    }
    public function reporteVenta($compraId){
     $compra=new pedido();
     $this->datosCompra=$compra->listarXidDC($compraId);
     $detalleCompra=new detalle_pedido();
     $this->detalleCompra=$detalleCompra->listarProductoCompra($compraId);
     $usuario=new usuario();
     $destino=new almacen();
     $this->destino=$destino->destino($compraId);
     $ciclo=new lote();
     $this->ciclo=$ciclo->ciclo($compraId);
    // $this->datosUsuario=$usuario->listarEmpleado($this->datosCompra->USUARIO_ID);
    }
    public function cotizacionEntradaCT($compraId){
     $compra=new cotizacion();
     $this->datosCompra=$compra->listarXid($compraId);
      $datosCompra=$this->datosCompra;
     $detalleCompra=new detalle_cotizacion();
     $this->detalleCompra=$detalleCompra->listarProductoCompra($compraId);
     $usuario=new usuario();
     $destino=new almacen();
     $this->destino=$destino->destino($compraId);
     $ciclo=new lote();
     $this->ciclo=$ciclo->ciclo($compraId);
     
     $this->referencia=$compra->find_by_sql("select documento from cotizacion where id='$datosCompra->referencia_id '");
    // $this->datosUsuario=$usuario->listarEmpleado($this->datosCompra->USUARIO_ID);
    }
    public function cotizacionSalida($entregaId){
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
            function libreriaVenta() {
        View::template(NULL);

        $this->operacion = Input::POST('operacion');


        if (($this->operacion) == "MOSTRAR") {
          $producto = new producto();  
          $this->listar=$producto->listar();  
        }
         else if (($this->operacion) == "BUSCA_UNIDADPRECIO") {
             $productoId=Input::POST('productoId');
          $producto = new producto();  
          $this->producto=$producto->listarXid($productoId);  
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
          
         $clase = new clase_venta();
            $this->mensaje = $clase-> introduce_movimiento(Input::POST('cantidad'), Input::POST('productoId'), Input::POST('precio'), Input::POST('lote'), Input::POST('fecha_caducidad'), Input::POST('serie'), Input::POST('opcion'),Input::POST('numInventario'));
           
        }
        else if (($this->operacion) == "AGREGAR_PARTIDA_PEDIDO") {
          
        $clase = new clase_venta();
            $this->mensaje = $clase-> introduce_movimiento_pedido(Input::POST('cantidad'), Input::POST('productoId'), Input::POST('precio'), Input::POST('lote'), Input::POST('fecha_caducidad'), Input::POST('serie'), Input::POST('opcion'),Input::POST('numInventario'));
           
        }
        else if (($this->operacion) == "AGREGAR_PARTIDA_VENTA") {
          
        $clase = new clase_venta();
            $this->mensaje = $clase-> introduce_movimiento_venta(Input::POST('cantidad'), Input::POST('productoId'), Input::POST('precio'), Input::POST('lote'), Input::POST('almacenId'), Input::POST('serie'), Input::POST('opcion'),Input::POST('numInventario'));
           
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
       $clase = new clase_venta();
            $this->mensaje = $clase-> quita_movimiento(Input::POST('linea'));
           
           
        }  
        else if (($this->operacion) == "QUITAR_PARTIDA_PEDIDO") {
       $clase = new clase_venta();
            $this->mensaje = $clase-> quita_movimiento_pedido(Input::POST('linea'));
         
           
        } 
        else if (($this->operacion) == "QUITAR_PARTIDA_VENTA") {
       $clase = new clase_venta();
            $this->mensaje = $clase-> quita_movimiento_venta(Input::POST('linea'));
         
           
        } 
        else if (($this->operacion) == "MOSTRAR_LISTA") {
          
          
        } 
        else if (($this->operacion) == "MOSTRAR_LISTA_VENTA") {
          
          
        }
        else if (($this->operacion) == "MOSTRAR_LISTA_VENTA") {
          
          
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
        else if (($this->operacion) == "BUSCA_CLIENTE") {
          
            $clienteId=Input::POST('clienteId');
     
          $this->cliente= Load::model('cliente')-> buscaCliente($clienteId);
          
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
    public function funciones() {
        require_once APP_PATH . 'libs/configuracion.php';
        View::template(NULL);

        $this->operacion = Input::post('operacion');
        if (($this->operacion) == "BUSCA_BANCO") {
            
            $this->condicion = "";
        }
        if (($this->operacion) == "BUSCA_MOVIMIENTO") {
            $idConcepto = Input::post('idConcepto');
            $this->tipoMovimiento = Load::Model('conceptos')->datosConceptoId($idConcepto);
        }
    } 
    public function envio($idVenta) {
        $this->venta=$idVenta;
        View::template(NULL);

        $envio = new envio();
        if (Input::hasPost('envio')) {

            $this->envio = $envio->guardarEnvio();
            
        }
        $this->envio=$envio->find_first("venta_id='$idVenta'");
    } 
    public function datosFactura($idVenta) {
        $this->venta=$idVenta;
        View::template(NULL);

        $envio = new envio();
        if (Input::hasPost('envio')) {

            $this->envio = $envio->guardarEnvio();
            
        }
        $this->envio=$envio->find_first("venta_id='$idVenta'");
    } 
    
}
?>

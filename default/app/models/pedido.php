<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class pedido extends ActiveRecord {

    

    protected function initialize() {
        
    }

    public function listar() {
       
        return $this->find();
        
        
    }
    public function listarXid($id) {
        return $this->find_by_sql("SELECT com.id, com.fecha_documento, com.fecha_entrega, com.documento, com.tipo_documento, cli.nombrecompleto,cli.rfc,com.estado,com.subtotal,com.iva,com.monto,com.costo_envio 
 ,cli.telefono,cli.correoelectronico,concat_ws(',',cli.calle,cli.numerointerior,cli.numeroexterior,cli.colonia,cli.ciudad,cli.estado,cli.pais,cli.codigopostal)  direccion FROM pedido as com inner join cliente as cli  WHERE com.cliente_id =cli.id and  com.id=$id");
    }
    public function listarXidDC($id) {
        return $this->find_by_sql("SELECT com.id, com.fecha_documento, com.fecha_entrega, com.documento, com.tipo_documento,cli.id, cli.nombrecompleto,cli.rfc,com.estado,com.subtotal,com.iva,com.monto,com.costo_envio 
 ,cli.telefono,cli.correoelectronico,concat_ws(',',cli.calle,cli.numerointerior,cli.numeroexterior)  direccion ,cli.colonia,cli.ciudad,cli.estado,cli.pais,cli.codigopostal FROM pedido as com inner join cliente as cli  WHERE com.cliente_id =cli.id and  com.id=$id");
    }
    public function listarProductoAlmacen($id) {
         $sqlProducto = "SELECT inv.ID, pro.CLAVE_ARTICULO, pro.DESCRIPCION, inv.EXISTENCIA, inv.CANTIDAD_MINIMA, inv.CANTIDAD_MAXIMA
FROM inventario as inv, producto as pro  WHERE ALMACEN_ID =$id and inv.PRODUCTO_ID= pro.ID ";
        return $this->find_all_by_sql($sqlProducto);
    }
    
    public function listarPedido() {
         
         $sqlCompra = "SELECT com.id, com.fecha_documento, com.fecha_entrega, com.documento, com.tipo_documento, cli.nombrecompleto,cli.rfc,com.estado 
FROM pedido as com inner join cliente as cli  WHERE com.cliente_id =cli.id  ";
        
        return $this->find_all_by_sql($sqlCompra);
    }
    
    public function guardarDatos(){
       $compra = new pedido(Input::post('pedido'));
        $fechaDocumento=$compra->fecha_documento;
        $fechaEntrega=$compra->fecha_entrega;
        $cliente_id=$compra->cliente_id;
        $folios = new series_folios();
           $folios->incrementarConsecutivo('PEDIDO');
        $datoFolios = $folios->find_first("tipo = 'PEDIDO'");
        $compra->documento="P-".str_pad(($datoFolios->consecutivo),4, "0", STR_PAD_LEFT);
        
        $compra->estado='1'; 
        $compra->fecha_documento = strftime("%Y-%m-%d", strtotime($compra->fecha_documento));
        $compra->fecha_entrega = strftime("%Y-%m-%d", strtotime($compra->fecha_entrega));
   
        $compra->usuario_id=Session::get('id'); 
        
        if( $compra->save()){
            $compraId=$compra->id;
             $array_productos = Session::get('array_pedido');
            $longitud = count($array_productos);
            $partidaTotal=0;
                    $importeTotal=0;
             for ($i = 0; $i < $longitud; $i++) {
              
   
 $productoId=  $array_productos[$i]['PRODUCTO_ID'] ;   
$codigo=$array_productos[$i]['CLAVE'];
$descripcion=$array_productos[$i]['DESCRIPCION'];
$cantidad=$array_productos[$i]['CANTIDAD'];
$precio=$array_productos[$i]['PRECIO'];
       $detalleCompra = new detalle_pedido();
              
            $partidaTotal=$cantidad*$precio;
         $importeTotal=$partidaTotal+$importeTotal;
              $detalleCompra->guardarDatos($compraId, $productoId, $cantidad,$precio);
              
              
             }
         //echo "<script>  jAlert ('Registro Insertado....!','AVISO');</script>";
           Input::delete();
           session::delete('array_pedido');
           $actualizar= new pedido();
           $actualizar->update_all("subtotal =  $importeTotal,iva=0,monto=$importeTotal", "id=$compraId ");
           return $compraId;
        }
    }
    public function guardarDatosIni(){
       $compra = new compra(Input::post('compra'));
        $fechaDocumento=$compra->fecha_documento;
        $fechaMovimiento=$compra->fecha_recepcion;
        $referencia=$compra->documento;
        $proveedorId=$compra->proveedor_id;
        
        $compra->estado='Activo'; 
        $compra->fecha_documento = strftime("%Y-%m-%d", strtotime($compra->fecha_documento));
        $compra->fecha_recepcion = strftime("%Y-%m-%d", strtotime($compra->fecha_recepcion));
        $compra->usuario_id=Session::get('id'); 
        $tipoMovimiento="E";
        $numeroMovimiento="8";
        if( $compra->save()){
            $compraId=$compra->id;
             $array_productos = Session::get('array_productos');
            $longitud = count($array_productos);
             for ($i = 0; $i < $longitud; $i++) {
              $detalleCompra = new detalle_compra();
              $lote=new detalle_lote();
              $serie=new serie();
              $movimiento=new movimiento_inventario();
              $producto=new producto();
              $inventario=new inventario();
            //  $datoInventario=new numeroInventario();
      $productoId=  $array_productos[$i]['PRODUCTO_ID'] ;
       $cantidad= $array_productos[$i]['CANTIDAD'] ;
       $opcion= "lote";
        $unidadXpaquete=$array_productos[$i]['UNIDAD_PAQUETE'];
        $fechaCaducidad=$array_productos[$i]['FECHA_CADUCIDAD'];
     $almacenId=$array_productos[$i]['ALMACEN_ID'];         
         $loteSerie=$array_productos[$i]['LOTE_SERIE'];
         $num_inventario=$array_productos[$i]['NUMERO_INVENTARIO'];
       
        
              $detalleCompra->guardarDatos($compraId, $productoId, $cantidad, $unidadXpaquete, $almacenId, $loteSerie, $fechaCaducidad,$num_inventario);
              if(($opcion=="lote") || ($opcion=="loteInventario")){
              $lote->valida_entrada($loteSerie,$fechaCaducidad,$productoId,$almacenId,$cantidad,$unidadXpaquete,$tipoMovimiento);    
              }else if (($opcion=="serie") || ($opcion=="serieInventario")){
              $serie->valida_entrada($loteSerie,$productoId,$almacenId,$cantidad,$unidadXpaquete,$tipoMovimiento);    
              }
              if($num_inventario!=""){
              $datoInventario -> valida_entrada($productoId,$almacenId,$loteSerie,$num_inventario,$cantidad,$unidadXpaquete,$tipoMovimiento);   
              }
              $inventario->actualizaInventario($productoId,$cantidad,$tipoMovimiento,$unidadXpaquete,$almacenId);
              $producto->actualizaProducto($productoId,$cantidad,$tipoMovimiento,$unidadXpaquete);
              $movimiento->validaMovimiento($productoId,$fechaDocumento,$referencia,$fechaMovimiento,$proveedorId, $cantidad, $unidadXpaquete, $almacenId, $loteSerie,$opcion,$numeroMovimiento,$num_inventario);
              
              
             }
          //echo "<script>  jAlert ('Registro Insertado....!','AVISO');</script>";
           Input::delete();
           session::delete('array_productos');
           return $compraId;
        }
    }
    public function cancelaCompra($id=NULL){
       $compra = new compra(); 
       $datosCompra=$compra->listarXid($id);
       $fechaDocumento=date('Y-m-d');
        $fechaMovimiento=date('Y-m-d');
        $referencia=$datosCompra->DOCUMENTO;
        $proveedorId=$datosCompra->PROVEEDOR_ID;
       
        $tipoMovimiento="S";
        $numeroMovimiento="12";
       
           
              $detalleCompra = new detalleCompra();
              $lote=new lote();
              $serie=new serie();
              $movimiento=new movimientoInventario();
              $producto=new producto();
              $inventario=new inventario();
              $numeroInventario=new numeroInventario();
              $datos=$detalleCompra->listarXCompra($id);
              foreach ($datos as $dato){
             $productoId=$dato->PRODUCTO_ID;
             $cantidad=$dato->CANTIDAD;     
             $producto=new producto();
             $detalleProducto=$producto->listarXid($productoId);
      if($detalleProducto->LOTE=="S"){
         $opcion="serie";
      }else if($detalleProducto->NUMERO_SERIE=="S"){
         $opcion="lote";
      }else{
          $opcion="";
      }  
        $unidadXpaquete=1;
        $fechaCaducidad=$dato->FECHA_CADUCIDAD;
     $almacenId=$dato->ALMACEN_ID;         
         $loteSerie=$dato->LOTE_SERIE;   
       $numInventario=$dato->NUMERO_INVENTARIO;
        
             // $detalleCompra->guardarDatos($compraId, $productoId, $cantidad, $unidadXpaquete, $almacenId, $loteSerie, $fechaCaducidad);
              if($opcion=="lote"){
              $lote->validaEntrada($loteSerie,$fechaCaducidad,$productoId,$almacenId,$cantidad,$unidadXpaquete,$tipoMovimiento);    
              }else if ($opcion=="serie"){
              $serie->validaEntrada($loteSerie,$productoId,$almacenId,$cantidad,$unidadXpaquete,$tipoMovimiento);    
              }
              if($detalleProducto->NUMERO_INVENTARIO=="S"){
                $inventarioId=$numeroInventario->obtenerIdInventario($productoId,$almacenId,$loteSerie,$numInventario);
                    $numeroInventario->actualizaInventario($inventarioId->ID,$cantidad,$unidadXpaquete,$tipoMovimiento);  
              }
              $inventario->actualizaInventario($productoId,$cantidad,$tipoMovimiento,$unidadXpaquete,$almacenId);
              $producto->actualizaProducto($productoId,$cantidad,$tipoMovimiento,$unidadXpaquete);
              $movimiento->validaMovimiento($productoId,$fechaDocumento,$referencia,$fechaMovimiento,$proveedorId, $cantidad, $unidadXpaquete, $almacenId, $loteSerie,$opcion,$numeroMovimiento);
              
              }
          $datosCompra->actualizaCompra($id);   
          echo "<script>  jAlert ('Registro Cancelado....!','AVISO');</script>";
           
    }
   public function actualizaCompra($id){
     $fecha_cancelacion=date('Y-m-d');
                  $this->update_all("FECHA_CANCELACION ='$fecha_cancelacion', ESTADO='Cancelado' ", "ID= $id");
               }
    public function buscarAlmacen($condicion) {
            return $this->find_all_by_sql("SELECT distinct(alm.ID), alm.DESCRIPCION
FROM almacen as alm, inventario as inv
WHERE inv.ALMACEN_ID = alm.ID
AND $condicion");
        }
}   
?>
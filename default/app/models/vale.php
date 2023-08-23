<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class vale extends ActiveRecord {

    

    protected function initialize() {
        
    }

    public function listar() {
       
        return $this->find();
        
        
    }
    public function listarXid($id) {
        return $this->find_by_sql("select * from vale where id=$id");
    }
    public function listarReferencia() {
        return $this->find_all_by_sql("select id, documento from vale where tipo='ST' order by  id desc");
    }
    public function listarProductoAlmacen($id) {
         $sqlProducto = "SELECT inv.ID, pro.CLAVE_ARTICULO, pro.DESCRIPCION, inv.EXISTENCIA, inv.CANTIDAD_MINIMA, inv.CANTIDAD_MAXIMA
FROM inventario as inv, producto as pro  WHERE ALMACEN_ID =$id and inv.PRODUCTO_ID= pro.ID ";
        return $this->find_all_by_sql($sqlProducto);
    }
    
    public function listarEntrada() {
         $ciclocosechaId = Session::get('cicloCosecha');
         $sqlCompra = "SELECT com.id, com.fecha_documento, com.fecha_recepcion, com.documento, com.tipo_documento, com.estado 
FROM vale as com  where tipo='E' and ciclocosecha_id='$ciclocosechaId' ";
        
        return $this->find_all_by_sql($sqlCompra);
    }
    public function listarCombinacion() {
         $ciclocosechaId = Session::get('cicloCosecha');
         $sqlCompra = "SELECT com.id, com.fecha_documento, com.fecha_recepcion, com.documento, com.tipo_documento, com.estado 
FROM vale as com  where tipo='CL' and ciclocosecha_id='$ciclocosechaId' ";
        
        return $this->find_all_by_sql($sqlCompra);
    }
     public function listarTraspaso() {
         $ciclocosechaId = Session::get('cicloCosecha');
         $sqlCompra = "SELECT com.id, com.fecha_documento, com.fecha_recepcion, com.documento, com.tipo_documento, com.estado 
FROM vale as com  where tipo='TA' and ciclocosecha_id='$ciclocosechaId'  order by com.id desc";
        
        return $this->find_all_by_sql($sqlCompra);
    }
    public function listarMovimiento() {
         $ciclocosechaId = Session::get('cicloCosecha');
         $sqlCompra = "SELECT com.id, com.fecha_documento, com.fecha_recepcion, com.documento, com.tipo_documento, com.estado 
FROM vale as com  where tipo='AI' and ciclocosecha_id='$ciclocosechaId' ";
        
        return $this->find_all_by_sql($sqlCompra);
    }
    public function listarSalidaCT() {
         $ciclocosechaId = Session::get('cicloCosecha');
         $sqlCompra = "SELECT com.id, com.fecha_documento, com.fecha_recepcion, com.documento, com.tipo_documento, com.estado 
FROM vale as com  where tipo='ST' and ciclocosecha_id='$ciclocosechaId' ";
        
        return $this->find_all_by_sql($sqlCompra);
    }
    public function listarEntradaCT() {
         $ciclocosechaId = Session::get('cicloCosecha');
         $sqlCompra = "SELECT com.id, com.fecha_documento, com.fecha_recepcion, com.documento, com.tipo_documento, com.estado 
FROM vale as com  where tipo='ET' and ciclocosecha_id='$ciclocosechaId' ";
        
        return $this->find_all_by_sql($sqlCompra);
    }
    
    public function guardarDatos(){
        
        $ciclocosechaId = Session::get('cicloCosecha');
        
       $vale = new vale(Input::post('vale'));
        $fechaDocumento=$vale->fecha_recepcion;
        $fechaMovimiento=date("Y-m-d");
        $referencia=$vale->documento;
        $proveedorId=$vale->proveedor_id;
        $folios = new series_folios();
           $folios->incrementarConsecutivo('VALEV');
        $datoFolios = $folios->find_first("tipo = 'VALEV'");
        $vale->documento="CV-".str_pad(($datoFolios->consecutivo),4, "0", STR_PAD_LEFT);
         $vale->tipo='E';
        $vale->estado='Activo'; 
        $vale->fecha_documento = strftime("%Y-%m-%d", strtotime($vale->fecha_recepcion));
        $vale->fecha_recepcion = date("Y-m-d");
        $vale->ciclocosecha_id=$ciclocosechaId; 
        $vale->usuario_id=Session::get('id'); 
        $tipoMovimiento="E";
        $numeroMovimiento="2";
        $pesoPaquete=0;
        $residuo=0;
        if( $vale->save()){
            $valeId=$vale->id;
             $array_productos = Session::get('array_productos');
            $longitud = count($array_productos);
             for ($i = 0; $i < $longitud; $i++) {
              $detalleCompra = new detalle_vale();
              $lote=new detalle_lote();
              $serie=new serie();
              $movimiento=new movimiento_inventario();
              $producto=new producto();
              $inventario=new inventario();
            //  $datoInventario=new numeroInventario();
      $productoId=  $array_productos[$i]['PRODUCTO_ID'] ;
       $cantidad= $array_productos[$i]['CANTIDAD'] ;
       $productod=$producto->find_first("id='$productoId'");
       $unidadXpaquete=$array_productos[$i]['UNIDAD_PAQUETE'];
       
        
       
       $opcion= "lote";
        
        $fechaCaducidad=$array_productos[$i]['FECHA_CADUCIDAD'];
     $almacenId=$array_productos[$i]['ALMACEN_ID'];         
         $loteSerie=$array_productos[$i]['LOTE_SERIE'];
         $num_inventario=$array_productos[$i]['NUMERO_INVENTARIO'];
       
        
              $detalleCompra->guardarDatos($valeId, $productoId, $cantidad, $unidadXpaquete, $almacenId, $loteSerie, $fechaCaducidad,$num_inventario,$unidadXpaquete);
              if(($opcion=="lote") || ($opcion=="loteInventario")){
              $lote->valida_entrada($loteSerie,$fechaCaducidad,$productoId,$almacenId,$cantidad,$unidadXpaquete,$tipoMovimiento);    
              }
              
              if($num_inventario!=""){
              $datoInventario -> valida_entrada($productoId,$almacenId,$loteSerie,$num_inventario,$cantidad,$unidadXpaquete,$tipoMovimiento);   
              }
              $inventario->actualizaInventario($productoId,$cantidad,$tipoMovimiento,$unidadXpaquete,$almacenId);
              $producto->actualizaProducto($productoId,$cantidad,$tipoMovimiento,$unidadXpaquete);
              if($proveedorId==NULL)$proveedorId='0';
              $movimiento->validaMovimiento($productoId,$fechaDocumento,$referencia,$fechaMovimiento,$proveedorId, $cantidad, $unidadXpaquete, $almacenId, $loteSerie,$opcion,$numeroMovimiento,$num_inventario);
              
              
             }
          //echo "<script>  jAlert ('Registro Insertado....!','AVISO');</script>";
           Input::delete();
           session::delete('array_productos');
           return $valeId;
        }
    }
    public function guardarDatosIni(){
       $vale = new vale(Input::post('vale'));
        $fechaDocumento=$vale->fecha_recepcion;
        $fechaMovimiento=date("Y-m-d");
        $referencia=$vale->documento;
        $proveedorId=$vale->proveedor_id;
        $folios = new series_folios();
           $folios->incrementarConsecutivo('VALE');
        $datoFolios = $folios->find_first("tipo = 'VALE'");
        $vale->documento="V-".str_pad(($datoFolios->consecutivo),4, "0", STR_PAD_LEFT);
         $vale->tipo='I';
        $vale->estado='Activo'; 
        $vale->fecha_documento = strftime("%Y-%m-%d", strtotime($vale->fecha_recepcion));
        $vale->fecha_recepcion = date("Y-m-d");
        $vale->usuario_id=Session::get('id'); 
        $tipoMovimiento="E";
        $numeroMovimiento="8";
        if( $vale->save()){
            $valeId=$vale->id;
             $array_productos = Session::get('array_productos');
            $longitud = count($array_productos);
             for ($i = 0; $i < $longitud; $i++) {
              $detalleCompra = new detalle_vale();
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
         $productod=$producto->find_first("id='$productoId'");
       if($productod->medida_id=="1"){
 $unidadEmpaque=$unidadXpaquete;
 $pesoEmpaque=$productod->peso_empaque;
 $paquete= $cantidad/$unidadEmpaque;
 $residuo= $cantidad%$unidadEmpaque;
 $paquete=floor($paquete);
 if($residuo > $pesoEmpaque){$paquete++; $pesoPaquete=$paquete;  }
 else {$pesoPaquete=$paquete;}
 $cantidadA=$cantidad-($pesoPaquete);
             }else{
                 $cantidadA=$cantidad;
             }  
        
              $detalleCompra->guardarDatos($valeId, $productoId, $cantidad, $unidadXpaquete, $almacenId, $loteSerie, $fechaCaducidad,$num_inventario);
              if(($opcion=="lote") || ($opcion=="loteInventario")){
              $lote->valida_entrada($loteSerie,$fechaCaducidad,$productoId,$almacenId,$cantidad,$unidadXpaquete,$tipoMovimiento);    
              }else if (($opcion=="serie") || ($opcion=="serieInventario")){
              $serie->valida_entrada($loteSerie,$productoId,$almacenId,$cantidad,$unidadXpaquete,$tipoMovimiento);    
              }
              if($num_inventario!=""){
              $datoInventario -> valida_entrada($productoId,$almacenId,$loteSerie,$num_inventario,$cantidad,$unidadXpaquete,$tipoMovimiento);   
              }
              $inventario->actualizaInventario($productoId,$cantidadA,$tipoMovimiento,$unidadXpaquete,$almacenId);
              $producto->actualizaProducto($productoId,$cantidadA,$tipoMovimiento,$unidadXpaquete);
              if($proveedorId==NULL)$proveedorId='0';
              $movimiento->validaMovimiento($productoId,$fechaDocumento,$referencia,$fechaMovimiento,$proveedorId, $cantidadA, $unidadXpaquete, $almacenId, $loteSerie,$opcion,$numeroMovimiento,$num_inventario);
              
              
             }
          //echo "<script>  jAlert ('Registro Insertado....!','AVISO');</script>";
           Input::delete();
           session::delete('array_productos');
           return $valeId;
        }
    }
    public function guardarDatosSCT(){
        $ciclocosechaId = Session::get('cicloCosecha');
       $vale = new vale(Input::post('vale'));
        $fechaDocumento=$vale->fecha_recepcion;
        $fechaMovimiento=$vale->fecha_recepcion;
        $referencia=$vale->documento;
        $proveedorId=$vale->proveedor_id;
        $folios = new series_folios();
           $folios->incrementarConsecutivo('VALET');
        $datoFolios = $folios->find_first("tipo = 'VALET'");
        $vale->documento="ST-".str_pad(($datoFolios->consecutivo),4, "0", STR_PAD_LEFT);
         $vale->tipo='ST';
        $vale->estado='Activo'; 
        $vale->fecha_documento = strftime("%Y-%m-%d", strtotime($vale->fecha_recepcion));
        $vale->fecha_recepcion = strftime("%Y-%m-%d", strtotime($vale->fecha_recepcion));
        $vale->fecha_salida = strftime("%Y-%m-%d", strtotime($vale->fecha_salida));
        $vale->ciclocosecha_id=$ciclocosechaId; 
        $vale->usuario_id=Session::get('id'); 
        $tipoMovimiento="S";
        $numeroMovimiento="12";
        if( $vale->save()){
            $valeId=$vale->id;
             $array_productos = Session::get('array_productos');
            $longitud = count($array_productos);
             for ($i = 0; $i < $longitud; $i++) {
              $detalleCompra = new detalle_vale();
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
       
        
              $detalleCompra->guardarDatos($valeId, $productoId, $cantidad, $unidadXpaquete, $almacenId, $loteSerie, $fechaCaducidad,$num_inventario);
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
              if($proveedorId==NULL)$proveedorId='0';
              $movimiento->validaMovimiento($productoId,$fechaDocumento,$referencia,$fechaMovimiento,$proveedorId, $cantidad, $unidadXpaquete, $almacenId, $loteSerie,$opcion,$numeroMovimiento,$num_inventario);
              
              
             }
          //echo "<script>  jAlert ('Registro Insertado....!','AVISO');</script>";
           Input::delete();
           session::delete('array_productos');
           return $valeId;
        }
    }
    public function guardarDatosTSEA(){
        $ciclocosechaId = Session::get('cicloCosecha');
       $vale = new vale(Input::post('traspaso'));
        $fechaDocumento=$vale->fecha_documento;
        $fechaMovimiento=$vale->fecha_documento;
        $referencia=$vale->documento;
        $proveedorId=$vale->proveedor_id;
        $folios = new series_folios();
           $folios->incrementarConsecutivo('VALE');
        $datoFolios = $folios->find_first("tipo = 'VALE'");
        $vale->documento="V-".str_pad(($datoFolios->consecutivo),4, "0", STR_PAD_LEFT);
         $vale->tipo='TA';
        $vale->estado='Pendiente'; 
        $vale->fecha_documento = strftime("%Y-%m-%d", strtotime($vale->fecha_documento));
        $vale->fecha_recepcion = strftime("%Y-%m-%d", strtotime($vale->fecha_documento));
        $vale->fecha_salida = strftime("%Y-%m-%d", strtotime($vale->fecha_documento));
        $vale->ciclocosecha_id=$ciclocosechaId; 
        $vale->usuario_id=Session::get('id'); 
        $vale->destino_id=Input::post('destino_id'); 
        $tipoMovimiento="S";
        $numeroMovimiento="18";
        if( $vale->save()){
            $valeId=$vale->id;
             $array_productos = Session::get('array_productos');
            $longitud = count($array_productos);
             for ($i = 0; $i < $longitud; $i++) {
              $detalleCompra = new detalle_vale();
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
       
        
              $detalleCompra->guardarDatos($valeId, $productoId, $cantidad, $unidadXpaquete, $almacenId, $loteSerie, $fechaCaducidad,$num_inventario);
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
              if($proveedorId==NULL)$proveedorId='0';
              $movimiento->validaMovimiento($productoId,$fechaDocumento,$referencia,$fechaMovimiento,$proveedorId, $cantidad, $unidadXpaquete, $almacenId, $loteSerie,$opcion,$numeroMovimiento,$num_inventario);
              
              
             }
        }
             
             
             
          /* echo "<script>  jAlert ('Registro Insertado....!','AVISO');</script>";
           
        $vale->usuario_id=Session::get('id'); 
        $tipoMovimiento="E";
        $numeroMovimiento="7";
        
            $valeId=$vale->id;
             $array_productos = Session::get('array_productos');
            $longitud = count($array_productos);
             for ($i = 0; $i < $longitud; $i++) {
              $detalleCompra = new detalle_vale();
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
     $almacenId=Input::post('destino_id');       
         $loteSerie=$array_productos[$i]['LOTE_SERIE'];
         $num_inventario=$array_productos[$i]['NUMERO_INVENTARIO'];
       
        
              $detalleCompra->guardarDatos($valeId, $productoId, $cantidad, $unidadXpaquete, $almacenId, $loteSerie, $fechaCaducidad,$num_inventario);
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
            */ 
             
           Input::delete();
           session::delete('array_productos');
           return $valeId;
        
    }
    public function guardarDatosTSEN($id){
       
             $datoVale=new vale();
             $vale=$datoVale->find_first($id);
              $vale->estado='Activo'; 
              $vale->update();
          $fechaDocumento=$vale->fecha_documento;
        $fechaMovimiento=$vale->fecha_documento;
        $referencia=$vale->documento;
        $proveedorId=$vale->proveedor_id; 
        
        $tipoMovimiento="E";
        $numeroMovimiento="7";
        
            $valeId=$id;
            $registro=new detalle_vale();
            $registros=$registro->find("compra_id='$id'");
            foreach ($registros as $dato) {
              $detalleCompra = new detalle_vale();
              $lote=new detalle_lote();
              $serie=new serie();
              $movimiento=new movimiento_inventario();
              $producto=new producto();
              $inventario=new inventario();
            //  $datoInventario=new numeroInventario();
      $productoId=  $dato->producto_id;
       $cantidad= $dato->cantidad;
       $opcion= "lote";
        $unidadXpaquete="";
        $fechaCaducidad="";
     $almacenId=$vale->destino_id;       
         $loteSerie=$dato->lote_id;
         $num_inventario="";
       
        
              $detalleCompra->guardarDatos($valeId, $productoId, $cantidad, $unidadXpaquete, $almacenId, $loteSerie, $fechaCaducidad,$num_inventario);
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
              if($proveedorId==NULL)$proveedorId='0';
              $movimiento->validaMovimiento($productoId,$fechaDocumento,$referencia,$fechaMovimiento,$proveedorId, $cantidad, $unidadXpaquete, $almacenId, $loteSerie,$opcion,$numeroMovimiento,$num_inventario);
              
              
             }  
             
             
           
        
    }
     public function guardarDatosECT(){
         $ciclocosechaId = Session::get('cicloCosecha');
       $vale = new vale(Input::post('vale'));
        $fechaDocumento=$vale->fecha_recepcion;
        $fechaMovimiento=$vale->fecha_recepcion;
        $referencia=$vale->documento;
        $proveedorId=$vale->proveedor_id;
        $folios = new series_folios();
           $folios->incrementarConsecutivo('VALEET');
        $datoFolios = $folios->find_first("tipo = 'VALEET'");
        $vale->documento="ET-".str_pad(($datoFolios->consecutivo),4, "0", STR_PAD_LEFT);
         $vale->tipo='ET';
        $vale->estado='Activo'; 
        $vale->fecha_documento = strftime("%Y-%m-%d", strtotime($vale->fecha_recepcion));
        $vale->fecha_recepcion = strftime("%Y-%m-%d", strtotime($vale->fecha_recepcion));
        $vale->fecha_salida = strftime("%Y-%m-%d", strtotime($vale->fecha_salida));
        $vale->ciclocosecha_id=$ciclocosechaId; 
        $vale->usuario_id=Session::get('id'); 
        $tipoMovimiento="E";
        $numeroMovimiento="3";
        if( $vale->save()){
            $valeId=$vale->id;
             $array_productos = Session::get('array_productos');
            $longitud = count($array_productos);
             for ($i = 0; $i < $longitud; $i++) {
              $detalleCompra = new detalle_vale();
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
       $productod=$producto->find_first("id='$productoId'");
         
        
              $detalleCompra->guardarDatos($valeId, $productoId, $cantidad, $unidadXpaquete, $almacenId, $loteSerie, $fechaCaducidad,$num_inventario);
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
              if($proveedorId==NULL)$proveedorId='0';
              $movimiento->validaMovimiento($productoId,$fechaDocumento,$referencia,$fechaMovimiento,$proveedorId, $cantidad, $unidadXpaquete, $almacenId, $loteSerie,$opcion,$numeroMovimiento,$num_inventario);
              
              
             }
          //echo "<script>  jAlert ('Registro Insertado....!','AVISO');</script>";
           Input::delete();
           session::delete('array_productos');
           return $valeId;
        }
    }
    public function guardarAjuste($movimiento){
        $ciclocosechaId = Session::get('cicloCosecha');
       $vale = new vale(Input::post('traspaso'));
        $fechaDocumento=$vale->fecha_documento;
        $fechaMovimiento=$vale->fecha_documento;
        $referencia=$vale->documento;
        $proveedorId=$vale->proveedor_id;
        $folios = new series_folios();
           $folios->incrementarConsecutivo('VALE');
        $datoFolios = $folios->find_first("tipo = 'VALE'");
        $vale->documento="V-".str_pad(($datoFolios->consecutivo),4, "0", STR_PAD_LEFT);
         $vale->tipo='AI';
        $vale->estado='Activo'; 
        $vale->fecha_documento = strftime("%Y-%m-%d", strtotime($vale->fecha_documento));
        $vale->fecha_recepcion = strftime("%Y-%m-%d", strtotime($vale->fecha_documento));
        $vale->fecha_salida = strftime("%Y-%m-%d", strtotime($vale->fecha_documento));
        $vale->ciclocosecha_id=$ciclocosechaId; 
        $vale->usuario_id=Session::get('id'); 
        $movimiento=$movimiento;
        $concepto=new concepto_movimiento();
        $concepto=$concepto->listarXid($movimiento);
        $tipoMovimiento=$concepto->tipo_movimiento;
        $numeroMovimiento=$movimiento;
        if( $vale->save()){
            $valeId=$vale->id;
             $array_productos = Session::get('array_productos');
            $longitud = count($array_productos);
             for ($i = 0; $i < $longitud; $i++) {
              $detalleCompra = new detalle_vale();
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
       
        
              $detalleCompra->guardarDatos($valeId, $productoId, $cantidad, $unidadXpaquete, $almacenId, $loteSerie, $fechaCaducidad,$num_inventario);
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
              if($proveedorId==NULL)$proveedorId='0';
              $movimiento->validaMovimiento($productoId,$fechaDocumento,$referencia,$fechaMovimiento,$proveedorId, $cantidad, $unidadXpaquete, $almacenId, $loteSerie,$opcion,$numeroMovimiento,$num_inventario);
              
              
             }
          //echo "<script>  jAlert ('Registro Insertado....!','AVISO');</script>";
           Input::delete();
           session::delete('array_productos');
           return $valeId;
        }
    }
    public function cancelaCompra($id=NULL){
       $vale = new vale(); 
       $datosCompra=$vale->listarXid($id);
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
        
             // $detalleCompra->guardarDatos($valeId, $productoId, $cantidad, $unidadXpaquete, $almacenId, $loteSerie, $fechaCaducidad);
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
              if($proveedorId==NULL)$proveedorId='0';
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
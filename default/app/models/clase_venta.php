<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php

class clase_venta {

    function introduce_movimiento( $cantidad,$productoId, $precio,$lote,$fecha_caducidad,$serie,$opcion,$num_inventario,$presentacionV,$presentacion,$comentario) {
        
        
        $array_productos = Session::get('array_cotizacion');
        $num = count($array_productos);
        $array_productos[$num]['PRODUCTO_ID'] = $productoId;
        $producto=new producto();
        $detalle=$producto->listarXid($productoId);
        $array_productos[$num]['CLAVE'] = $detalle->clave;
        $array_productos[$num]['DESCRIPCION'] = $detalle->descripcion;
        $array_productos[$num]['CANTIDAD'] = $cantidad;
        $array_productos[$num]['MEDIDA'] = $detalle->medida;
        $array_productos[$num]['PRECIO'] = $precio;
        $array_productos[$num]['TASA'] = $detalle->impuesto;
        $array_productos[$num]['UNIDAD_ENTRADA'] = $detalle->empaque;
        $array_productos[$num]['PRESENTACIONV'] = $presentacionV;
        $array_productos[$num]['PRESENTACION'] = $presentacion;
        $array_productos[$num]['COMENTARIO'] = $comentario;
        
        Session::set('array_cotizacion', $array_productos);
    }
    function introduce_movimiento_pedido( $cantidad,$productoId, $precio,$lote,$fecha_caducidad,$serie,$opcion,$num_inventario,$presentacionV,$presentacion) {
        
        
        $array_productos = Session::get('array_pedido');
        $num = count($array_productos);
        $array_productos[$num]['PRODUCTO_ID'] = $productoId;
        $producto=new producto();
        $detalle=$producto->listarXid($productoId);
        $array_productos[$num]['CLAVE'] = $detalle->clave;
        $array_productos[$num]['DESCRIPCION'] = $detalle->descripcion;
        $array_productos[$num]['CANTIDAD'] = $cantidad;
        $array_productos[$num]['MEDIDA'] = $detalle->medida;
        $array_productos[$num]['PRECIO'] = $precio;
         $array_productos[$num]['TASA'] = $detalle->impuesto;
        $array_productos[$num]['UNIDAD_ENTRADA'] = $detalle->empaque;
        $array_productos[$num]['PRESENTACIONV'] = $presentacionV;
        $array_productos[$num]['PRESENTACION'] = $presentacion;
        
        Session::set('array_pedido', $array_productos);
    }
    function introduce_movimiento_venta($cantidad,$productoId,$precio,$lote,$almacenId,$serie,$opcion,$num_inventario,$presentacionV,$presentacion) {
        
        $fecha_caducidad="";
        $num=0;
        $array_productos = Session::get('array_venta');
        if(isset($array_productos))
        $num = count($array_productos);
        $array_productos[$num]['PRODUCTO_ID'] = $productoId;
        $producto=new producto();
        $detalle=$producto->listarXid($productoId);
        $array_productos[$num]['CLAVE'] = $detalle->clave;
        $array_productos[$num]['DESCRIPCION'] = $detalle->descripcion;
        $array_productos[$num]['CANTIDAD'] = $cantidad;
        $array_productos[$num]['MEDIDA'] = $detalle->medida;
        $array_productos[$num]['PRECIO'] = $precio;
        $array_productos[$num]['TASA'] = $detalle->impuesto;
        $array_productos[$num]['PRESENTACIONV'] = $presentacionV;
        $array_productos[$num]['PRESENTACION'] = $presentacion;
        //$presentacion=new presentacion();
        //$detalle_presentacion=$presentacion->listarXid($detalle->UNIDAD_ENTRADA);
        $array_productos[$num]['UNIDAD_ENTRADA'] = $detalle->empaque;
        $array_productos[$num]['OPCION']=$opcion;
        if(($detalle->UNIDAD_PAQUETE=="") || ($detalle->UNIDAD_PAQUETE==0)){
        $array_productos[$num]['UNIDAD_PAQUETE']=1;        
        }else{
        $array_productos[$num]['UNIDAD_PAQUETE']=$detalle->UNIDAD_PAQUETE;    
        }
        $consultaExis=new detalle_lote();
        $lote=$consultaExis->find_first($lote);
        $lote=@$lote->lote_id;
        $loteD=new lote();
        $detalle_lote=$loteD->listarXid($lote);
        $array_productos[$num]['LOTE_CODIGO'] = $detalle_lote->codigo;
        if(($lote!="") and ($serie=="")){
        $array_productos[$num]['LOTE_SERIE'] = $lote;
        }else if(($lote=="") and ($serie!="")){
          $array_productos[$num]['LOTE_SERIE'] = $serie;  
        }  else {
         $array_productos[$num]['LOTE_SERIE'] = "";   
        }
        $array_productos[$num]['FECHA_CADUCIDAD'] = $fecha_caducidad;
        $array_productos[$num]['NUMERO_INVENTARIO']= $num_inventario;
        $array_productos[$num]['ALMACEN_ID']= $almacenId;
          $almacen=new almacen();
        $detalle_almacen=$almacen->listarXid($almacenId);     
        $array_productos[$num]['ALMACEN']= $detalle_almacen->almacen;
        $array_productos[$num]['DESCRIPCION_ALMACEN']= $detalle_almacen->descripcion;
        
        Session::set('array_venta', $array_productos);
    }
    function introduce_movimiento_anticipo($cantidad,$productoId,$precio,$lote,$almacenId,$serie,$opcion,$num_inventario,$presentacionV,$presentacion) {
        
        $fecha_caducidad="";
        $num=0;
        $array_productos = Session::get('array_anticipo');
        if(isset($array_productos))
        $num = count($array_productos);
        $array_productos[$num]['PRODUCTO_ID'] = $productoId;
        $producto=new producto();
        $detalle=$producto->listarXid($productoId);
        $array_productos[$num]['CLAVE'] = $detalle->clave;
        $array_productos[$num]['DESCRIPCION'] = $detalle->descripcion;
        $array_productos[$num]['CANTIDAD'] = $cantidad;
        $array_productos[$num]['MEDIDA'] = $detalle->medida;
        $array_productos[$num]['PRECIO'] = $precio;
        $array_productos[$num]['TASA'] = $detalle->impuesto;
        $array_productos[$num]['PRESENTACIONV'] = $presentacionV;
        $array_productos[$num]['PRESENTACION'] = $presentacion;
        //$presentacion=new presentacion();
        //$detalle_presentacion=$presentacion->listarXid($detalle->UNIDAD_ENTRADA);
        $array_productos[$num]['UNIDAD_ENTRADA'] = $detalle->empaque;
        $array_productos[$num]['OPCION']=$opcion;
        if(($detalle->UNIDAD_PAQUETE=="") || ($detalle->UNIDAD_PAQUETE==0)){
        $array_productos[$num]['UNIDAD_PAQUETE']=1;        
        }else{
        $array_productos[$num]['UNIDAD_PAQUETE']=$detalle->UNIDAD_PAQUETE;    
        }
        $consultaExis=new detalle_lote();
        $lote=$consultaExis->find_first($lote);
        $lote=@$lote->lote_id;
        $loteD=new lote();
        $detalle_lote=$loteD->listarXid($lote);
        $array_productos[$num]['LOTE_CODIGO'] = $detalle_lote->codigo;
        if(($lote!="") and ($serie=="")){
        $array_productos[$num]['LOTE_SERIE'] = $lote;
        }else if(($lote=="") and ($serie!="")){
          $array_productos[$num]['LOTE_SERIE'] = $serie;  
        }  else {
         $array_productos[$num]['LOTE_SERIE'] = "";   
        }
        $array_productos[$num]['FECHA_CADUCIDAD'] = $fecha_caducidad;
        $array_productos[$num]['NUMERO_INVENTARIO']= $num_inventario;
        $array_productos[$num]['ALMACEN_ID']= $almacenId;
          $almacen=new almacen();
        $detalle_almacen=$almacen->listarXid($almacenId);     
        $array_productos[$num]['ALMACEN']= $detalle_almacen->almacen;
        $array_productos[$num]['DESCRIPCION_ALMACEN']= $detalle_almacen->descripcion;
        
        Session::set('array_anticipo', $array_productos);
    }
    function guarda_movimiento($idSujeto, $importe, $formaPago, $fechaElaboracion, $referencia, $idCobro, $idRecibo, $conceptoDescuento) {


        $array_datos_ventanilla = Session::get('array_datos_ventanilla');
        $longitud = count($array_datos_ventanilla);
        $monto = $importe;
        if ($longitud != 0) {
            for ($num = 0; $num < $longitud; $num++) {
                if ($array_datos_ventanilla[$num]['ID_SUJETO'] == $idSujeto) {
                    if ($monto > 0) {
                        if ($monto >= $array_datos_ventanilla[$num]['IMPORTE_ACTUAL']) {
                            $monto = $monto - $array_datos_ventanilla[$num]['IMPORTE_ACTUAL'];
                            $array_datos_descuento = Session::get('array_datos_descuento');
                            $longitudDescuento = count($array_datos_descuento);
                            if ($longitudDescuento > 0) {
                                for ($des = 0; $des < $longitudDescuento; $des++) {
                                    if ((($array_datos_ventanilla[$num]['CLAVE_CRI']) == ($array_datos_descuento[$des]['CLAVE'])) and (($array_datos_descuento[$des]['ID_SUJETO']) == ($idSujeto))) {

                                        $idDescuento = $array_datos_descuento[$des]['ID'];
                                        $importe = $array_datos_ventanilla[$num]['IMPORTE_ACTUAL'];
                                        $porcentaje = $array_datos_descuento[$des]['PORCENTAJE'];
                                        $importeDescuento = ($importe * $porcentaje) / 100;
                                        $importeNeto = $importe - $importeDescuento;
                                        $importeActual = $importeDescuento;
                                        $resultado = load::model('cuentaCobrar')->insertaCuenta($idSujeto, $importeNeto, $importeActual, $formaPago, $array_datos_ventanilla[$num]['ID_CUENTA'], $array_datos_ventanilla[$num]['ID_PERIODICIDAD'], $array_datos_ventanilla[$num]['CLAVE_CRI'], $fechaElaboracion, $referencia,$idCobro,NULL, $array_datos_ventanilla[$num]['CUENTA_ID']);
                                        $importeActual = 0;
                                        $resultado = load::model('cuentaCobrar')->insertaCuenta($idSujeto, $importeDescuento, $importeActual, $conceptoDescuento, $array_datos_ventanilla[$num]['ID_CUENTA'], $array_datos_ventanilla[$num]['ID_PERIODICIDAD'], $array_datos_ventanilla[$num]['CLAVE_CRI'], $fechaElaboracion, $referencia,$idCobro, $idDescuento, $array_datos_ventanilla[$num]['CUENTA_ID']);
                                        $idCuentaCobrar = $resultado;
                                        $recibosDescuentos = load::Model('recibosDescuentos')->guardarDatos($idRecibo, $idCuentaCobrar, $idDescuento);
                                        $des = $longitudDescuento;
                                    }
                                }
                            } else {                               
                                $importeActual = 0;
                                $resultado = load::model('cuentaCobrar')->insertaCuenta($idSujeto, $array_datos_ventanilla[$num]['IMPORTE_ACTUAL'], $importe_actual, $formaPago, $array_datos_ventanilla[$num]['ID_CUENTA'], $array_datos_ventanilla[$num]['ID_PERIODICIDAD'], $array_datos_ventanilla[$num]['CLAVE_CRI'], $fechaElaboracion, $referencia, $idCobro,NULL, $array_datos_ventanilla[$num]['CUENTA_ID']);
                            }
                         }else {
                            
                            $array_datos_descuento = Session::get('array_datos_descuento');
                            $longitudDescuento = count($array_datos_descuento);
                            if ($longitudDescuento > 0) {
                                for ($des = 0; $des < $longitudDescuento; $des++) {
                                    if ((($array_datos_ventanilla[$num]['CLAVE_CRI']) == ($array_datos_descuento[$des]['CLAVE'])) and (($array_datos_descuento[$des]['ID_SUJETO']) == ($idSujeto))) {
                                      
                                        $idDescuento = $array_datos_descuento[$des]['ID'];
                                        $importe = $array_datos_ventanilla[$num]['IMPORTE_ACTUAL'];
                                        $porcentaje = $array_datos_descuento[$des]['PORCENTAJE'];
                                        $importeDescuento = ($importe * $porcentaje) / 100;
                                        $importeNeto = $importe - $importeDescuento;
                                        $importeActual = $importeDescuento;                                     
                                        //$importeActual=0;
                                        $resultado = load::model('cuentaCobrar')->insertaCuenta($idSujeto, $importeNeto, $importeActual, $formaPago, $array_datos_ventanilla[$num]['ID_CUENTA'], $array_datos_ventanilla[$num]['ID_PERIODICIDAD'], $array_datos_ventanilla[$num]['CLAVE_CRI'], $fechaElaboracion, $referencia,$idCobro,NULL,$array_datos_ventanilla[$num]['CUENTA_ID']);
                                        $importeActual=0;  
                                        $resultado = load::model('cuentaCobrar')->insertaCuenta($idSujeto, $importeDescuento,$importeActual, $conceptoDescuento, $array_datos_ventanilla[$num]['ID_CUENTA'], $array_datos_ventanilla[$num]['ID_PERIODICIDAD'], $array_datos_ventanilla[$num]['CLAVE_CRI'], $fechaElaboracion, $referencia,$idCobro, $idDescuento, $array_datos_ventanilla[$num]['CUENTA_ID']);
                                        $idCuentaCobrar = $resultado;
                                        $recibosDescuentos = load::Model('recibosDescuentos')->guardarDatos($idRecibo, $idCuentaCobrar, $idDescuento);
                                        $des = $longitudDescuento;
                                    }
                                }
                            } else {
                                $importe = $array_datos_ventanilla[$num]['IMPORTE_ACTUAL'];
                                $importeActual = 0;
                                $resultado = load::model('cuentaCobrar')->insertaCuenta($idSujeto, $monto, $importeActual, $formaPago, $array_datos_ventanilla[$num]['ID_CUENTA'], $array_datos_ventanilla[$num]['ID_PERIODICIDAD'], $array_datos_ventanilla[$num]['CLAVE_CRI'], $fechaElaboracion, $referencia, $idCobro,NULL, $array_datos_ventanilla[$num]['CUENTA_ID']);
                                $monto = 0;
                            }
                        }
                    }
                }
            }
            return $mensaje = "inserto";
        } else {
            return $mensaje = "NoExiste";
        }
    }

    function quita_movimiento($linea) {
        $array_productos = Session::get('array_cotizacion');
        unset($array_productos[$linea]);
        $array_productos = array_values($array_productos);
        Session::set('array_cotizacion', $array_productos);
    }
    function quita_movimiento_pedido($linea) {
        $array_productos = Session::get('array_pedido');
        unset($array_productos[$linea]);
        $array_productos = array_values($array_productos);
        Session::set('array_pedido', $array_productos);
    }
    function quita_movimiento_venta($linea) {
        $array_productos = Session::get('array_venta');
        unset($array_productos[$linea]);
        $array_productos = array_values($array_productos);
        Session::set('array_venta', $array_productos);
    }

}

?>

<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php

class claseSalida {

    function introduce_movimiento( $cantidad,$productoId, $almacenId,$loteId,$serieId,$opcion,$numInventario) {
        
        
        $array_salida = Session::get('array_salida');
        $num = count($array_salida);
        $array_salida[$num]['PRODUCTO_ID'] = $productoId;
        $producto=new producto();
        $detalle=$producto->listarXid($productoId);
        $array_salida[$num]['CLAVE'] = $detalle->CLAVE_ARTICULO;
        $array_salida[$num]['DESCRIPCION'] = $detalle->DESCRIPCION;
        $array_salida[$num]['CANTIDAD'] = $cantidad;
        $presentacion=new presentacion();
        $detalle_presentacion=$presentacion->listarXid($detalle->UNIDAD_ENTRADA);
        $array_salida[$num]['UNIDAD_SALIDA'] = $detalle_presentacion->DESCRIPCION;
        $array_salida[$num]['OPCION']=$opcion;
        $array_salida[$num]['UNIDAD_PAQUETE']=$detalle->UNIDAD_PAQUETE;
        if(($opcion=="lote") || ($opcion=="loteInventario")){
        $lote=new lote();
        $detalle=$lote->listarXid($loteId);    
        $array_salida[$num]['LOTE_SERIE'] = $detalle->LOTE;
        $array_salida[$num]['FECHA_CADUCIDAD'] = $detalle->FECHA_CADUCIDAD;
        }else if(($opcion=="serie") || ($opcion=="serieInventario")){
          $serie=new serie();
        $detalle=$serie->listarXid($serieId);    
        $array_salida[$num]['LOTE_SERIE'] = $detalle->SERIE;
         $array_salida[$num]['FECHA_CADUCIDAD'] = "";
        
        }  else {
         $array_salida[$num]['LOTE_SERIE'] = ""; 
         $array_salida[$num]['FECHA_CADUCIDAD'] = "";
        }
        if($numInventario!=""){
            $Inventario=new numeroInventario();
          $detalle=$Inventario->listarXid($numInventario);    
        $array_salida[$num]['NUMERO_INVENTARIO'] = $detalle->NUMERO_INVENTARIO;
        $array_salida[$num]['NUMERO_INVENTARIO_ID'] = $numInventario; 
          
        }else{
           $array_salida[$num]['NUMERO_INVENTARIO'] =""; 
           $array_salida[$num]['NUMERO_INVENTARIO_ID']="";
        }
        
       $array_salida[$num]['LOTE_ID'] = $loteId; 
       $array_salida[$num]['SERIE_ID'] = $serieId; 
        $array_salida[$num]['ALMACEN_ID']= $almacenId;
          $almacen=new almacen();
        $detalle_almacen=$almacen->listarXid($almacenId);     
        $array_salida[$num]['ALMACEN']= $detalle_almacen->ALMACEN;
        $array_salida[$num]['DESCRIPCION_ALMACEN']= $detalle_almacen->DESCRIPCION;
        
        Session::set('array_salida', $array_salida);
    }

    function guarda_movimiento($idSujeto, $importe, $formaPago, $fechaElaboracion, $referencia, $idCobro, $idRecibo, $conceptoDescuento){}

    function quita_movimiento($linea) {
        $array_salida = Session::get('array_salida');
        unset($array_salida[$linea]);
        $array_salida = array_values($array_salida);
        Session::set('array_salida', $array_salida);
    }

}

?>

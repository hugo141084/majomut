<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class anticipo extends ActiveRecord {

    

    protected function initialize() {
        
    }

    public function listar() {
        return $this->find("estatus='1'");
    }
    public function listarXid($id) {
        return $this->find_by_sql("select * from almacen where id='$id'");
    }
    public function almacenApartados() {
        return $this->find_by_sql("select descripcion from almacen where almacen='100'");
    }
    public function destino($id) {
        return $this->find_all_by_sql("select distinct(al.descripcion) from almacen al inner join detalle_vale dv on dv.almacen_id=al.id where dv.compra_id='$id'");
    }
    public function listarAnticipo() {
        
         $sqlProducto = "SELECT an.id, an.nombre, an.importe, an.importe_aplicado, an.referencia, con.concepto,an.estatus FROM anticipo an left join conceptos con  on an.concepto_id=con.id";
        
        return $this->find_all_by_sql($sqlProducto);
    }
    public function guardarDatos(){
        $ciclocosechaId = Session::get('cicloCosecha');
        $anticipo = new anticipo(Input::post('anticipo'));
        $tipoOperacion="";
       $folios = new series_folios();
           $folios->incrementarConsecutivo('ANTICIPO');
        $datoFolios = $folios->find_first("tipo = 'ANTICIPO'");
        $anticipo->documento="ANT-".str_pad(($datoFolios->consecutivo),4, "0", STR_PAD_LEFT); 
        $referencia=$anticipo->documento;
        $fechaDocumento=$anticipo->fecha_anticipo;
        $fechaMovimiento=$anticipo->fecha_anticipo;
        $proveedorId=$anticipo->cliente_id;
        $anticipo->ciclocosecha_id=$ciclocosechaId; 
        if (($anticipo->bancos_cuentas_id > 0) ) {
            $tipoOperacion = "B";
        }
        $tipo="ANTICIPO";
        $Fanticipo=date("Y-m-d H:i:s");
        $datosCobro = new cobros();
                    $datosCobro = $datosCobro->guardarDatosC($Fanticipo, $anticipo->concepto_id, $anticipo->referencia, $anticipo->fecha_referencia, $anticipo->importe, $anticipo->bancos_cuentas_id, Session::get('id'), $tipoOperacion, $anticipo->fecha_anticipo, $anticipo->uso_id,$anticipo->cliente_id,$tipo);
         $anticipo->cobro_id=$datosCobro;
                    if( $anticipo->save()){
         // echo "<script>  alert ('Registro Insertado....!');</script>";  
        }
                $tipoMovimientoS="S";
        $numeroMovimientoS="24";
        $tipoMovimientoE="E";
        $numeroMovimientoE="25";
             $array_productos = Session::get('array_anticipo');
             
            $longitud = count($array_productos);
            $entAlmacen=new almacen();
              $entAlmacen=$entAlmacen->find_first("descripcion='APARTADOS'");
             $almacenIdE=$entAlmacen->id;
             for ($i = 0; $i < $longitud; $i++) {
              
              $lote=new detalle_lote();
              
              $movimiento=new movimiento_inventario();
              $producto=new producto();
              $medida=new medida();
              $inventario=new inventario();
              $llote=new lote();
            //  $datoInventario=new numeroInventario();
               $productoId=  $array_productos[$i]['PRODUCTO_ID'] ;   
$codigo=$array_productos[$i]['CLAVE'];
$descripcion=$array_productos[$i]['DESCRIPCION'];
$cantidad=$array_productos[$i]['CANTIDAD'];
$precio=$array_productos[$i]['PRECIO'];
$presentacionVenta=$array_productos[$i]['PRESENTACIONV'];
$presentacion=$array_productos[$i]['PRESENTACION'];
$loteSerie=$array_productos[$i]['LOTE_SERIE'];
$datoLote=$llote->find_first($loteSerie);
$cantidadP=$cantidad * (1/$presentacion);
       
              
         $productoId=  $array_productos[$i]['PRODUCTO_ID'] ;     
             
       $cantidad= $array_productos[$i]['CANTIDAD'] ;
       $opcion= "lote";
        $unidadXpaquete=$array_productos[$i]['UNIDAD_PAQUETE'];
        $fechaCaducidad=$array_productos[$i]['FECHA_CADUCIDAD'];
     $almacenId=$array_productos[$i]['ALMACEN_ID'];         
         
         $num_inventario=$array_productos[$i]['NUMERO_INVENTARIO'];
       
        //salida
              if(($opcion=="lote") || ($opcion=="loteInventario")){
              $lote->valida_entrada($loteSerie,$fechaCaducidad,$productoId,$almacenId,$cantidad,$unidadXpaquete,$tipoMovimientoS);    
              }else if (($opcion=="serie") || ($opcion=="serieInventario")){
              $serie->valida_entrada($loteSerie,$productoId,$almacenId,$cantidad,$unidadXpaquete,$tipoMovimientoS);    
              }
              if($num_inventario!=""){
              $datoInventario -> valida_entrada($productoId,$almacenId,$loteSerie,$num_inventario,$cantidad,$unidadXpaquete,$tipoMovimientoS);   
              }
              $inventario->actualizaInventario($productoId,$cantidad,$tipoMovimientoS,$unidadXpaquete,$almacenId);
              $producto->actualizaProducto($productoId,$cantidad,$tipoMovimientoS,$unidadXpaquete);
              $movimiento->validaMovimiento($productoId,$fechaDocumento,$referencia,$fechaMovimiento,$proveedorId, $cantidad, $unidadXpaquete, $almacenId, $loteSerie,$opcion,$numeroMovimientoS,$num_inventario,$precio,$presentacionVenta);
         //entrada  
              
              
              if(($opcion=="lote") || ($opcion=="loteInventario")){
              $lote->valida_entrada($loteSerie,$fechaCaducidad,$productoId,$almacenIdE,$cantidad,$unidadXpaquete,$tipoMovimientoE);    
              }else if (($opcion=="serie") || ($opcion=="serieInventario")){
              $serie->valida_entrada($loteSerie,$productoId,$almacenIdE,$cantidad,$unidadXpaquete,$tipoMovimientoE);    
              }
              if($num_inventario!=""){
              $datoInventario -> valida_entrada($productoId,$almacenIdE,$loteSerie,$num_inventario,$cantidad,$unidadXpaquete,$tipoMovimientoE);   
              }
              $inventario->valida_inventario($almacenIdE, $productoId);
              $inventario->actualizaInventario($productoId,$cantidad,$tipoMovimientoE,$unidadXpaquete,$almacenIdE);
              $producto->actualizaProducto($productoId,$cantidad,$tipoMovimientoE,$unidadXpaquete);
              $movimiento->validaMovimiento($productoId,$fechaDocumento,$referencia,$fechaMovimiento,$proveedorId, $cantidad, $unidadXpaquete, $almacenIdE, $loteSerie,$opcion,$numeroMovimientoE,$num_inventario,$precio,$presentacionVenta);
              
             }
        
        session::delete('array_anticipo');
        
    }
    public function actualizarDatos(){
        $almacen = new almacen(Input::post('almacen'));
        
        if( $almacen->update()){
          //echo "<script>  alert ('Registro Insertado....!');</script>";  
        }
    }
    public function buscarAlmacen($condicion) {
            return $this->find_all_by_sql("SELECT distinct(alm.id), alm.descripcion
FROM almacen as alm, inventario as inv
WHERE inv.almacen_id = alm.id
AND $condicion order by alm.descripcion asc");
        }
        
       
        
        
}   
?>
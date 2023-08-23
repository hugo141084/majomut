<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class movimiento_inventario extends ActiveRecord {

    

    protected function initialize() {
        
    }

    public function listar() {
        return $this->find();
    }
    public function listarXmovimiento($idProducto,$idMovimiento) {
      
        return $this->find_by_sql("select sum(cantidad)  as cantidad from movimiento_inventario where tipo_movimiento=$idMovimiento and producto_id=$idProducto");
    
    }
     public function buscaXmovimiento($condicion) {
      $usuarioId=Session::get('id');
        @$dato->producto_id="";
       
        if(($dato->producto_id=="") || ($dato->producto_id=="0") ){
        return $this->find_all_by_sql("SELECT DISTINCT movi.producto_id, inv.descripcion, alma.almacen, inv.clave, alma.descripcion as descripcion_almacen, alma.id as id_almacen
FROM movimiento_inventario AS movi, producto AS inv, almacen  AS alma 
WHERE movi.producto_id = inv.id and alma.id=movi.almacen_id  $condicion
ORDER BY  alma.almacen ASC");
            
        }else{
            return $this->find_all_by_sql("SELECT DISTINCT movi.producto_id, inv.descripcion, alma.almacen, inv.clave, alma.descripcion as descripcion_almacen, alma.id as id_almacen
FROM movimiento_inventario AS movi, producto AS inv, almacen  AS alma 
WHERE movi.producto_id = inv.id and alma.id=movi.almacen_id and inv.producto_id=$dato->producto_id  $condicion
ORDER BY  alma.almacen ASC");

        }
        
    
    }
    
    public function detalleMovimiento($clave_articulo, $almacen,$fecha_inicio, $fecha_fin,$condicion,$loteB ) {
    $lote="";  if(trim($loteB !='0')){ $lote=" and movi.lote_id= '".$loteB."'"; }
        return $this->find_all_by_sql("select movi.existencia,movi.fecha_documento,movi.referencia,movi.cantidad,movi.costo,movi.tipo_movimiento,L.codigo lote from movimiento_inventario as movi inner join lote L ON movi.lote_id=L.id where movi.almacen_id='$almacen' and movi.producto_id='$clave_articulo'  and (fecha_documento >= '$fecha_inicio' && fecha_documento <='$fecha_fin') $condicion $lote order by movi.id asc ");
    
    }
    
    public function guardarDatos(){
        $puesto = new puesto(Input::post('puesto'));       
        $puesto->USUARIO_ID=Session::get('id'); 
       
        if( $puesto->save()){
          echo "<script>  jAlert ('Registro Insertado....!','AVISO');</script>";  
        }
    }
    
    public function validaMovimiento($productoId,$fechaDocumento,$referencia,$fechaMovimiento,$proveedorId, $cantidad, $unidadXpaquete, $almacenId, $loteSerie,$opcion,$tipoMovimiento,$numInventario=NULL,$precio=null,$presentacionVenta=NULL){
       $ciclocosechaId = Session::get('cicloCosecha');
        $fechaDocumento=strftime("%Y-%m-%d", strtotime($fechaDocumento));
        $fechaMovimiento=strftime("%Y-%m-%d", strtotime($fechaMovimiento));
       
        $cantidad=$cantidad;
        if($opcion=="lote"){
         $lote=new detalle_lote();
         $lote=$lote->existenciaLote($loteSerie,$productoId,$almacenId);
         $loteSerieExistencia=$lote->suma;
        }else if($opcion=="serie"){
         $serie=new serie();
         $serie=$serie->existenciaSerie($loteSerie,$productoId,$almacenId);
         $loteSerieExistencia=$serie->suma;   
        }else{
         $loteSerieExistencia=0;   
        }
        $producto=new inventario();
        $productoExistencia=$producto->existenciaProducto($productoId,$almacenId);
        $movimiento=new movimiento_inventario();
        $movimiento->producto_id=$productoId;
        $movimiento->tipo_movimiento=$tipoMovimiento;
        $movimiento->fecha_documento=$fechaDocumento;        
        $movimiento->referencia=$referencia;
        $movimiento->fecha_movimiento=$fechaMovimiento;
        $movimiento->empleado_proveedor=$proveedorId;
        $movimiento->referencia=$referencia;
        $movimiento->cantidad=$cantidad;
        $movimiento->almacen_id=$almacenId;
        $movimiento->lote_id=$loteSerie;
        $movimiento->existencia=$productoExistencia->suma;
        $movimiento->lote_serie_inventario=$loteSerieExistencia; 
        $movimiento->precio=$precio;
        $movimiento->presentacion_venta=$presentacionVenta;
        $movimiento->ciclocosecha_id=$ciclocosechaId; 
        $movimiento->usuario_id=Session::get('id'); 
        $movimiento->save();
    }
    
    public function validaExiste($loteSerie,$fechaCaducidad,$productoId,$almacenId){
    $fechaCaducidad=strftime("%Y-%m-%d", strtotime($fechaCaducidad));
                   return $this->exists("LOTE='$loteSerie' and  FECHA_CADUCIDAD='$fechaCaducidad' and PRODUCTO_ID=$productoId, and ALMACEN_ID=$almacenId");                 
    }
    public function registrarLote($loteSerie,$productoId,$almacenId,$cantidad,$unidadXpaquete,$tipoMovimiento){
        $guardar=new lote();
        $guardar->lote_id=$loteSerie;
       
        $guardar->producto_id=$productoId;
        $guardar->almacen_id=$almacenId;
        $guardar->existencia=$cantidad;
        $guardar->usuario_id=Session::get('id'); 
        $guardar->save();
        
    }
    
    public function obtenerIdLote($loteSerie, $fechaCaducidad,$productoId, $almacenId){
        $fechaCaducidad=strftime("%Y-%m-%d", strtotime($fechaCaducidad));
                   return $this->find_first("conditions: LOTE='$loteSerie' and  FECHA_CADUCIDAD='$fechaCaducidad' and PRODUCTO=$productoId and ALMACEN_ID=$almacenId","order: id desc");
               }
   public function actualizaLote($idLote,$cantidad,$tipoMovimiento,$unidadXpaquete){
     
       if($tipoMovimiento =="E"){
          $cantidad=$cantidad;
      }else if($tipoMovimiento=="S"){
          $cantidad=$cantidad * (-1);
      }
                  $this->update_all("existencia = exitencia + $cantidad", "ID= $idLote");
               }
               
               public function listarVentaVale($documento) {
         $sqlProducto = "SELECT pro.clave, pro.descripcion,pro.peso, pro.peso_neto,pro.precio, alm.almacen, alm.descripcion descripcionAl, pr.descripcion preparacion, pre.descripcion presentacion, lo.codigo, pro.unidad_empaque, pro.peso_empaque, me.descripcion medida,c.descripcion calidad, mi.cantidad FROM producto pro left join preparacion pr on pr.id=pro.preparacion_id left join presentacion pre on pre.id=pro.presentacion_id left join calidad c on pro.calidad_id=c.id left join medida me on pro.medida_id=me.id inner join movimiento_inventario mi on mi.producto_id=pro.id INNER join lote lo on mi.lote_id=lo.id inner join almacen alm on mi.almacen_id=alm.id where mi.referencia='$documento'";
        return $this->find_all_by_sql($sqlProducto);
    }
    
    public function cancelarMovimiento($documento,$movimiento){
        $registro= $this->find("referencia='$documento'");
        $tipoMovimiento=$movimiento;
        $unidadXpaquete=1;
        foreach ($registro as $datos){
            
           
            $lote=new detalle_lote();
            $inventario= new inventario();
            $producto=new producto();
            $resultado=$lote->actualizaLote($datos->lote_id,$datos->cantidad,$tipoMovimiento,$unidadXpaquete);
             $inventario->actualizaInventario($datos->producto_id,$datos->cantidad,$tipoMovimiento,$unidadXpaquete,$datos->almacen_id);
              $producto->actualizaProducto($datos->producto_id,$datos->cantidad,$tipoMovimiento,$unidadXpaquete); 
              $producto=new inventario();
                $productoExistencia=$producto->existenciaProducto($datos->producto_id,$datos->almacen_id);
            $linea=$this->find_first($datos->id);
            $linea->id='';
            $linea->tipo_movimiento=$tipoMovimiento;
            $linea->existencia=$productoExistencia->suma;
            $linea->fecha_usuario=date('Y-m-d H:i:s');
            $linea->save();
        }
    }
    
    public function cancelarMovimientoM($documento){
        $registro= $this->find("referencia='$documento'");
        $tipoMovimiento=$movimiento;
        $unidadXpaquete=1;
        foreach ($registro as $datos){
            
           if($datos->tipo_movimiento=='18')$tipoMovimiento='7';
           else if($datos->tipo_movimiento=='7')$tipoMovimiento='18';
            $lote=new detalle_lote();
            $inventario= new inventario();
            $producto=new producto();
            $resultado=$lote->actualizaLote($datos->lote_id,$datos->cantidad,$tipoMovimiento,$unidadXpaquete);
             $inventario->actualizaInventario($datos->producto_id,$datos->cantidad,$tipoMovimiento,$unidadXpaquete,$datos->almacen_id);
              $producto->actualizaProducto($datos->producto_id,$datos->cantidad,$tipoMovimiento,$unidadXpaquete); 
              $producto=new inventario();
                $productoExistencia=$producto->existenciaProducto($datos->producto_id,$datos->almacen_id);
            $linea=$this->find_first($datos->id);
            $linea->id='';
            $linea->tipo_movimiento=$tipoMovimiento;
            $linea->existencia=$productoExistencia->suma;
            $linea->fecha_usuario=date('Y-m-d H:i:s');
            $linea->save();
        }
    }
}   
?>
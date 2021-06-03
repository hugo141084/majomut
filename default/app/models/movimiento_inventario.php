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
        $dato->producto_id=="";
        echo $condicion;
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
    
    public function detalleMovimiento($clave_articulo, $almacen,$fecha_inicio, $fecha_fin) {
      
        return $this->find_all_by_sql("select movi.existencia,movi.fecha_documento,movi.referencia,movi.cantidad,movi.costo,movi.referencia,movi.tipo_movimiento from movimiento_inventario as movi where movi.almacen_id='$almacen' and movi.producto_id='$clave_articulo'  and (fecha_documento >= '$fecha_inicio' && fecha_documento <='$fecha_fin')  order by movi.id ");
    
    }
    
    public function guardarDatos(){
        $puesto = new puesto(Input::post('puesto'));       
        $puesto->USUARIO_ID=Session::get('id'); 
       
        if( $puesto->save()){
          echo "<script>  jAlert ('Registro Insertado....!','AVISO');</script>";  
        }
    }
    
    public function validaMovimiento($productoId,$fechaDocumento,$referencia,$fechaMovimiento,$proveedorId, $cantidad, $unidadXpaquete, $almacenId, $loteSerie,$opcion,$tipoMovimiento,$numInventario=NULL,$precio=null){
        $fechaDocumento=strftime("%Y-%m-%d", strtotime($fechaDocumento));
        $fechaMovimiento=strftime("%Y-%m-%d", strtotime($fechaMovimiento));
        if($unidadXpaquete==0){$unidadXpaquete=1;}
        $cantidad=$cantidad * $unidadXpaquete;
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
     $cantidad=$cantidad *$unidadXpaquete;
       if($tipoMovimiento =="E"){
          $cantidad=$cantidad;
      }else if($tipoMovimiento=="S"){
          $cantidad=$cantidad * (-1);
      }
                  $this->update_all("existencia = exitencia + $cantidad", "ID= $idLote");
               }            
}   
?>
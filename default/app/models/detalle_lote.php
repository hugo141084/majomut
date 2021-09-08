<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class detalle_lote extends ActiveRecord{

   

    public function listar() {
        return $this->find();
    }
     public function listarXid($id) {
        return $this->find_by_sql("select * from lote where id=$id");
    }
    public function guardarDatos(){
        $lote = new detalle_lote(Input::post('lote'));       
        
        if( $lote->save()){
          echo "<script>  alert ('Registro Insertado....!');</script>";  
        }
    }
    
    public function valida_entrada($loteSerie,$fechaCaducidad,$productoId,$almacenId,$cantidad,$unidadXpaquete,$tipoMovimiento){
        
        $lote = new detalle_lote();
        $existe=$lote->valida_existe($loteSerie,$fechaCaducidad,$productoId,$almacenId);
        if ($existe > 0) {
            $lote->obtenerIdLote($loteSerie, $fechaCaducidad, $productoId,$almacenId);
            $resultado=$lote->actualizaLote($lote->id,$cantidad,$tipoMovimiento,$unidadXpaquete);
        }else{
            $lote->registrarLote($loteSerie, $productoId,$almacenId, $cantidad, $unidadXpaquete, $tipoMovimiento);
        }
        
    }
    public function validaSalida($loteId,$cantidad,$unidadXpaquete,$tipoMovimiento){
        
        $lote = new detalle_lote();
        
            $resultado=$lote->actualizaLote($loteId,$cantidad,$tipoMovimiento,$unidadXpaquete);
        
        
    }
    
   public function valida_existe($loteSerie,$fechaCaducidad,$productoId,$almacenId){
    $fechaCaducidad=strftime("%Y-%m-%d", strtotime($fechaCaducidad));
                   return $this->exists("lote_id='$loteSerie' and producto_id='$productoId' and almacen_id='$almacenId'  ");                 
    }
    public function existenciaLote($lote,$productoId,$almacenId){
    
                   return $this->find_by_sql("select sum(existencia) as suma from detalle_lote where lote_id='$lote' and producto_id=$productoId and almacen_id=$almacenId ");                 
    }
    public function buscarXconsulta($condicion){
    
                   return $this->find_all_by_sql("select * from detalle_lote where $condicion ");                 
    }
    public function registrarLote($loteSerie,$productoId,$almacenId,$cantidad,$unidadXpaquete,$tipoMovimiento){
        $guardar=new detalle_lote();
        $guardar->lote_id=$loteSerie;
        $guardar->producto_id=$productoId;
        $guardar->almacen_id=$almacenId;
        $guardar->existencia=$cantidad ;
        $guardar->usuario_id=Session::get('id'); 
        $guardar->save();
        
    }
    
    public function obtenerIdLote($loteSerie, $fechaCaducidad,$productoId, $almacenId){
        
                   return $this->find_first("conditions: lote_id='$loteSerie'  and producto_id=$productoId and almacen_id=$almacenId","order: id desc");
               }
    public function actualizaLote($idLote,$cantidad,$tipoMovimiento,$unidadXpaquete){
     $cantidad=$cantidad *$unidadXpaquete;
       if($tipoMovimiento =="E"){
          $cantidad=$cantidad;
      }else if($tipoMovimiento=="S"){
          $cantidad=$cantidad * (-1);
      }
                  $this->update_all("existencia = existencia + $cantidad", "id= $idLote");
               }
               
               public function buscarXproducto($productoId){
                  
                   return $this->find_all_by_sql("select * from lote where PRODUCTO_ID=$productoId  ");                 
    
               }
                public function buscarLotePA($condicion){
                  
                   return $this->find_all_by_sql("select lo.id,lo.codigo from lote lo inner join detalle_lote dl on dl.lote_id=lo.id where $condicion ");                 
    
               }
}   
?>
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class lote extends ActiveRecord {

    

    protected function initialize() {
        
    }

    public function listar() {
        return $this->find("estatus='1'");
    }
     public function listarXid($id) {
        return $this->find_by_sql("select * from lote where id=$id");
    }
    public function ciclo($id) {
        return $this->find_all_by_sql("select distinct(lo.ciclo) from lote lo inner join detalle_vale dv on dv.lote_id=lo.id where dv.compra_id='$id'");
    }
    public function guardarDatos(){
        $lote = new lote(Input::post('lote'));       
        
        if( $lote->save()){
        //  echo "<script>  alert ('Registro Insertado....!');</script>";  
        }
    }
     public function actualizarDatos(){
        $lote = new lote(Input::post('lote'));       
        
        if( $lote->update()){
          //echo "<script>  alert ('Registro Insertado....!');</script>";  
        }
    }
    
    public function valida_entrada($loteSerie,$fechaCaducidad,$productoId,$almacenId,$cantidad,$unidadXpaquete,$tipoMovimiento){
        
        $lote = new lote_detalle();
        $existe=$lote->valida_existe($loteSerie,$productoId,$almacenId);
        if ($existe > 0) {
            $lote->obtenerIdLote($loteSerie, $productoId,$almacenId);
            $resultado=$lote->actualizaLote($lote->id,$cantidad,$tipoMovimiento,$unidadXpaquete);
        }else{
            $lote->registrarLote($loteSerie, $fechaCaducidad, $productoId,$almacenId, $cantidad, $unidadXpaquete, $tipoMovimiento);
        }
        
    }
    public function validaSalida($loteId,$cantidad,$unidadXpaquete,$tipoMovimiento){
        
        $lote = new lote();
        
            $resultado=$lote->actualizaLote($loteId,$cantidad,$tipoMovimiento,$unidadXpaquete);
        
        
    }
    
    public function valida_existe($loteSerie,$fechaCaducidad,$productoId,$almacenId){
    $fechaCaducidad=strftime("%Y-%m-%d", strtotime($fechaCaducidad));
                   return $this->exists("lote_id='$loteSerie' and producto_id='$productoId' and almacen_id='$almacenId'  ");                 
    }
    public function existenciaLote($lote,$productoId,$almacenId){
    
                   return $this->find_by_sql("select sum(existencia) as suma from lote where LOTE='$lote' and PRODUCTO_ID=$productoId and ALMACEN_ID=$almacenId ");                 
    }
    public function buscarXconsulta($condicion){
    
                   return $this->find_all_by_sql("select * from lote where $condicion ");                 
    }
    public function registrarLote($loteSerie,$fechaCaducidad,$productoId,$almacenId,$cantidad,$unidadXpaquete,$tipoMovimiento){
        $guardar=new lote();
        $guardar->lote_id=$loteSerie;
        
        $guardar->producto_id=$productoId;
        $guardar->almacen_id=$almacenId;
        $guardar->existencia=$cantidad * $unidadXpaquete;
        $guardar->usuario_id=Session::get('id'); 
        $guardar->save();
        
    }
    
    public function obtenerIdLote($loteSerie,$productoId, $almacenId){
        
                   return $this->find_first("conditions: lote_id='$loteSerie'  and producto_id=$productoId and almacen_id=$almacenId","order: id desc");
               }
   public function actualizaLote($idLote,$cantidad,$tipoMovimiento,$unidadXpaquete){
     $cantidad=$cantidad *$unidadXpaquete;
       if($tipoMovimiento =="E"){
          $cantidad=$cantidad;
      }else if($tipoMovimiento=="S"){
          $cantidad=$cantidad * (-1);
      }
                  $this->update_all("existencia = existencia + $cantidad", "ID= $idLote");
               }
               
               public function buscarXproducto($productoId){
                  
                   return $this->find_all_by_sql("select * from detalle_lote where producto_id=$productoId  ");                 
    
               }
               public function buscarLotes($productoId){
                  
                   return $this->find_all_by_sql("select l.codigo, dl.existencia, dl.almacen_id from detalle_lote dl inner join lote l on dl.lote_id=l.id where dl.producto_id=$productoId  ");                 
    
               }
}   
?>
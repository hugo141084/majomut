<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class serie extends ActiveRecord {

    

    protected function initialize() {
        
    }

    public function listar() {
        return $this->find();
    }
     public function listarXid($id) {
        return $this->find_by_sql("select * from serie where ID=$id");
    }
    public function guardarDatos(){
        $puesto = new puesto(Input::post('puesto'));       
        $puesto->USUARIO_ID=Session::get('id'); 
       
        if( $puesto->save()){
          echo "<script>  jAlert ('Registro Insertado....!','AVISO');</script>";  
        }
    }
    
    public function validaEntrada($loteSerie,$productoId,$almacenId,$cantidad,$unidadXpaquete,$tipoMovimiento){
        
        $serie = new serie();
        $existe=$serie->validaExiste($loteSerie,$productoId,$almacenId);
        if ($existe > 0) {
            $serie->obtenerIdSerie($loteSerie,$productoId, $almacenId);
            $resultado=$serie->actualizaSerie($serie->ID,$cantidad,$tipoMovimiento,$unidadXpaquete);
        }else{
            $serie->registrarSerie($loteSerie,$productoId, $almacenId, $cantidad, $unidadXpaquete, $tipoMovimiento);
        }
        
    }
    public function validaSalida($serieId,$cantidad,$unidadXpaquete,$tipoMovimiento){
        
        $serie = new serie();
        
            $resultado=$serie->actualizaSerie($serieId,$cantidad,$tipoMovimiento,$unidadXpaquete);
        
        
    }
    public function validaExiste($loteSerie,$productoId,$almacenId){
                      return $this->exists("SERIE='$loteSerie' and PRODUCTO_ID=$productoId and ALMACEN_ID=$almacenId");                 
    }
    public function existenciaSerie($serie,$productoId,$almacenId){    
                   return $this->find_by_sql("select sum(EXISTENCIA) as suma from serie where SERIE='$serie' and PRODUCTO_ID=$productoId and ALMACEN_ID=$almacenId ");                 
    }
    public function buscarXconsulta($condicion){
    
                   return $this->find_all_by_sql("select * from serie where $condicion ");                 
    }
    public function registrarSerie($loteSerie,$productoId,$almacenId,$cantidad,$unidadXpaquete,$tipoMovimiento){
        $guardar=new serie();
        $guardar->SERIE=$loteSerie;
        $guardar->PRODUCTO_ID=$productoId;
        $guardar->ALMACEN_ID=$almacenId;
        $guardar->EXISTENCIA=$cantidad * $unidadXpaquete;
        $guardar->USUARIO_ID=Session::get('id'); 
        $guardar->save();
        
    }
    
    public function obtenerIdSerie($loteSerie,$productoId, $almacenId){
        
                   return $this->find_first("conditions: SERIE='$loteSerie' and PRODUCTO_ID=$productoId  and ALMACEN_ID=$almacenId","order: id desc");
               }
   public function actualizaSerie($idSerie,$cantidad,$tipoMovimiento,$unidadXpaquete){
     $cantidad=$cantidad *$unidadXpaquete;
       if($tipoMovimiento =="E"){
          $cantidad=$cantidad;
      }else if($tipoMovimiento=="S"){
          $cantidad=$cantidad * (-1);
      }
                  $this->update_all("EXISTENCIA = EXISTENCIA + $cantidad", "ID= $idSerie");
               } 
    public function buscarXproducto($productoId){
                  
                   return $this->find_all_by_sql("select * from serie where PRODUCTO_ID=$productoId  ");                 
    
               }
}   
?>
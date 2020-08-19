<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class inventario extends ActiveRecord {

    

    protected function initialize() {
        
    }

    public function listar() {
        return $this->find();
    }
    
    public function guardarDatos($producto,$almacen,$minima,$maxima){
        $inventario = new inventario();
        $inventario->almacen_id=$almacen; 
        $inventario->producto_id=$producto; 
        $inventario->estado="1";
        $inventario->existencia="0";
        $inventario->cantidad_minima=$minima; 
        $inventario->cantidad_maxima=$maxima;        
        $inventario->usuario_id=Session::get('id'); 
       
        if( $inventario->save()){
          echo "<script>  alert ('Registro Insertado....!');</script>";  
        }
    }
     public function quitarProducto($id){
        return $this->find_by_sql("delete from inventario where id=$id");
       
       
    }
    public function existenciaProducto($productoId,$almacenId){    
                   return $this->find_by_sql("select sum(existencia) as suma from inventario where producto_id=$productoId and almacen_id=$almacenId ");                 
    }
     public function buscarXproducto($productoId){    
                   return $this->find_all_by_sql("select * from inventario where producto_id=$productoId  ");                 
    }
    public function actualizaInventario($productoId,$cantidad,$tipoMovimiento,$unidadXpaquete,$almacenId){
     $cantidad=$cantidad *$unidadXpaquete;
       if($tipoMovimiento =="E"){
          $cantidad=$cantidad;
      }else if($tipoMovimiento=="S"){
          $cantidad=$cantidad * (-1);
      }
                  $this->update_all("existencia = existencia + $cantidad", "producto_id=$productoId and almacen_id=$almacenId");
               } 
}   
?>
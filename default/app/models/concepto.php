<?php

class concepto extends ActiveRecord {
    
 public function listar() {
        return $this->find();
    }
      public function listarXid($id) {
        return $this->find_by_sql("select * from concepto_movimiento where id=$id");
    } 
    public function listarM() {
        return $this->find_all_by_sql("select concat_ws(' -- ',descripcion,tipo_movimiento) descripcion, id from concepto_movimiento where estatus='1'");
    }
}   
?>

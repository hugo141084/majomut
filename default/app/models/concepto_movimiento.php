<?php

class concepto_movimiento extends ActiveRecord {
    
 public function listar() {
        return $this->find();
    }
      public function listarXid($id) {
        return $this->find_by_sql("select * from concepto_movimiento where id=$id");
    } 
    
}   
?>

<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class conceptoMovimiento extends ActiveRecord {

    

    protected function initialize() {
        
    }

    public function listar() {
        return $this->find();
    }
      public function listarXid($id) {
        return $this->find_by_sql("select * from concepto_movimiento where id=$id");
    }      
}   
?>
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class conceptos extends ActiveRecord {

    protected function initialize() {
        
    }

    public function listar() {
        return $this->find("estatus='1'");
    }
      public function datosConcepto($id){
                    return $this->find_by_sql("select * from conceptos where id=$id");
                    
                }   
}   
?>
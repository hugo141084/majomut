<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class cupon extends ActiveRecord {

    

    protected function initialize() {
        
    }

    public function listar() {
         
        return $this->find();
        
        
    }
     
}   
?>
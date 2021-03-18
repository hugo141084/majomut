<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class embalaje extends ActiveRecord {

    

    protected function initialize() {
        
    }

    public function listar() {
         
        return $this->find("estatus='1'");
        
        
    }
     public function listarXid($id) {
        return $this->find_by_sql("select * from clasificacion_producto where ID=$id");
    }
    public function guardarDatos(){
        $embalaje = new embalaje(Input::post('embalaje'));
        
        if( $embalaje->save()){
        //  echo "<script>  alert ('Registro Insertado....!');</script>";  
        }
    }
    public function actualizarDatos(){
        $embalaje = new embalaje(Input::post('embalaje'));
        
        if( $embalaje->update()){
        //  echo "<script>  alert ('Registro Insertado....!');</script>";  
        }
    }
}   
?>
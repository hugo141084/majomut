<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class calidad extends ActiveRecord {

    

    protected function initialize() {
        
    }

    public function listar() {
         
        return $this->find("estatus='1'");
        
        
    }
     public function listarXid($id) {
        return $this->find_by_sql("select * from clasificacion_producto where ID=$id");
    }
    public function guardarDatos(){
        $cuenta = new calidad(Input::post('calidad'));
         
       
        if( $cuenta->save()){
          
        }
    }
    public function actualizarDatos(){
        $cuenta = new calidad(Input::post('calidad'));
         
       
        if( $cuenta->update()){
         
        }
    }
}   
?>
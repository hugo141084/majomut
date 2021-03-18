<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class presentacion extends ActiveRecord {

    

    protected function initialize() {
        
    }

    public function listar() {
        return $this->find("estatus='1'");
    }
     public function listarXid($id) {
        return $this->find_by_sql("select * from presentacion where id=$id");
    }
    public function guardarDatos(){
        $presentacion = new presentacion(Input::post('presentacion'));       
         
       
        if( $presentacion->save()){
         // echo "<script>  alert ('Registro Insertado....!');</script>";  
        }
    }
    public function actualizarDatos(){
        $presentacion = new presentacion(Input::post('presentacion'));       
         
       
        if( $presentacion->update()){
         // echo "<script>  alert ('Registro Insertado....!');</script>";  
        }
    }
}   
?>
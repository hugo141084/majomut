<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class medida extends ActiveRecord {

    

    protected function initialize() {
        
    }

    public function listar() {
         
        return $this->find("estatus='1'");
        
        
    }
     public function listarXid($id) {
        return $this->find_by_sql("select * from medida where id=$id");
    }
    public function guardarDatos(){
        $cuenta = new medida(Input::post('medida'));
         
       
        if( $cuenta->save()){
         // echo "<script>  alert ('Registro Insertado....!');</script>";  
        }
    }
     public function actualizarDatos(){
        $cuenta = new medida(Input::post('medida'));
         
       
        if( $cuenta->update()){
        //  echo "<script>  alert ('Registro Insertado....!');</script>";  
        }
    }
}   
?>
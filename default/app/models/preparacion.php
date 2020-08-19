<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class preparacion extends ActiveRecord {

    

    protected function initialize() {
        
    }

    public function listar() {
         
        return $this->find();
        
        
    }
     public function listarXid($id) {
        return $this->find_by_sql("select * from clasificacion_producto where ID=$id");
    }
    public function guardarDatos(){
        $cuenta = new preparacion(Input::post('preparacion'));
        
       
        if( $cuenta->save()){
          echo "<script>  jAlert ('Registro Insertado....!','AVISO');</script>";  
        }
    }
}   
?>
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class proveedor extends ActiveRecord {

    

    protected function initialize() {
        
    }

    public function listar() {
        return $this->find();
    }
    public function listarXid($id) {
        return $this->find_by_sql("select * from proveedor where id='$id'");
    }
    public function guardarDatos(){
        $proveedor = new proveedor(Input::post('proveedor'));
        $proveedor->ESTADO="Activo";
        $proveedor->USUARIO_ID=Session::get('id'); 
        
       
        if( $proveedor->save()){
          echo "<script>  alert ('Registro Insertado....!');</script>"; 
          Redirect::to('proveedor/crear');
        }
    }
    
    public function buscarProveedor($proveedorId){
        return $this->find_by_sql("SELECT *
FROM proveedor
WHERE id = $proveedorId");
        
    }
}   
?>
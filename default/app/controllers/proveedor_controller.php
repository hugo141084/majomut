<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
Load::model('proveedor'); 
class proveedorController extends AppController{
    
    /**
     * Muestra el partial de las nominas generadas
     */
    public function index() {
        $proveedor = new proveedor();
        $this->result = $proveedor->listar();
        $this->campos = array(
            utf8_encode('#') => 'id',
            utf8_encode('CLAVE') => 'clave',
            utf8_encode('RAZON SOCIAL') => 'nombre',
            utf8_encode('R.F.C.') => 'rfc',
            utf8_encode('CORREO ELECTRONICO') => 'email',
            utf8_encode('TELEFONO') => 'telefono',
            utf8_encode('ESTADO') => 'estado'
        );
        $this->encabezado= "PROVEEDORES";
    }
    
    

        public function crear(){
         $this->accion="crear";
        
        $proveedor= new proveedor();
        $this->proveedor = $proveedor->find();

        if (Input::hasPost('proveedor')) {

            $this->proveedor = $proveedor->guardarDatos();
        }
    }
    
    public function editar($id = null) {
        $this->accion = 'editar';
        $proveedor = new proveedor();
        if ($id != null) {
            $this->proveedor = $proveedor->find($id);
        } else if (Input::hasPost('proveedor')) {
             $proveedor->usuario_id=Session::get('id');
            if ($proveedor->update(Input::post('proveedor'))) {
                
               
                Redirect::to('proveedor');
                Flash::valid('Registro Actualizado');
                Input::delete();
            } else {
                $this->proveedor = Input::post('proveedor');
                Flash::error('Falló Operación');
            }
        }
        view::select('crear');
    }
}
?>

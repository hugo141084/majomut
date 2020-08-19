<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class enviosController extends AppController{
    
    /**
     * Muestra el partial de las nominas generadas
     */
    public function index() {
         $envios = new costoEnvio();
        if (Input::hasPost('costo')) {

            $this->envios = $envios->guardarCosto();
            
        }
        
    
        $this->result = $envios->datosCosto();
        $this->campos = array(
            utf8_encode('#') => 'id',
            utf8_encode('CODIGO') => 'codigo',
            utf8_encode('KILOS') => 'num_kilos',
            utf8_encode('COSTO') => 'costo'
            
        );
        $this->encabezado= "";
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

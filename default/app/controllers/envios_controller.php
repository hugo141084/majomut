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
            utf8_encode('Empresa') => 'empresa',
            utf8_encode('Codigo') => 'codigo',
            utf8_encode('Kilo(s)') => 'kilos',
            utf8_encode('Costo') => 'costo'
            
        );
        $this->encabezado= "";
    }
    
    public function editarEnvio($id){
        
     
        $costo = new costoEnvio();
        $this->costo = $costo->find_first($id);

        if (Input::hasPost('costo')) {

            $this->costo = $costo->actualizarDatos();
            Redirect::to('envios/index');
        }
       
    }
     public function eliminarEnvio($id){
        $costo = new costoEnvio();
        $costo = $costo->find_first($id);
        $costo->estatus='0';
        $costo->update();
        Redirect::to('envios/index');
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
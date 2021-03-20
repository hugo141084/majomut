<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class empresaController extends AppController{
    
    /**
     * Muestra el partial de las nominas generadas
     */
    public function index() {
         $empresa = new empresa();
        if (Input::hasPost('empresa')) {

            $this->empresa = $empresa->guardarDatos();
            
        }
        
    
        $this->result = $empresa->datosEmpresa();
        $this->campos = array(
            utf8_encode('NOMBRE') => 'nombre',
            utf8_encode('DIRECCION') => 'direccion',
            utf8_encode('TELEFONO') => 'telefono',
            utf8_encode('DATOS PARA PAGO') => 'datosPago',
           
            
        );
        $this->encabezado= "";
    }
    public function editarEmpresa($id){
        
     
        $empresa = new empresa();
        $this->empresa = $empresa->find_first($id);

        if (Input::hasPost('empresa')) {

            $this->empresa = $empresa->actualizarDatos();
            Redirect::to('empresa/index');
        }
       
    }
     public function eliminarEmpresa($id){
        $empresa = new empresa();
        $empresa = $empresa->find_first($id);
        $empresa->estatus='0';
        $empresa->update();
        Redirect::to('empresa/index');
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

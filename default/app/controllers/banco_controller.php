<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class bancoController extends AppController{
    
    /**
     * Muestra el partial de las nominas generadas
     */
    public function index() {
         $cuentas = new bancosCuentas();
        if (Input::hasPost('cuenta')) {

            $this->cuentas = $cuentas->guardarDatos();
            
        }
        
    
        $this->result = $cuentas->datosCuenta();
        $this->campos = array(
            utf8_encode('#') => 'id',
            utf8_encode('BANCO') => 'nombre_banco',
            utf8_encode('CUENTA') => 'numero_cuenta',
            utf8_encode('FECHA APERTURA') => 'fecha_apertura',
            utf8_encode('SUCURSAL') => 'sucursal'
            
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

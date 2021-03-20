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
    public function editarCuenta($id){
        
     
        $cuenta = new bancosCuentas();
        $this->cuenta = $cuenta->find_first($id);

        if (Input::hasPost('cuenta')) {

            $this->cuenta = $cuenta->actualizarDatos();
            Redirect::to('banco/index');
        }
       
    }
     public function eliminarCuenta($id){
        $cuenta = new bancosCuentas();
        $cuenta = $cuenta->find_first($id);
        $cuenta->estatus='0';
        $cuenta->update();
        Redirect::to('banco/index');
    }
    

        public function crear(){
         $this->accion="crear";
        
        $proveedor= new proveedor();
        $this->proveedor = $proveedor->find();

        if (Input::hasPost('proveedor')) {

            $this->proveedor = $proveedor->guardarDatos();
        }
    }
    
   
}
?>

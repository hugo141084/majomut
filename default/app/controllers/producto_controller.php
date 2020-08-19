<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
Load::model('producto'); 
class productoController extends AppController{
    
    /**
     * Muestra el partial de las nominas generadas
     */
    public function index() {
        $producto = new producto();
        $this->result = $producto->listar();
        $this->campos = array(
            utf8_encode('ID') => 'id',
            utf8_encode('CLAVE') => 'clave',
            utf8_encode('DESCRIPCION') => 'nombre',
            utf8_encode('TIPO') => 'presentacion',
            utf8_encode('PRESENTACION') => 'calidad',
            utf8_encode('PREPARACION') => 'preparacion',
            utf8_encode('PESO BRUTO') => 'peso',
            utf8_encode('EXISTENCIA') => 'existencia'
            
        );
        $this->encabezado= "PRODUCTOS";
    }
    
    public function crear(){
         $this->accion="crear";
        
        $producto = new producto();
        $this->producto = $producto->find();

        if (Input::hasPost('producto')) {

            $this->producto = $producto->guardarDatos();
        }
    }
    
    public function editar($id = null) {
        $this->accion = 'editar';
        $producto = new producto();
        if ($id != null) {
            $this->producto = $producto->find($id);
        } else if (Input::hasPost('producto')) {
            if($producto->NUMERO_SERIE=="S"){
         $producto->lOTE="N";   
        }
         $producto->USUARIO_ID=Session::get('id');
            if ($producto->update(Input::post('producto'))) {
                
               
                Redirect::to('producto');
                Flash::valid('Registro Actualizado');
                Input::delete();
            } else {
                $this->producto = Input::post('producto');
                Flash::error('Falló Operación');
            }
        }
        view::select('crear');
    }
}
?>

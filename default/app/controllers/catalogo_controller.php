<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Load::models('embalaje','lote','preparacion','calidad'); 
class catalogoController extends AppController{
    
    public function listadoLote() {
        $lote = new lote();
        $this->lote = $lote->find();

        if (Input::hasPost('lote')) {

            $this->lote = $lote->guardarDatos();
        }
        $this->result = $lote->listar();
        $this->campos = array(
            utf8_encode('#') => 'id',
            utf8_encode('CICLO') => 'ciclo',
            utf8_encode('NUM. LOTE') => 'num_lote',
            utf8_encode('CODIGO') => 'codigo',
            utf8_encode('CADUCIDAD') => 'fecha_caducidad'
            
        );
        $this->encabezado= "Lotes ";
    }
    public function listadoCalidad() {
        $calidad = new calidad();
        if (Input::hasPost('calidad')) {

            $this->calidad = $calidad->guardarDatos();
        }
        
        $this->result = $calidad->listar();
        $this->campos = array(
            utf8_encode('#') => 'id',
            utf8_encode('CODIGO') => 'codigo',
            utf8_encode('DESCRIPCION') => 'descripcion'
        );
        $this->encabezado= "PRESENTACION";
    }
    public function listadoPreparacion() {
        
        $preparacion = new preparacion();
        
        if (Input::hasPost('preparacion')) {
            $this->preparacion = $preparacion->guardarDatos();
        }
        $this->result = $preparacion->listar();
        $this->campos = array(
            utf8_encode('#') => 'id',
            utf8_encode('DESCRIPCION') => 'descripcion',
            
        );
        $this->encabezado= "Preparacion";
    }
    public function listadoMedida() {
        $medida = new medida();
        $this->medida = $medida->find();

        if (Input::hasPost('medida')) {

            $this->medida = $medida->guardarDatos();
        }
        $this->result = $medida->listar();
        $this->campos = array(
            utf8_encode('#') => 'id',
            utf8_encode('DESCRIPCION') => 'descripcion',
            
        );
        $this->encabezado= "Unidades de Medida";
    }
    public function listadoPresentacion() {
        
        $presentacion = new presentacion();
        $this->preparacion = $presentacion->find();
        if (Input::hasPost('presentacion')) {
            $this->presentacion = $presentacion->guardarDatos();
        }
        $presentacion = new presentacion();
        $this->result = $presentacion->listar();
        $this->campos = array(
            utf8_encode('#') => 'id',
            utf8_encode('DESCRIPCION') => 'descripcion',
            
        );
        $this->encabezado= "";
    }
    public function crear(){
         $this->accion="crear";
    }
    
    
    public function crearLote(){
        View::template(NULL);
        $this->accion="crearLote";
        $clasificacion = new lote();
        $this->lote = $clasificacion->find();

        if (Input::hasPost('lote')) {

            $this->lote = $clasificacion->guardarDatos();
        }
       
    }
    public function editarLote($id = null,$var=null) {
        $this->accion = 'editarLote';
        $clasificacion = new lote();
        if ($id != null) {
            $this->lote = $clasificacion->find($id);
        } else if (Input::hasPost('lote')) {
             
            if ($clasificacion->update(Input::post('lote'))) {               
                $this->actualizo="0";
                Flash::valid('Registro Actualizado');
                Router::redirect('catalogo/lostarLote/'.$clasificacion->id);   
            } else {
                $this->lote = Input::post('lote');
                Flash::error('Falló Operación');
            }
        }
        $this->var=$var;
        view::select('crearClasifProducto');
    }
     public function crearCalidad(){
        View::template(NULL);
        $this->accion="crearCalidad";
        $calidad = new calidad();
        $this->zona = $calidad->find();

        if (Input::hasPost('calidad')) {

            $this->calidad = $calidad->guardarDatos();
        }
       
    }
    public function editarCalidad($id = null,$var=null){
        View::template(NULL);
        View::select('crearCalidad');
        $this->accion="crearCalidad";
        $zona = new calidad();
        $this->zona = $zona->find($id);

        if (Input::hasPost('calidad')) {

            $this->calidad = $calidad->guardarDatos();
        }
       
    }
    
    public function listadoEmbalaje() {
        $embalaje = new embalaje();

        $this->embalaje = $embalaje->find();

        if (Input::hasPost('embalaje')) {

            $this->embalaje = $embalaje->guardarDatos();
        }
        $this->result = $embalaje->listar();
        $this->campos = array(
            utf8_encode('#') => 'id',
            utf8_encode('DESCRIPCION') => 'descripcion',
            
        );
        $this->encabezado= "Empaques";
    }
     public function crearEmbalaje(){
        View::template(NULL);
        $this->accion="crearEmbalaje";
        $embalaje = new embalaje();
        $this->embalaje = $embalaje->find();

        if (Input::hasPost('embalaje')) {

            $this->embalaje = $embalaje->guardarDatos();
        }
     }
     public function crearPreparacion(){
        View::template(NULL);
        $this->accion="crearPreparacion";
        $preparacion = new preparacion();
        $this->preparacion = $preparacion->find();

        if (Input::hasPost('preparacion')) {

            $this->preparacion = $preparacion->guardarDatos();
        }
     }
      public function crearMedida(){
        View::template(NULL);
        $this->accion="crearMedida";
        $medida = new medida();
        $this->medida = $medida->find();

        if (Input::hasPost('medida')) {

            $this->medida = $medida->guardarDatos();
        }
     }
      public function crearPresentacion(){
        View::template(NULL);
        $this->accion="crearPresentacion";
        $presentacion = new presentacion();
        $this->preparacion = $presentacion->find();

        if (Input::hasPost('presentacion')) {

            $this->presentacion = $presentacion->guardarDatos();
        }
     }
       
}
?>
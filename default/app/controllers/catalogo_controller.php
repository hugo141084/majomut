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
     public function editarLote($id){
        
     
        $lote = new lote();
        $this->lote = $lote->find_first($id);

        if (Input::hasPost('lote')) {

            $this->lote = $lote->actualizarDatos();
            Redirect::to('catalogo/listadoLote');
        }
       
    }
     public function eliminarLote($id){
        $lote = new lote();
        $lote = $lote->find_first($id);
        $lote->estatus='0';
        $lote->update();
        Redirect::to('catalogo/listadoLote');
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
    public function editarCalidad($id){
        
        $this->accion="crearCalidad";
        $calidad = new calidad();
        $this->calidad = $calidad->find_first($id);

        if (Input::hasPost('calidad')) {

            $this->calidad = $calidad->actualizarDatos();
            Redirect::to('catalogo/listadoCalidad');
        }
       
    }
     public function eliminarCalidad($id){
        $calidad = new calidad();
        $calidad = $calidad->find_first($id);
        $calidad->estatus='0';
        $calidad->update();
        Redirect::to('catalogo/listadoCalidad');
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
    public function editarPreparacion($id){
        
     
        $preparacion = new preparacion();
        $this->preparacion = $preparacion->find_first($id);

        if (Input::hasPost('preparacion')) {

            $this->preparacion = $preparacion->actualizarDatos();
            Redirect::to('catalogo/listadoPreparacion');
        }
       
    }
     public function eliminarPreparacion($id){
        $preparacion = new preparacion();
        $preparacion = $preparacion->find_first($id);
        $preparacion->estatus='0';
        $preparacion->update();
        Redirect::to('catalogo/listadoPreparacion');
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
     public function editarMedida($id){
        
     
        $medida = new medida();
        $this->medida = $medida->find_first($id);

        if (Input::hasPost('medida')) {

            $this->medida = $medida->actualizarDatos();
            Redirect::to('catalogo/listadoMedida');
        }
       
    }
     public function eliminarMedida($id){
        $medida = new medida();
        $medida = $medida->find_first($id);
        $medida->estatus='0';
        $medida->update();
        Redirect::to('catalogo/listadoMedida');
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
     public function editarPresentacion($id) {
        
        $presentacion = new presentacion();
        $this->presentacion = $presentacion->find_first($id);
        if (Input::hasPost('presentacion')) {
            $this->presentacion = $presentacion->actualizarDatos();
         Redirect::to('catalogo/listadoPresentacion');
            
        }
        
        $this->encabezado= "";
    }
    public function eliminarPresentacion($id) {
        
        $presentacion = new presentacion();
        $presentacion = $presentacion->find_first($id);
        $presentacion->estatus='0';
        $presentacion->update();
         Redirect::to('catalogo/listadoPresentacion');
            
        
        
       
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
    
     public function crearCalidad(){
        View::template(NULL);
        $this->accion="crearCalidad";
        $calidad = new calidad();
        $this->zona = $calidad->find();

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
    public function editarEmbalaje($id){
        
     
        $embalaje = new embalaje();
        $this->embalaje = $embalaje->find_first($id);

        if (Input::hasPost('embalaje')) {

            $this->embalaje = $embalaje->actualizarDatos();
            Redirect::to('catalogo/listadoEmbalaje');
        }
       
    }
     public function eliminarEmbalaje($id){
        $embalaje = new embalaje();
        $embalaje = $embalaje->find_first($id);
        $embalaje->estatus='0';
        $embalaje->update();
        Redirect::to('catalogo/listadoEmbalaje');
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
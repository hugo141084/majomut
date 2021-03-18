<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class producto extends ActiveRecord {

    

    protected function initialize() {
        
    }

    public function listar() {
        
        
        return $this->find_all_by_sql("SELECT p.id, p.descripcion nombre,p.clave,p.descripcion nombre,pre.descripcion presentacion,c.descripcion calidad,pr.descripcion  preparacion,concat_ws(', ',p.descripcion,pre.descripcion,c.descripcion,pr.descripcion) descripcion, p.peso, p.existencia FROM producto p left join preparacion pr on pr.id=p.preparacion_id left join presentacion pre on pre.id=p.presentacion_id left join calidad c on p.calidad_id=c.id where p.estatus='1'");
        
    }
    public function listarEntrada() {
        
        
        return $this->find_all_by_sql("SELECT p.id, concat_ws(', ',p.descripcion,pre.descripcion,c.descripcion,pr.descripcion) descripcion FROM producto p left join preparacion pr on pr.id=p.preparacion_id left join presentacion pre on pre.id=p.presentacion_id left join calidad c on p.calidad_id=c.id ");
        
    }
    public function listarXid($id) {
        return $this->find_by_sql("SELECT p.id, concat_ws(', ',p.descripcion,pre.descripcion,c.descripcion,pr.descripcion) descripcion,p.clave, p.peso, p.existencia, em.descripcion empaque, me.descripcion medida,p.precio,p.impuesto FROM producto p inner join preparacion pr on pr.id=p.preparacion_id inner join presentacion pre on pre.id=p.preparacion_id inner join calidad c on p.calidad_id=c.id left join embalaje em on p.empaque_id=em.id left join medida me on p.medida_id=me.id  where p.id=$id");
    }
    public function guardarDatos(){
        $producto = new producto(Input::post('producto'));
        $producto->costo_promedio=$producto->costo; 
        $producto->fecha_usuario=date('Y-m-d H:i:s'); 
        $producto->usuario_id=Session::get('id'); 
        $producto->existencia="0"; 
        if($producto->numero_serie=="S"){
         $producto->lote="N";   
        }
        if( $producto->save()){
          echo "<script>  alert ('Registro Insertado....!');</script>"; 
          Redirect::to('producto/crear');
        }
    }
    
    public function actualizaProducto($productoId,$cantidad,$tipoMovimiento,$unidadXpaquete){
     $cantidad=$cantidad *$unidadXpaquete;
       if($tipoMovimiento =="E"){
          $cantidad=$cantidad;
      }else if($tipoMovimiento=="S"){
          $cantidad=$cantidad * (-1);
      }
                  $this->update_all("existencia = existencia + $cantidad", "id=$productoId ");
               }
               public function buscaXcondicion($condicion) {
            return $this->find_all_by_sql("SELECT *
FROM producto
 $condicion");
        }
}   
?>

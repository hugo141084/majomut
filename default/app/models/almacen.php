<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class almacen extends ActiveRecord {

    

    protected function initialize() {
        
    }

    public function listar() {
        return $this->find("estatus='1'");
    }
    public function listarXid($id) {
        return $this->find_by_sql("select * from almacen where id='$id'");
    }
    public function almacenApartados() {
        return $this->find_by_sql("select descripcion from almacen where almacen='100'");
    }
    public function destino($id) {
        return $this->find_all_by_sql("select distinct(al.descripcion) from almacen al inner join detalle_vale dv on dv.almacen_id=al.id where dv.compra_id='$id'");
    }
    public function listarProductoAlmacen($id) {
        
         $sqlProducto = "SELECT p.id, p.descripcion nombre,p.clave,concat_ws(', ',p.descripcion,pre.descripcion,c.descripcion,pr.descripcion) descripcion, p.peso, p.existencia,inv.id inventarioId FROM producto p left join preparacion pr on pr.id=p.preparacion_id left join presentacion pre on pre.id=p.presentacion_id left join calidad c on p.calidad_id=c.id inner join inventario inv on inv.producto_id=p.id where inv.almacen_id =$id";
        
        return $this->find_all_by_sql($sqlProducto);
    }
    public function guardarDatos(){
        $almacen = new almacen(Input::post('almacen'));
        $almacen->estado='1'; 
        
        $almacen->usuario=Session::get('id'); 
       
        if( $almacen->save()){
         // echo "<script>  alert ('Registro Insertado....!');</script>";  
        }
    }
    public function actualizarDatos(){
        $almacen = new almacen(Input::post('almacen'));
        
        if( $almacen->update()){
          //echo "<script>  alert ('Registro Insertado....!');</script>";  
        }
    }
    public function buscarAlmacen($condicion) {
            return $this->find_all_by_sql("SELECT distinct(alm.id), alm.descripcion
FROM almacen as alm, inventario as inv
WHERE inv.almacen_id = alm.id
AND $condicion order by alm.descripcion asc");
        }
        public function buscaAlmacen($condicion) {
            return $this->find_all_by_sql("SELECT * FROM almacen  $condicion ");
        }
        
       
        
        
}   
?>
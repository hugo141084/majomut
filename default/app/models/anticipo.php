<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class anticipo extends ActiveRecord {

    

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
    public function listarAnticipo() {
        
         $sqlProducto = "SELECT an.nombre, an.importe, an.importe_aplicado, an.referencia, con.concepto FROM anticipo an left join conceptos con  on an.concepto_id=con.id";
        
        return $this->find_all_by_sql($sqlProducto);
    }
    public function guardarDatos(){
        $anticipo = new anticipo(Input::post('anticipo'));
        
       
        if( $anticipo->save()){
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
        
       
        
        
}   
?>
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class detalle_vale extends ActiveRecord {

    

    protected function initialize() {
        
    }

    public function listar() {
        return $this->find();
    }
    public function listarXCompra($id) {
        return $this->find_all_by_sql("select * from detalle_vale where compra_id='$id'");
    }
    public function listarXid($id) {
        return $this->find_by_sql("select * from almacen where id='$id'");
    }
    public function listarProductoCompra($compraId) {
         $sqlProducto = "SELECT det.id, pro.clave, pro.descripcion,pro.peso, pro.peso_neto,pro.precio, det.cantidad, alm.almacen, pp.descripcion preparacion, pre.descripcion presentacion, lo.codigo, em.descripcion embalaje FROM detalle_vale det inner join producto pro on det.producto_id=pro.id inner join almacen alm on det.almacen_id=alm.id left join presentacion pre on pro.presentacion_id=pre.id left join preparacion pp on pro.preparacion_id=pp.id inner join lote lo on det.lote_id=lo.id inner join embalaje em on pro.empaque_id=em.id WHERE det.compra_id ='$compraId' ";
        return $this->find_all_by_sql($sqlProducto);
    }
    public function listarProductoAlmacen($id) {
         $sqlProducto = "SELECT inv.id, pro.clave, pro.DESCRIPCION, inv.EXISTENCIA, inv.CANTIDAD_MINIMA, inv.CANTIDAD_MAXIMA
FROM inventario as inv, producto as pro  WHERE ALMACEN_ID =$id and inv.PRODUCTO_ID= pro.ID ";
        return $this->find_all_by_sql($sqlProducto);
    }
    public function guardarDatos($compraId,$productoId,$cantidad,$unidadXpaquete,$almacenId,$loteSerie,$fechaCaducidad,$numInventario){
        $detalleCompra = new detalle_vale();
        $detalleCompra->compra_id=$compraId; 
        $detalleCompra->producto_id=$productoId; 
        $detalleCompra->cantidad=$cantidad;
        $detalleCompra->almacen_id=$almacenId;
        $detalleCompra->lote_id=$loteSerie;
        $detalleCompra->numero_inventario=$numInventario;
        $detalleCompra->fecha_caducidad = strftime("%Y-%m-%d", strtotime($fechaCaducidad));
        $detalleCompra->usuario_id=Session::get('id'); 
        $detalleCompra->save();
        
    }
    
    public function buscarAlmacen($condicion) {
            return $this->find_all_by_sql("SELECT distinct(alm.ID), alm.DESCRIPCION
FROM almacen as alm, inventario as inv
WHERE inv.ALMACEN_ID = alm.ID
AND $condicion");
        }
}   
?>

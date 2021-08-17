<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class detalle_venta extends ActiveRecord {

    

    protected function initialize() {
        
    }

    public function listar() {
        return $this->find();
    }
    public function listarXCompra($id) {
        return $this->find_all_by_sql("select * from detalle_compra where compra_id='$id'");
    }
    public function listarXid($id) {
        return $this->find_by_sql("select * from almacen where id='$id'");
    }
    public function listarProductoCompra($compraId) {
         $sqlProducto = "SELECT det.id, pro.clave, pro.descripcion, det.cantidad, alm.almacen, pre.descripcion AS unidad_entrada
FROM detalle_compra AS det, producto AS pro, almacen AS alm, presentacion AS pre
WHERE det.compra_id =$compraId
AND det.producto_id = pro.id
AND det.almacen_id = alm.id
 ";
        return $this->find_all_by_sql($sqlProducto);
    }
    public function listarProductoVenta($compraId) {
         $sqlProducto = "SELECT p.id,p.clave, concat_ws(', ',p.descripcion,pre.descripcion,c.descripcion,pr.descripcion) descripcion, dv.cantidad, dv.precio,dv.impuesto,dv.total "
                 . "FROM producto p left join preparacion pr on pr.id=p.preparacion_id left join presentacion pre on pre.id=p.presentacion_id left join calidad c on p.calidad_id=c.id "
                 . "inner join detalle_venta dv  on dv.producto_id=p.id where dv.venta_id=$compraId
 ";
        return $this->find_all_by_sql($sqlProducto);
    }
    public function listarProductoAlmacen($id) {
         $sqlProducto = "SELECT inv.id, pro.clave, pro.DESCRIPCION, inv.EXISTENCIA, inv.CANTIDAD_MINIMA, inv.CANTIDAD_MAXIMA
FROM inventario as inv, producto as pro  WHERE ALMACEN_ID =$id and inv.PRODUCTO_ID= pro.ID ";
        return $this->find_all_by_sql($sqlProducto);
    }
    public function guardarDatos($compraId,$productoId,$cantidad,$precio,$ivaI,$totalI){
        $detalleCompra = new detalle_venta();
        $detalleCompra->venta_id=$compraId; 
        $detalleCompra->producto_id=$productoId; 
        $detalleCompra->cantidad=$cantidad;
        $detalleCompra->precio=$precio;
         $detalleCompra->impuesto=$ivaI;
        $detalleCompra->total=$totalI;
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

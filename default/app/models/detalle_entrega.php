<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class detalle_entrega extends ActiveRecord {

    

    protected function initialize() {
        
    }

    public function listar() {
        return $this->find();
    }
     public function listarXentrega($id) {
        return $this->find_all_by_sql("select * from detalle_entrega where ENTREGA_ID=$id");
    }
    public function listarXid($id) {
        return $this->find_by_sql("select * from almacen where ID=$id");
    }
    public function listarProductoCompra($entregaId) {
         $sqlProducto = "SELECT det.id, pro.clave, pro.descripcion, det.cantidad, det.lote_serie, det.fecha_caducidad, pre.descripcion AS unidad_Salida, det.numero_inventario
FROM detalle_entrega AS det, producto AS pro, almacen AS alm, presentacion AS pre
WHERE det.entrega_id =$entregaId
AND det.producto_id = pro.id
AND det.almacen_id = alm.id
 ";
        return $this->find_all_by_sql($sqlProducto);
    }
    public function listarProductoAlmacen($id) {
         $sqlProducto = "SELECT inv.ID, pro.CLAVE_ARTICULO, pro.DESCRIPCION, inv.EXISTENCIA, inv.CANTIDAD_MINIMA, inv.CANTIDAD_MAXIMA
FROM inventario as inv, producto as pro  WHERE ALMACEN_ID =$id and inv.PRODUCTO_ID= pro.ID ";
        return $this->find_all_by_sql($sqlProducto);
    }
    public function guardarDatos($entregaId,$productoId,$cantidad,$unidadXpaquete,$almacenId,$loteSerie,$fechaCaducidad,$numInventario){
        $detalleCompra = new detalleEntrega();
        $detalleCompra->ENTREGA_ID=$entregaId; 
        $detalleCompra->PRODUCTO_ID=$productoId; 
        $detalleCompra->CANTIDAD=$cantidad;
        $detalleCompra->ALMACEN_ID=$almacenId;
        $detalleCompra->LOTE_SERIE=$loteSerie;
        $detalleCompra->NUMERO_INVENTARIO=$numInventario;
        $detalleCompra->FECHA_CADUCIDAD = strftime("%Y-%m-%d", strtotime($fechaCaducidad));
        $detalleCompra->USUARIO_ID=Session::get('id'); 
        $detalleCompra->save();
        
    }
    
    public function buscarAlmacen($condicion) {
            return $this->find_all_by_sql("SELECT distinct(alm.ID), alm.DESCRIPCION
FROM almacen as alm, inventario as inv
WHERE inv.ALMACEN_ID = alm.ID
AND $condicion");
        }
        public function sumaCantidad($condicion) {
            return $this->find_by_sql("SELECT sum(detEn.CANTIDAD) AS CANTIDAD FROM detalle_entrega  as detEn, entrega as ent
WHERE ent.ID = detEn.ENTREGA_ID $condicion ");
        }
}   
?>
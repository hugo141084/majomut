<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class entrega extends ActiveRecord {

    

    protected function initialize() {
        
    }

    public function listar() {
        return $this->find();
    }
    public function listarXid($id) {
        return $this->find_by_sql("select * from entrega where ID=$id");
    }
    public function listarProductoAlmacen($id) {
         $sqlProducto = "SELECT inv.ID, pro.CLAVE_ARTICULO, pro.DESCRIPCION, inv.EXISTENCIA, inv.CANTIDAD_MINIMA, inv.CANTIDAD_MAXIMA
FROM inventario as inv, producto as pro  WHERE ALMACEN_ID =$id and inv.PRODUCTO_ID= pro.ID ";
        return $this->find_all_by_sql($sqlProducto);
    }
    
    public function listarEntrega() {
   $usuarioId=Session::get('id');
        $dato=Load::model('usuariosAdministrador')->datosUsuario($usuarioId);
        if($dato->tipo=="ADMINISTRADOR"){
        $sqlCompra = "SELECT ent.ID, ent.FECHA_SOLICITUD, ent.FECHA_ENTREGA, dep.DESCRIPCION, ent.TIPO_DOCUMENTO, ent.ESTADO,concat_ws(' ',emp.NOMBRE, emp.APE_PAT, emp.APE_MAT) as NOMBRE 
FROM entrega as ent, empleado as emp, departamento as dep  WHERE ent.EMPLEADO_ID =emp.ID and emp.DEPARTAMENTO_ID=dep.ID  ";
        }else{
          $sqlCompra = "SELECT ent.ID, ent.FECHA_SOLICITUD, ent.FECHA_ENTREGA, dep.DESCRIPCION, ent.TIPO_DOCUMENTO, ent.ESTADO,concat_ws(' ',emp.NOMBRE, emp.APE_PAT, emp.APE_MAT) as NOMBRE 
FROM entrega as ent, empleado as emp, departamento as dep  WHERE ent.EMPLEADO_ID =emp.ID and emp.DEPARTAMENTO_ID=dep.ID and  ent.USUARIO_ID=$usuarioId  ";   
        }
        return $this->find_all_by_sql($sqlCompra);
    }
    
    public function guardarDatos(){
       $entrega = new entrega(Input::post('entrega'));
        $fechaDocumento=$entrega->FECHA_SOLICITUD;
        $fechaMovimiento=$entrega->FECHA_ENTREGA;
        
        $empleadoId=$entrega->EMPLEADO_ID;
        
        $entrega->ESTADO='Activo'; 
        $entrega->FECHA_SOLICITUD = strftime("%Y-%m-%d", strtotime($entrega->FECHA_SOLICITUD));
        $entrega->FECHA_ENTREGA = strftime("%Y-%m-%d", strtotime($entrega->FECHA_ENTREGA));
        $entrega->USUARIO_ID=Session::get('id'); 
        $tipoMovimiento="S";
        $numeroMovimiento="11";
        
        if( $entrega->save()){
            $entregaId=$entrega->ID;
            $referencia="VS-".str_pad($entrega->ID, 8, '0', STR_PAD_LEFT);
             $array_salida = Session::get('array_salida');
            $longitud = count($array_salida);
             for ($i = 0; $i < $longitud; $i++) {
              $detalleEntrega = new detalleEntrega();
              $lote=new lote();
              $serie=new serie();
              $movimiento=new movimientoInventario();
              $producto=new producto();
              $inventario=new inventario();
              $datoInventario=new numeroInventario();
      $productoId=  $array_salida[$i]['PRODUCTO_ID'] ;
       $cantidad= $array_salida[$i]['CANTIDAD'] ;
       $opcion= $array_salida[$i]['OPCION'];
        $unidadXpaquete="1";
        $fechaCaducidad=$array_salida[$i]['FECHA_CADUCIDAD'];
         $almacenId=$array_salida[$i]['ALMACEN_ID'];         
         $loteId=$array_salida[$i]['LOTE_ID']; 
         $serieId=$array_salida[$i]['SERIE_ID']; 
         $loteSerie= $array_salida[$i]['LOTE_SERIE'];
         $numInventario=$array_salida[$i]['NUMERO_INVENTARIO_ID'];
         $etiquetaInventario=$array_salida[$i]['NUMERO_INVENTARIO'];
        
              $detalleEntrega->guardarDatos($entregaId, $productoId, $cantidad, $unidadXpaquete, $almacenId, $loteSerie, $fechaCaducidad,$etiquetaInventario);
              if(($opcion=="lote") || ($opcion=="loteInventario")){
              $lote->validaSalida($loteId,$cantidad,$unidadXpaquete,$tipoMovimiento);    
              }else if (($opcion=="serie") || ($opcion=="serieInventario")){
              $serie->validaSalida($serieId,$cantidad,$unidadXpaquete,$tipoMovimiento);    
              }
              if($numInventario !="" ){
               $datoInventario->validaSalida($numInventario,$cantidad,$unidadXpaquete,$tipoMovimiento);   
              }
               
              $inventario->actualizaInventario($productoId,$cantidad,$tipoMovimiento,$unidadXpaquete,$almacenId);
              $producto->actualizaProducto($productoId,$cantidad,$tipoMovimiento,$unidadXpaquete);
              $movimiento->validaMovimiento($productoId,$fechaDocumento,$referencia,$fechaMovimiento,$empleadoId, $cantidad, $unidadXpaquete, $almacenId, $loteSerie,$opcion,$numeroMovimiento,$numInventario);
              
              
             }
          echo "<script>  jAlert ('Registro Insertado....!','AVISO');</script>";
           Input::delete();
           session::delete('array_salida');
           return $entregaId;
        }
    }
    public function cancelaSalida($id){
       $entrega = new entrega();
       $datosEntrega=$entrega->listarXid($id);
        $fechaDocumento=date('Y-m-d');
        $fechaMovimiento=date('Y-m-d');
        
        $empleadoId=$datosEntrega->EMPLEADO_ID;
        
        
        $tipoMovimiento="E";
        $numeroMovimiento="2";
        $etiqueta=str_pad($id, 8, '0', STR_PAD_LEFT);
        $referencia="VS-".$etiqueta;
        
              $detalleEntrega = new detalleEntrega();
              
              $lote=new lote();
              $serie=new serie();
              $movimiento=new movimientoInventario();
              $producto=new producto();
              $inventario=new inventario();
              $numeroInventario=new numeroInventario();
              $datos=$detalleEntrega->listarXentrega($id);
              foreach ($datos as $dato){      
        $productoId=$dato->PRODUCTO_ID;
             $cantidad=$dato->CANTIDAD;     
             $producto=new producto();
             $detalleProducto=$producto->listarXid($productoId);
      if($detalleProducto->LOTE=="S"){
         $opcion="lote";
         
      }else if($detalleProducto->NUMERO_SERIE=="S"){
         $opcion="serie";
      }else{
          $opcion="";
      }  
        $unidadXpaquete=1;
        $fechaCaducidad=$dato->FECHA_CADUCIDAD;
     $almacenId=$dato->ALMACEN_ID;         
         $loteSerie=$dato->LOTE_SERIE;  
         $numInventario=$dato->NUMERO_INVENTARIO;
          //    $detalleEntrega->guardarDatos($entregaId, $productoId, $cantidad, $unidadXpaquete, $almacenId, $loteSerie, $fechaCaducidad);
              if($opcion=="lote"){
              $loteId=$serie->obtenerIdLote($loteSerie,$fechaCaducidad,$productoId, $almacenId);    
              $lote->validaSalida($loteId,$cantidad,$unidadXpaquete,$tipoMovimiento);    
              }else if ($opcion=="serie"){
              $serieId=$serie->obtenerIdSerie($loteSerie,$productoId, $almacenId);
              $serie->validaSalida($serieId->ID,$cantidad,$unidadXpaquete,$tipoMovimiento);    
              }
              if($detalleProducto->NUMERO_INVENTARIO=="S"){
                $inventarioId=$numeroInventario->obtenerIdInventario($productoId,$almacenId,$loteSerie,$numInventario);
                    $numeroInventario->actualizaInventario($inventarioId->ID,$cantidad,$unidadXpaquete,$tipoMovimiento);  
              }
              $inventario->actualizaInventario($productoId,$cantidad,$tipoMovimiento,$unidadXpaquete,$almacenId);
              $producto->actualizaProducto($productoId,$cantidad,$tipoMovimiento,$unidadXpaquete);
              $movimiento->validaMovimiento($productoId,$fechaDocumento,$referencia,$fechaMovimiento,$empleadoId, $cantidad, $unidadXpaquete, $almacenId, $loteSerie,$opcion,$numeroMovimiento);
              
              }  
              $datosEntrega->actualizaEntrega($id); 
          echo "<script>  jAlert ('Registro Eliminado....!','AVISO');</script>";
           
        
    }
    
    public function actualizaEntrega($id){
     $fecha_cancelacion=date('Y-m-d');
                  $this->update_all("FECHA_CANCELACION ='$fecha_cancelacion', ESTADO='Cancelado' ", "ID= $id");
               }
   public function entregaXfecha($condicion){
    
              return    $this->find_all_by_sql("select * from entrega where $condicion order by FECHA_ENTREGA asc");
               } 
   public function entregaXvale($condicion){
     
               return   $this->find_all_by_sql("select * from entrega where $condicion order by ID asc");
               } 
   public function buscaProducto($condicion){
   $usuarioId=Session::get('id');
        $dato=Load::model('usuariosAdministrador')->datosUsuario($usuarioId);
        if(($dato->clasificacion_producto_id=="") || ($dato->clasificacion_producto_id=="0") ){
               return   $this->find_all_by_sql("SELECT distinct(pro.ID),pro.CLAVE_ARTICULO,  pro.DESCRIPCION, pro.UNIDAD_SALIDA
FROM producto as pro , entrega as ent, detalle_entrega as detEn
WHERE ent.ID = detEn.ENTREGA_ID and detEn.PRODUCTO_ID = pro.ID
 $condicion order by pro.DESCRIPCION ASC " );
        }else{
         return   $this->find_all_by_sql("SELECT distinct(pro.ID),pro.CLAVE_ARTICULO,  pro.DESCRIPCION, pro.UNIDAD_SALIDA
FROM producto as pro , entrega as ent, detalle_entrega as detEn
WHERE ent.ID = detEn.ENTREGA_ID and detEn.PRODUCTO_ID = pro.ID and pro.CLASIFICACION_PRODUCTO_ID=$dato->clasificacion_producto_id
 $condicion order by pro.DESCRIPCION ASC " );   
        }
               }  
   public function buscarAlmacen($condicion) {
            return $this->find_all_by_sql("SELECT distinct(alm.ID), alm.DESCRIPCION
FROM almacen as alm, inventario as inv
WHERE inv.ALMACEN_ID = alm.ID
AND $condicion");
        }
        public function listarDepartamento($condicion) {
            $usuarioId=Session::get('id');
        $dato=Load::model('usuariosAdministrador')->datosUsuario($usuarioId);
        if(($dato->clasificacion_producto_id=="") || ($dato->clasificacion_producto_id=="0") ){
               return   $this->find_all_by_sql("SELECT distinct(ent.DEPARTAMENTO_ID), dep.DESCRIPCION
FROM entrega as ent, departamento as dep
WHERE  ent.DEPARTAMENTO_ID = dep.ID 
 $condicion and ent.ESTADO='Activo' ORDER BY dep.DESCRIPCION ASC" );
        }else{
            return $this->find_all_by_sql("SELECT distinct(ent.DEPARTAMENTO_ID), dep.DESCRIPCION
FROM entrega as ent,detalle_entrega as detEn, departamento as dep, producto as pro
WHERE ent.ID=detEn.ENTREGA_ID and  detEn.PRODUCTO_ID = pro.ID and ent.DEPARTAMENTO_ID = dep.ID and pro.CLASIFICACION_PRODUCTO_ID=$dato->clasificacion_producto_id
 $condicion and ent.ESTADO='Activo' ORDER BY dep.DESCRIPCION ASC " );
        }}
}   
?>
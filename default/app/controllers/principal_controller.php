<?php

/**
 * Controller por defecto si no se usa el routes
 *
 */
class principalController extends AppController
{

   
     
    public function principal(){
        $ciclocosechaId = Session::get('cicloCosecha');
        if($ciclocosechaId < 1) Redirect::to('ciclo/seleccionar');
       $producto=new producto();
       $this->producto=$producto->count();
       $pedido=new pedido();
       $this->pedido=$pedido->count();
       $cotizacion=new cotizacion();
       $this->cotizacion=$cotizacion->count();
       $venta=new venta();
       $this->venta=$venta->count();
    }
}

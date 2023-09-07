<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//Load::models('conceptos'); 
class cobroController extends AppController {

    /**
     * Muestra el partial de las nominas generadas
     */
    
     public function listadoCobros() {
        $cotizacion = new cobros();
        $this->result = $cotizacion->listadoCobro();
        
        $this->encabezado = "COBROS";
    }

   
public function crearCobro() {
        $this->accion = "crearCobro";

        $cobro = new cobros();
        
        if (Input::hasPost('cobro')) {
           $cobro = new cobros(Input::Post('cobro'));
           $cobro->estatus='1';
           $cobro->fecha_captura=date("Y-m-d H:i:s");
           $cliente=$cobro->cliente_id;
           $monto=$cobro->monto;
           $cobro->save();
           $producto = new venta();
           $recibosCobro = new recibos_cobros();
           $listar = $producto->listarPendiente($cliente);
           $suma=0;
           foreach ($listar as $dato){
               if(((Input::Post('aplicar'.$dato->id))>0) and ($suma <=$monto)){
               
                 $guardaRC=new recibos_cobros();
                 $guardaRC->recibos_id=$dato->id;
                 $guardaRC->cobros_id=$cobro->id;
                 $guardaRC->cliente_id=$cliente;
                 $guardaRC->monto=Input::Post('aplicar'.$dato->id);
                 $guardaRC->estatus='1';
                 $guardaRC->save();
                 $actualiza=$producto->find_first($dato->id);
                 $actualiza->saldo=$actualiza->saldo - Input::Post('aplicar'.$dato->id);
                 $actualiza->update();
                 $suma=$suma + Input::Post('aplicar'.$dato->id);
               }
           }
           
           Redirect::to('cobro/listadoCobros');
        }
        Input::delete();
        
    }
    

   

    function libreriaCobro() {
        View::template(NULL);

        $this->operacion = Input::POST('operacion');


        if (($this->operacion) == "MOSTRAR_REMISION_SALDO") {
            $cliente= Input::POST('cliente');
            $producto = new venta();
            $this->listar = $producto->listarPendiente($cliente);
        } 
        }
public function cancelarCobro($id=NULL) {
    
        $cotizacion = new cobros();
        $cancelaC = $cotizacion->find_first($id);
        $cancelaC->estatus='0';
        $cancelaC->update();
        $venta = new venta();
        $recibosCobro = new recibos_cobros();
        $recibosC=$recibosCobro->find("cobros_id='$id'");
        $saldo=0;
        foreach ($recibosC as $dato){
            $actulizaRC=$recibosCobro->find_first($dato->id);
            $actulizaRC->estatus='0';
            $saldo=$actulizaRC->monto;
            $actulizaRC->update();
            $actualizaV=$venta->find_first($dato->recibos_id);
                 $actualizaV->saldo=$actualizaV->saldo - $saldo;
                 $actualizaV->update();
        }
         Redirect::to('cobro/listadoCobros');
       
    }
    
 public function funciones() {
        require_once APP_PATH . 'libs/configuracion.php';
        View::template(NULL);

        $this->operacion = Input::post('operacion');
        if (($this->operacion) == "BUSCA_BANCO") {

            $this->condicion = "";
        }
        if (($this->operacion) == "BUSCA_MOVIMIENTO") {
            $idConcepto = Input::post('idConcepto');
            $this->tipoMovimiento = Load::Model('conceptos')->datosConcepto($idConcepto);
        }
    }  
    
    public function correguirCobros($id=NULL) {
        View::select(NULL);
        $ventas = new venta();
        $venta = $ventas->find("condicion='C'");
        
        foreach ($venta as $dato){
            echo $dato->id;
            $reciboC=new recibos_cobros();
            $recibo=$reciboC->find_first("recibos_id=$dato->id and cliente_id='$dato->cliente_id' ");
            if((isset($recibo->id))){
            $recibo->monto=$dato->monto;
            $recibo->update();
            }
            $cobros=new cobros();
            $cobro=$cobros->find_first($recibo->cobros_id);
            $cobro->monto=$dato->monto;
            $cobro->update();
            $venta=new venta();
           $venta=$venta->find_first($dato->id);
           $venta->saldo=0;
           $venta->update();
        }
        // Redirect::to('cobro/listadoCobros');
       
    }
    
     public function cobros() {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '-1');
        $consulta = new venta();
        $condicion="";
        if (Input::hasPost('fecha_inicial')) {
            $metodo = Input::Post('pago_id');
            $fecha_inicial = Input::Post('fecha_inicial');
            $fecha_final = Input::Post('fecha_final');
            $titulo = Input::Post('titulo');
            $cliente = Input::Post('cliente_id');
           $condicion = "(V.fecha_documento >='$fecha_inicial') and (V.fecha_documento <='$fecha_final' )";
            if ($metodo != "")
                $condicion.=" and C.concepto_id='" . $metodo . "'";
            if ($cliente != "")
                $condicion.=" and V.cliente_id='" . $cliente . "'";
            $this->resultado = $consulta->find_all_by_sql("select V.documento,V.monto total,V.estado,DF.numero_factura, CO.concepto, C.fecha_documento,C.monto,C.referencia,C.fecha_referencia,CL.nombrecompleto,C.monto,V.condicion  from venta V left join recibos_cobros RC on V.id=RC.recibos_id inner join   cobros C on RC.cobros_id=C.id inner join cliente CL  on C.cliente_id=CL.id inner join conceptos CO on C.concepto_id=CO.id left join dato_factura DF  on V.id=DF.venta_id    where $condicion ");
          
              if (trim($titulo)!="") {
              $repRelacionCobros = $this->resultado;
              session::set("repRelacionCobros",$repRelacionCobros);
            ?>
            <script>
                window.open('reporteCobrosPdf/<?php echo $fecha_inicial . '/' . $fecha_final . '/' . $titulo ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
            </script>
            <?php
              }
           
        }
    }
    
}
?>

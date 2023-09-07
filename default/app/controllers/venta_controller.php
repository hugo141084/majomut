<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//Load::models('conceptos'); 
class ventaController extends AppController {

    /**
     * Muestra el partial de las nominas generadas
     */
    public function listadoCotizacion() {
        $ciclocosechaId = Session::get('cicloCosecha');
        if($ciclocosechaId < 1) Redirect::to('ciclo/seleccionar');
        $cotizacion = new cotizacion();
        $this->result = $cotizacion->listarCotizacion();
        $this->campos = array(
            utf8_encode('#') => 'id',
            utf8_encode('TIPO DOCUMENTO') => 'tipo_documento',
            utf8_encode('DOCUMENTO') => 'documento',
            utf8_encode('F. DOCUMENTO') => 'fecha_documento',
            utf8_encode('CLIENTE') => 'nombrecompleto',
            utf8_encode('RFC') => 'rfc',
            utf8_encode('ESTADO') => 'estado'
        );
        $this->encabezado = "COTIZACIONES";
    }

    public function listadoPedido() {
        $ciclocosechaId = Session::get('cicloCosecha');
        if($ciclocosechaId < 1) Redirect::to('ciclo/seleccionar');
        $cotizacion = new pedido();
        $this->result = $cotizacion->listarPedido();
        $this->campos = array(
            utf8_encode('#') => 'id',
            utf8_encode('TIPO DOCUMENTO') => 'tipo_documento',
            utf8_encode('ORDEN COMPRA') => 'orden_compra',
            utf8_encode('DOCUMENTO') => 'documento',
            utf8_encode('F. DOCUMENTO') => 'fecha_documento',
            utf8_encode('F. ENTREGA') => 'fecha_entrega',
            utf8_encode('CLIENTE') => 'nombrecompleto',
            utf8_encode('RFC') => 'rfc'
        );
        $this->encabezado = "PEDIDOS";
    }

    public function listadoAnticipo() {
        $cotizacion = new anticipo();
        $this->result = $cotizacion->listarAnticipo();
        $this->campos = array(
            utf8_encode('#') => 'id',
            utf8_encode('Cliente') => 'nombre',
            utf8_encode('Concepto') => 'concepto',
            utf8_encode('Referencia') => 'referencia',
            utf8_encode('Importe') => 'importe',
            utf8_encode('Importe aplicado') => 'importe_aplicado'
        );

        $this->encabezado = "ANTICIPOS";
    }

    public function listadoVenta() {
        $ciclocosechaId = Session::get('cicloCosecha');
        if($ciclocosechaId < 1) Redirect::to('ciclo/seleccionar');
        $cotizacion = new venta();
        $this->result = $cotizacion->listarVenta();
        $this->campos = array(
            utf8_encode('#') => 'id',
            utf8_encode('TIPO DOCUMENTO') => 'tipo_documento',
            utf8_encode('ORIGEN') => 'origen',
            utf8_encode('DOCUMENTO') => 'documento',
            utf8_encode('F. DOCUMENTO') => 'fecha_documento',
            utf8_encode('CLIENTE') => 'nombrecompleto',
            utf8_encode('RFC') => 'rfc',
            utf8_encode('ESTADO') => 'estado',
            utf8_encode('ESTADO') => 'estado'
        );
        $this->encabezado = "VENTAS";
    }
     public function listadoCobros() {
         $ciclocosechaId = Session::get('cicloCosecha');
        if($ciclocosechaId < 1) Redirect::to('ciclo/seleccionar');
        $cotizacion = new cobros();
        $this->result = $cotizacion->listadoCobro();
        
        $this->encabezado = "COBROS";
    }

    public function crearCotizacion() {
        $ciclocosechaId = Session::get('cicloCosecha');
        if($ciclocosechaId < 1) {Redirect::to('ciclo/seleccionar');}
        $this->accion = "crearCotizacion";

        $cotizacion = new cotizacion();
        $this->cotizacion = $cotizacion->find();

        if (Input::hasPost('cotizacion')) {
            $array_productos = Session::get('array_cotizacion');
            $num = count($array_productos);

            if ($num == "0") {

                echo "<script>  alert ('Primero agrege partidas para poder guardar este movimiento....!');</script>";
            } else {
                $this->cotizacion = $cotizacion->guardarDatos();
                ?>
                <script>
                    window.open('reporteCotizacion/<?php echo $this->cotizacion; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
                </script>
                <?php
                Input::delete();
                Redirect::to('venta/crearCotizacion');
            }
        } else {
            session::delete('array_cotizacion');
        }
        Input::delete();
       // $folios = new series_folios();
        //$datoFolios = $folios->find_first("tipo = 'COTIZACION'");

        //$this->num = "C-" . str_pad(($datoFolios->consecutivo) + 1, 4, "0", STR_PAD_LEFT);
    }
 public function editarCotizacion($num) {
     $ciclocosechaId = Session::get('cicloCosecha');
        if($ciclocosechaId < 1) Redirect::to('ciclo/seleccionar');
        $this->accion = "editarCotizacion";
        $this->num=$num;
        $cotizacion = new cotizacion();
        $this->cotizacion = $cotizacion->find_first($num);
        $detalleCompra = new detalle_cotizacion();
        $this->detalleCompra = $detalleCompra->listarProductoCompra($num);
        if (Input::hasPost('cotizacion')) {
           
              $cotizacion=new cotizacion(Input::post('cotizacion'));
              $cotizacion->subtotal=str_replace(',','',$cotizacion->subtotal);
              $cotizacion->iva=str_replace(',','',$cotizacion->iva);
              $cotizacion->monto=str_replace(',','',$cotizacion->monto);
              $cotizacion->ciclocosecha_id=$ciclocosechaId; 
               $cotizacion->update();
                ?>
                <script>
                    window.open('../reporteCotizacion/<?php echo $num; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
                </script>
                <?php
                Input::delete();
                Redirect::to('venta/listadoCotizacion');
            
        } else {
            
        }
        Input::delete();
       
    }
    public function crearPedido() {
        $ciclocosechaId = Session::get('cicloCosecha');
        if($ciclocosechaId < 1) Redirect::to('ciclo/seleccionar');
        $this->accion = "crearPedido";

        $pedido = new pedido();
        $this->pedido = $pedido->find();

        if (Input::hasPost('pedido')) {
            $array_productos = Session::get('array_pedido');
            $num = count($array_productos);

            if ($num == "0") {

                echo "<script>  alert ('Primero agrege partidas para poder guardar este movimiento....!');</script>";
            } else {
                $this->pedido = $pedido->guardarDatos();
                ?>
                <script>
                    window.open('reportePedido/<?php echo $this->pedido; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
                </script>
                <?php
                Input::delete();
                Redirect::to('venta/crearPedido');
            }
        } else {
            session::delete('array_pedido');
        }
        Input::delete();
        
    }

    public function crearVenta() {
        $ciclocosechaId = Session::get('cicloCosecha');
        if($ciclocosechaId < 1) Redirect::to('ciclo/seleccionar');
        $this->accion = "crearVenta";

        $venta = new venta();
        $this->venta = $venta->find();
        $ventaId = 0;
        if (Input::hasPost('venta')) {
            $array_productos = Session::get('array_venta');
            $num = count($array_productos);

            if ($num == "0") {

                echo "<script>  alert ('Primero agrege partidas para poder guardar este movimiento....!');</script>";
            } else {
                $aux = Input::Post("venta");
                $condiciones = $aux['condicion'];
                $cliente_id = $aux['cliente_id'];
                $conceptoId = Input::Post('concepto_id');
                $fecha_referencia = Input::Post('fecha_referencia');
                (($fecha_referencia == "") ? $fecha_referencia = date('Y-m-d') : '');
                if ($condiciones == 'C') {
                    $importeTotal = Input::Post('dtotal');
                    $importeRecibido = Input::Post('importe_recibido');
                    $cambio = input::Post('cambio');
                    $uso_cfdi = input::Post('uso_cfdi');
                    if ($cambio > 0) $importeRecibido=$importeRecibido-$cambio;
                    if ($importeRecibido > $importeTotal) {
                        $monto = $importeTotal;
                    } else {
                        $monto = $importeRecibido;
                    }
                }
                $ventaId = $venta->guardarDatos();

                if ($condiciones == 'C') {
                    $operacion = new conceptos();
                    $resultado = $operacion->find_first("id='$conceptoId'");
                    $operacion = $resultado->movimiento;


                    $datosCobro = new cobros();
                    $datosCobro = $datosCobro->guardarDatosC(date('Y-m-d H:m:s'), $conceptoId, Input::Post('referencia'), $fecha_referencia, $importeRecibido, Input::Post('cuenta_id'), Session::get('id'), $operacion, $fecha_referencia, $uso_cfdi,$cliente_id);
                    $idCobro = $datosCobro;
                    $insertaReciboCobro = new recibos_cobros();
                    $insertaReciboCobro->guardarDatosRC($ventaId, $idCobro, $cliente_id, $monto, '1');
                    $cuentaCobrar = new cuenta_cobrar();
                    $cuenta = $cuentaCobrar->registraMovimiento('Cobro', $resultado->cuenta_cobrar_id, $resultado->concepto, $fecha_referencia, '', $cliente_id, $importeRecibido);
                    $actualizarSaldo = new venta();
                    $actualizarSaldo->update_all("saldo=saldo-$monto","id='$ventaId'");
                    
                }
                ?>
                <script>
                    window.open('reporteVenta/<?php echo $ventaId; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
                </script>
                <?php
                Input::delete();
                   Redirect::to('venta/crearVenta');
            }
        } else {
            session::delete('array_venta');
        }
        Input::delete();
        $folios = new series_folios();
        $datoFolios = $folios->find_first("tipo = 'REMISION'");

        $this->num = "R-" . str_pad(($datoFolios->consecutivo) + 1, 4, "0", STR_PAD_LEFT);
    }
public function crearCobro() {
    $ciclocosechaId = Session::get('cicloCosecha');
        if($ciclocosechaId < 1) Redirect::to('ciclo/seleccionar');
        $this->accion = "crearVenta";

        $venta = new venta();
        $this->venta = $venta->find();
        $ventaId = 0;
        if (Input::hasPost('venta')) {
            $array_productos = Session::get('array_venta');
            $num = count($array_productos);

            if ($num == "0") {

                echo "<script>  alert ('Primero agrege partidas para poder guardar este movimiento....!');</script>";
            } else {
                $aux = Input::Post("venta");
                $condiciones = $aux['condicion'];
                $cliente_id = $aux['cliente_id'];
                $conceptoId = Input::Post('concepto_id');
                $fecha_referencia = Input::Post('fecha_referencia');
                (($fecha_referencia == "") ? $fecha_referencia = date('Y-m-d') : '');
                if ($condiciones == 'C') {
                    $importeTotal = Input::Post('dtotal');
                    $importeRecibido = Input::Post('importe_recibido');
                    $cambio = input::Post('cambio');
                    $uso_cfdi = input::Post('uso_cfdi');
                    if ($cambio > 0) $importeRecibido=$importeRecibido-$cambio;
                    if ($importeRecibido > $importeTotal) {
                        $monto = $importeTotal;
                    } else {
                        $monto = $importeRecibido;
                    }
                }
                $ventaId = $venta->guardarDatos();

                if ($condiciones == 'C') {
                    $operacion = new conceptos();
                    $resultado = $operacion->find_first("id='$conceptoId'");
                    $operacion = $resultado->movimiento;


                    $datosCobro = new cobros();
                    $datosCobro = $datosCobro->guardarDatosC(date('Y-m-d H:m:s'), $conceptoId, Input::Post('referencia'), $fecha_referencia, $importeRecibido, Input::Post('cuenta_id'), Session::get('id'), $operacion, $fecha_referencia, $uso_cfdi,$cliente_id);
                    $idCobro = $datosCobro;
                    $insertaReciboCobro = new recibos_cobros();
                    $insertaReciboCobro->guardarDatosRC($ventaId, $idCobro, $cliente_id, $monto, '1');
                    $cuentaCobrar = new cuenta_cobrar();
                    $cuenta = $cuentaCobrar->registraMovimiento('Cobro', $resultado->cuenta_cobrar_id, $resultado->concepto, $fecha_referencia, '', $cliente_id, $importeRecibido);
                    $actualizarSaldo = new venta();
                    $actualizarSaldo->update_all("saldo=saldo-$monto","id='$ventaId'");
                    
                }
                ?>
                <script>
                    window.open('reporteVenta/<?php echo $ventaId; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
                </script>
                <?php
                Input::delete();
                   Redirect::to('venta/crearVenta');
            }
        } else {
            session::delete('array_venta');
        }
        Input::delete();
        $folios = new series_folios();
        $datoFolios = $folios->find_first("tipo = 'REMISION'");

        $this->num = "R-" . str_pad(($datoFolios->consecutivo) + 1, 4, "0", STR_PAD_LEFT);
    }
    public function ventaDocto($pedidoId, $origen) {
        $ciclocosechaId = Session::get('cicloCosecha');
        if($ciclocosechaId < 1) Redirect::to('ciclo/seleccionar');
        $this->accion = "ventaDocto";
        $this->pedidoId = $pedidoId;
        $this->origen = $origen;
        $venta = new venta();
        $this->venta = $venta->find();




        if (Input::hasPost('venta')) {
            $array_productos = Session::get('array_venta');
            $num = count($array_productos);

            if ($num == "0") {

                echo "<script>  alert ('Primero agrege partidas para poder guardar este movimiento....!');</script>";
            } else {
                $this->venta = $venta->guardarDatos();
                ?>
                <script>
                    window.open('reporteVenta/<?php echo $this->venta; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
                </script>
                <?php
                if ($origen == "Pedido") {
                    $pedido = new pedido();
                    $pedido = $pedido->find_first($pedidoId);
                    $pedido->venta = '1';
                    $pedido->update();
                } else {
                    $pedido = new cotizacion();
                    $this->pedido = $pedido->find_first($pedidoId);
                    $pedido->venta = '1';
                    $pedido->update();
                }

                Input::delete();
                Redirect::to('venta/listado' . $origen);
            }
        } else {
            
        }
        Input::delete();

        if ($origen == "Pedido") {
            $pedido = new pedido();
            $this->pedido = $pedido->find_first($pedidoId);
            $detalleCompra = new detalle_pedido();
            $this->detalleCompra = $detalleCompra->listarProductoCompra($pedidoId);
            $detalleCompra = $this->detalleCompra;
            $cliente=$this->pedido->cliente_id;
        } else {
            $pedido = new cotizacion();
            $this->pedido = $pedido->find_first($pedidoId);
            $detalleCompra = new detalle_cotizacion();
            $this->detalleCompra = $detalleCompra->listarProductoCompra($pedidoId);
            $detalleCompra = $this->detalleCompra;
            $cliente=$this->pedido->cliente_id;
        }
        $datosCliente=new cliente();
        $this->cliente=$datosCliente->find_first($cliente);
        session::delete('array_venta');
        foreach ($detalleCompra as $info) {
            $clase = new clase_venta();
            $this->mensaje = $clase->introduce_movimiento_venta($info->cantidad, $info->productoId, $info->precio, "", "", "", 'lote', "","",$info->presentacion_venta);
                                                                
        }

        $folios = new series_folios();
        $datoFolios = $folios->find_first("tipo = 'REMISION'");

        $this->num = "R-" . str_pad(($datoFolios->consecutivo) + 1, 4, "0", STR_PAD_LEFT);
    }

    public function crearAnticipo() {
        $this->accion = "crearAnticipo";

        $anticipo = new anticipo();
        $this->anticipo = $anticipo->find();

        if (Input::hasPost('anticipo')) {

 $array_productos = Session::get('array_anticipo');
            $num = count($array_productos);
 if ($num == "0") {

                echo "<script>  alert ('Primero agrege partidas para poder guardar este movimiento....!');</script>";
            } else {
            $this->anticipo = $anticipo->guardarDatos();
           Input::delete();
            Redirect::to('venta/listadoAnticipo');
            }
            
        }
         
         Input::delete();
        $folios = new series_folios();
        $datoFolios = $folios->find_first("tipo = 'ANTICIPO'");

        $this->num = "ANT-" . str_pad(($datoFolios->consecutivo) + 1, 4, "0", STR_PAD_LEFT);
    }

    public function crearVale() {
        $this->accion = "crearVale";

        $venta = new venta();
        $this->venta = $venta->find();

        if (Input::hasPost('venta')) {
            $array_productos = Session::get('array_venta');
            $num = count($array_productos);

            if ($num == "0") {

                echo "<script>  alert ('Primero agrege partidas para poder guardar este movimiento....!');</script>";
            } else {
                $this->venta = $venta->guardarDatosVale();
                ?>
                <script>
                    window.open('reporteVenta/<?php echo $this->venta; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
                </script>
                <?php
                Input::delete();
                Redirect::to('venta/crearVale');
            }
        } else {
            session::delete('array_venta');
        }
        Input::delete();
        $folios = new series_folios();
        $datoFolios = $folios->find_first("tipo = 'REMISION'");

        $this->num = "R-" . str_pad(($datoFolios->consecutivo) + 1, 4, "0", STR_PAD_LEFT);
    }

    public function crearSalidaCT() {
        $this->accion = "crearSalidaCT";

        $cotizacion = new cotizacion();
        $this->cotizacion = $cotizacion->find();

        if (Input::hasPost('cotizacion')) {
            $array_productos = Session::get('array_productos');
            $num = count($array_productos);

            if ($num == "0") {

                echo "<script>  alert ('Primero agrege partidas para poder guardar este movimiento....!');</script>";
            } else {
                $this->cotizacion = $cotizacion->guardarDatosSCT();
                ?>
                <script>
                    window.open('cotizacionSalidaCT/<?php echo $this->cotizacion; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
                </script>
                <?php
                Input::delete();
                Redirect::to('inventario/crearSalidaCT');
            }
        } else {
            session::delete('array_productos');
        }
        Input::delete();
        $folios = new series_folios();
        $datoFolios = $folios->find_first("tipo = 'VALE'");

        $this->num = "V-" . str_pad(($datoFolios->consecutivo) + 1, 4, "0", STR_PAD_LEFT);
    }

    public function crearSalida() {
        $this->accion = "crearSalida";

        $entrega = new entrega();
        $this->entrega = $entrega->find();


        if (Input::hasPost('cotizacion')) {
            $array_productos = Session::get('array_productos');
            $num = count($array_productos);

            if ($num == "0") {

                echo "<script>  alert ('Primero agrege partidas para poder guardar este movimiento....!');</script>";
            } else {
                $this->cotizacion = $cotizacion->guardarDatosSCT();
                ?>
                <script>
                    window.open('cotizacionSalidaCT/<?php echo $this->cotizacion; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
                </script>
                <?php
                Input::delete();
                Redirect::to('inventario/crearSalida');
            }
        } else {
            session::delete('array_productos');
        }
        Input::delete();
        $folios = new series_folios();
        $datoFolios = $folios->find_first("tipo = 'VALE'");

        $this->num = "V-" . str_pad(($datoFolios->consecutivo) + 1, 4, "0", STR_PAD_LEFT);
    }

    public function eliminarSalida($id = NULL) {
        View::select(NULL);
        $salida = new entrega();
        $this->salida = $salida->cancelaSalida($id);
        Redirect::to('inventario/listadoSalida');
    }

    public function crear() {
        $this->accion = "crear";

        $almacen = new almacen();
        $this->producto = $almacen->find();

        if (Input::hasPost('almacen')) {

            $this->almacen = $almacen->guardarDatos();
        }
    }

    public function inventarioInicial() {
        $this->accion = "inventarioInicial";
    }

    public function crearRecepcion() {
        $this->accion = "crearRecepcion";

        $cotizacion = new cotizacion();
        $this->cotizacion = $cotizacion->find();

        if (Input::hasPost('cotizacion')) {
            $array_productos = Session::get('array_productos');
            $num = count($array_productos);

            if ($num == "0") {

                echo "<script>  alert ('Primero agrege partidas para poder guardar este movimiento....!');</script>";
            } else {
                $this->cotizacion = $cotizacion->guardarDatos();
                ?>
                <script>
                    window.open('cotizacionEntrada/<?php echo $this->cotizacion; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
                </script>
                <?php
                Input::delete();
                Redirect::to('inventario/crearRecepcion');
            }
        } else {
            session::delete('array_productos');
        }
        Input::delete();
        $folios = new series_folios();
        $datoFolios = $folios->find_first("tipo = 'VALE'");

        $this->num = "V-" . str_pad(($datoFolios->consecutivo) + 1, 4, "0", STR_PAD_LEFT);
    }

    public function inventarioIni() {
        $this->accion = "inventarioIni";

        $cotizacion = new cotizacion();
        $this->cotizacion = $cotizacion->find();

        if (Input::hasPost('cotizacion')) {
            $array_productos = Session::get('array_productos');
            $num = count($array_productos);

            if ($num == "0") {

                echo "<script>  alert ('Primero agrege partidas para poder guardar este movimiento....!');</script>";
            } else {
                $this->cotizacion = $cotizacion->guardarDatosIni();
                ?>
                <script>
                    window.open('cotizacionInicial/<?php echo $this->cotizacion; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
                </script>

                <?php
                Input::delete();
                Redirect::to('inventario/inventarioIni');
            }
        } else {
            session::delete('array_productos');
        }
        Input::delete();
        $folios = new series_folios();
        $datoFolios = $folios->find_first("tipo = 'VALE'");

        $this->num = "V-" . str_pad(($datoFolios->consecutivo) + 1, 4, "0", STR_PAD_LEFT);
    }

    public function eliminarEntrada($id = NULL) {
        View::select(NULL);
        $compra = new compra();
        $this->compra = $compra->cancelaCompra($id);
        Redirect::to('inventario/listadoRecepcion');
    }

    public function imprimirRecepcion($id) {
        ?>
        <script>
            window.open('cotizacionEntrada/<?php echo $id; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
        </script>
        <?php
    }

    public function editar($id = null) {
        $this->accion = 'editar';
        $almacen = new almacen();
        if ($id != null) {
            $this->almacen = $almacen->find($id);
        } else if (Input::hasPost('almacen')) {
            $almacen->USUARIO_ID = Session::get('id');
            if ($almacen->update(Input::post('almacen'))) {


                Redirect::to('inventario/listadoAlmacen');
                Flash::valid('Registro Actualizado');
                Input::delete();
            } else {
                $this->almacen = Input::post('inventario/listadoAlmacen');
                Flash::error('Falló Operación');
            }
        }
        view::select('crear');
    }

    public function asignar() {
        $this->accion = "crear";
    }

    public function cotizacionInicial($compraId) {
        $compra = new cotizacion();
        $this->datosCompra = $compra->listarXid($compraId);
        $datosCompra = $this->datosCompra;
        $detalleCompra = new detalle_cotizacion();
        $this->detalleCompra = $detalleCompra->listarProductoCompra($compraId);
        $usuario = new usuario();
        $destino = new almacen();
        $this->destino = $destino->destino($compraId);
        $ciclo = new lote();
        $this->ciclo = $ciclo->ciclo($compraId);


        // $this->datosUsuario=$usuario->listarEmpleado($this->datosCompra->USUARIO_ID);
    }

    public function reporteCotizacion($compraId) {
        $compra = new cotizacion();
        $this->datosCompra = $compra->listarXid($compraId);
        $cotizacion = $this->datosCompra;
        $detalleCompra = new detalle_cotizacion();
        $this->detalleCompra = $detalleCompra->listarProductoCompra($compraId);
        $usuario = new usuario();
        $destino = new almacen();
        $this->destino = $destino->destino($compraId);
        $ciclo = new lote();
        $this->ciclo = $ciclo->ciclo($compraId);
        // $this->datosUsuario=$usuario->listarEmpleado($this->datosCompra->USUARIO_ID);
        $banco = new bancosCuentas();
        $this->datosBancarios = $banco->find_by_sql("select * from  bancos ban inner join bancos_cuentas bc on bc.bancos_id=ban.id where bc.id='$cotizacion->bancos_cuentas_id'");
    }

    public function reportePedido($compraId) {
        $compra = new pedido();
        $this->datosCompra = $compra->listarXid($compraId);
        $pedido = $this->datosCompra;
        $detalleCompra = new detalle_pedido();
        $this->detalleCompra = $detalleCompra->listarProductoCompra($compraId);
        $usuario = new usuario();
        $destino = new almacen();
        $this->destino = $destino->destino($compraId);
        $ciclo = new lote();
        $this->ciclo = $ciclo->ciclo($compraId);
        // $this->datosUsuario=$usuario->listarEmpleado($this->datosCompra->USUARIO_ID);
        $banco = new bancosCuentas();
        $this->datosBancarios = $banco->find_by_sql("select * from  bancos ban inner join bancos_cuentas bc on bc.bancos_id=ban.id where bc.id='$pedido->bancos_cuentas_id'");
    }

    public function reporteVenta($compraId) {
        $compra = new venta();
        $this->datosCompra = $compra->listarVentaId($compraId);
        $detalleCompra = new detalle_venta();
        $this->detalleCompra = $detalleCompra->listarProductoVenta($compraId);
        $usuario = new usuario();
        $destino = new almacen();
        $this->destino = $destino->destino($compraId);
        $ciclo = new lote();
        $this->ciclo = $ciclo->ciclo($compraId);
        $this->detalle=$compra->find_by_sql("SELECT DF.numero_factura, DF.fecha_emision,DF.fecha_envio fecha_envio_factura, EM.nombre empresa,E.numero_rastreo,E.fecha_envio,E.fecha_entrega FROM venta V inner join dato_factura DF on DF.venta_id=V.id inner join envio E on E.venta_id=V.id inner join empresa EM on E.empresa_id = EM.id where V.id='$compraId'");
        // $this->datosUsuario=$usuario->listarEmpleado($this->datosCompra->USUARIO_ID);
    }
    public function ticket($compraId) {
        View::template(NULL);
        $compra = new venta();
        $this->datosCompra = $compra->listarVentaId($compraId);
        $detalleCompra = new detalle_venta();
        $this->detalleCompra = $detalleCompra->listarProductoVenta($compraId);
        $usuario = new usuario();
        $destino = new almacen();
        $this->destino = $destino->destino($compraId);
        $ciclo = new lote();
        $this->ciclo = $ciclo->ciclo($compraId);
        // $this->datosUsuario=$usuario->listarEmpleado($this->datosCompra->USUARIO_ID);
    }

    public function cotizacionEntradaCT($compraId) {
        $compra = new cotizacion();
        $this->datosCompra = $compra->listarXid($compraId);
        $datosCompra = $this->datosCompra;
        $detalleCompra = new detalle_cotizacion();
        $this->detalleCompra = $detalleCompra->listarProductoCompra($compraId);
        $usuario = new usuario();
        $destino = new almacen();
        $this->destino = $destino->destino($compraId);
        $ciclo = new lote();
        $this->ciclo = $ciclo->ciclo($compraId);

        $this->referencia = $compra->find_by_sql("select documento from cotizacion where id='$datosCompra->referencia_id '");
        // $this->datosUsuario=$usuario->listarEmpleado($this->datosCompra->USUARIO_ID);
    }

    public function cotizacionSalida($entregaId) {
        $entrega = new entrega();
        $this->datosEntrega = $entrega->listarXid($entregaId);
        $empleado = new empleado();
        $this->datosEmpleado = $empleado->buscarEmpleado($this->datosEntrega->EMPLEADO_ID);
        $detalleEntrega = new detalle_entrega();
        $this->detalleEntrega = $detalleEntrega->listarProductoCompra($entregaId);
        //    $departamento=new departamento();
        //   $this->datosDepartamento=$departamento->listarXid($this->datosEntrega->DEPARTAMENTO_ID);
        // $usuario=new usuario();
        //  $this->datosUsuario=$usuario->listarEmpleado($this->datosEntrega->USUARIO_ID); 
    }

    function libreriaVenta() {
        View::template(NULL);
        $ciclocosechaId = Session::get('cicloCosecha');
        $this->operacion = Input::POST('operacion');


        if (($this->operacion) == "MOSTRAR") {
            $producto = new producto();
            $this->listar = $producto->listar();
        } else if (($this->operacion) == "BUSCA_UNIDADPRECIO") {
            $productoId = Input::POST('productoId');
            $producto = new producto();
            $this->producto = $producto->listarXid($productoId);
        } else if (($this->operacion) == "MOSTRAR_INICIAL") {
            $id = Input::POST('almacen');
            $almacen = new almacen();
            $this->listar = $almacen->listarProductoAlmacen($id);
        } else if (($this->operacion) == "AGREGAR_PRODUCTO") {

            $inventario = new inventario();


            $this->resultado = $inventario->guardarDatos(Input::POST('producto'), Input::POST('almacen'), Input::POST('minimo'), Input::POST('maxima'));
        } else if (($this->operacion) == "AGREGAR_PARTIDA") {

            $clase = new clase_venta();
            $this->mensaje = $clase->introduce_movimiento(Input::POST('cantidad'), Input::POST('productoId'), Input::POST('precio'), Input::POST('lote'), Input::POST('fecha_caducidad'), Input::POST('serie'), Input::POST('opcion'), Input::POST('numInventario'), Input::POST('presentacionV'), Input::POST('presentacion'), Input::POST('comentario'));
        }else if (($this->operacion) == "AGREGAR_PARTIDAE") {

             $detalleCompra = new detalle_cotizacion();
             $productoId=  Input::POST('productoId');   
$cantidad=Input::POST('cantidad');
$precio=Input::POST('precio');
$cotizacionId=Input::POST('id');
$presentacion=Input::POST('presentacion');
$cantidadP=$cantidad * (1/$presentacion);
       $pro = new producto();     
        $pro=$pro->find_first('id='.$productoId);
        $iva=$pro->impuesto; 
         $medida=new medida();
        $datoMedida=$medida->find_first($pro->medida_id);     
        $partidaTotal=$cantidadP*$precio;
         
         $ivaI=(round((($partidaTotal*$iva)/100),2));
         $totalI=$partidaTotal+$ivaI;
         $ivaTotal=$ivaTotal+$ivaI;
          $importeTotal=$importeTotal+$totalI;
          $subtotal=$subtotal+$partidaTotal;
              $detalleCompra->guardarDatos($cotizacionId, Input::POST('productoId'), $cantidad,Input::POST('precio'),$ivaI,$totalI,$cantidadP,$presentacion,$datoMedida->descripcion);
            $actualizar= new cotizacion();
           $actualizar->update_all("subtotal =  $subtotal,iva=$ivaTotal,monto=$importeTotal", "id=$cotizacionId ");
            
        } else if (($this->operacion) == "AGREGAR_PARTIDA_PEDIDO") {

            $clase = new clase_venta();
            $this->mensaje = $clase->introduce_movimiento_pedido(Input::POST('cantidad'), Input::POST('productoId'), Input::POST('precio'), Input::POST('lote'), Input::POST('fecha_caducidad'), Input::POST('serie'), Input::POST('opcion'), Input::POST('numInventario'), Input::POST('presentacionV'), Input::POST('presentacion'));
        } else if (($this->operacion) == "AGREGAR_PARTIDA_VENTA") {

            $clase = new clase_venta();
            $this->mensaje = $clase->introduce_movimiento_venta(Input::POST('cantidad'), Input::POST('productoId'), Input::POST('precio'), Input::POST('lote'), Input::POST('almacenId'), Input::POST('serie'), Input::POST('opcion'), Input::POST('numInventario'), Input::POST('presentacionV'), Input::POST('presentacion'));
        }else if (($this->operacion) == "AGREGAR_PARTIDA_ANTICIPO") {

            $clase = new clase_venta();
            $this->mensaje = $clase->introduce_movimiento_anticipo(Input::POST('cantidad'), Input::POST('productoId'), Input::POST('precio'), Input::POST('lote'), Input::POST('almacenId'), Input::POST('serie'), Input::POST('opcion'), Input::POST('numInventario'), Input::POST('presentacionV'), Input::POST('presentacion'));
        } else if (($this->operacion) == "AGREGAR_INICIAL") {
            $tipoMovimiento = "E";
            $numeroMovimiento = "8";
            $fechaDocumento = date('Y-m-d');
            $fechaMovimiento = date('Y-m-d');
            $referencia = "INV. INICIAL";
            $empleadoId = Session::get('id');

            $lote = new lote();
            $serie = new serie();
            $movimiento = new movimientoInventario();
            $producto = new producto();
            $inventario = new inventario();
            $productoId = Input::POST('productoId');
            $cantidad = Input::POST('cantidad');
            $opcion = Input::POST('opcion');
            $unidadXpaquete = "1";
            $fechaCaducidad = Input::POST('fechaCaducidad');
            $almacenId = Input::POST('almacenId');
            $loteSerie = Input::POST('loteSerie');



            if ($opcion == "lote") {
                $this->mensaje = $lote->validaEntrada($loteSerie, $fechaCaducidad, $productoId, $almacenId, $cantidad, $unidadXpaquete, $tipoMovimiento);
            } else if ($opcion == "serie") {
                $this->mensaje = $serie->validaEntrada($loteSerie, $productoId, $almacenId, $cantidad, $unidadXpaquete, $tipoMovimiento);
            }
            $this->mensaje = $this->mensaje . $inventario->actualizaInventario($productoId, $cantidad, $tipoMovimiento, $unidadXpaquete, $almacenId);
            $this->mensaje = $this->mensaje . $producto->actualizaProducto($productoId, $cantidad, $tipoMovimiento, $unidadXpaquete);
            $this->mensaje = $this->mensaje . $movimiento->validaMovimiento($productoId, $fechaDocumento, $referencia, $fechaMovimiento, $empleadoId, $cantidad, $unidadXpaquete, $almacenId, $loteSerie, $opcion, $numeroMovimiento);
        } else if (($this->operacion) == "QUITAR_PRODUCTO") {

            $inventario = new inventario();


            $this->resultado = $inventario->quitarProducto(Input::POST('id'));
        } else if (($this->operacion) == "QUITAR_PARTIDA") {
            $clase = new clase_venta();
            $this->mensaje = $clase->quita_movimiento(Input::POST('linea'));
        } else if (($this->operacion) == "QUITAR_PARTIDA_PEDIDO") {
            $clase = new clase_venta();
            $this->mensaje = $clase->quita_movimiento_pedido(Input::POST('linea'));
        } else if (($this->operacion) == "QUITAR_PARTIDA_VENTA") {
            $clase = new clase_venta();
            $this->mensaje = $clase->quita_movimiento_venta(Input::POST('linea'));
        } else if (($this->operacion) == "MOSTRAR_LISTA") {
            
        } else if (($this->operacion) == "MOSTRAR_LISTA_VENTA") {
            
        }else if (($this->operacion) == "MOSTRAR_LISTA_ATICIPO") {
            
        } else if (($this->operacion) == "MOSTRAR_LISTA_VENTA_DOCTO") {
            
        } else if (($this->operacion) == "MOSTRAR_LISTA_SALIDA") {
            
        } else if (($this->operacion) == "BUSCA_ALMACEN") {

            $this->condicion = 'PRODUCTO_ID=' . Input::POST('productoId');
        } else if (($this->operacion) == "BUSCA_PRODUCTO") {

            $this->condicion = 'almacen_id=' . Input::POST('almacenId');
        } else if (($this->operacion) == "BUSCA_LOTE_PA") {
            $almacen = Input::POST('almacenId');
            $producto = Input::POST('productoId');
            $this->condicion = "dl.almacen_id='$almacen' and dl.producto_id='$producto' ";
        } else if (($this->operacion) == "BUSCA_LOTE_DO") {
            $almacen = Input::POST('almacenId');
            $producto = Input::POST('productoId');
            $this->linea = Input::POST('num');
            $this->condicion = "dl.almacen_id='$almacen' and dl.producto_id='$producto'";
        } else if (($this->operacion) == "INSERTA_LOTE") {
            $lote = Input::POST('lote');
            $linea = Input::POST('linea');
            $almacenId = Input::POST('almacen');
            $array_productos = Session::get('array_venta');
           $consultaExis=new detalle_lote();
        $lote=$consultaExis->find_first($lote);
        $lote=@$lote->lote_id;
        $loteD=new lote();
        $detalle_lote=$loteD->listarXid($lote);
            $linea = $linea - 1;
            $array_productos[$linea]['LOTE_CODIGO'] = $detalle_lote->codigo;
            $array_productos[$linea]['LOTE_SERIE'] = $lote;
            $array_productos[$linea]['ALMACEN_ID'] = $almacenId;
            $almacen = new almacen();
            $detalle_almacen = $almacen->listarXid($almacenId);
            $array_productos[$linea]['ALMACEN'] = $detalle_almacen->almacen;
            $array_productos[$linea]['DESCRIPCION_ALMACEN'] = $detalle_almacen->descripcion;



            Session::set('array_venta', $array_productos);
        } else if (($this->operacion) == "BUSCA_EXISTENCIA_PA") {
            $lote = Input::POST('lote');
            $existencia = new detalle_lote();
            $this->existencia = $existencia->find_first($lote);
        } else if (($this->operacion) == "BUSCA_EXISTENCIA_DO") {
            $lote = Input::POST('lote');
            $existencia = new detalle_lote();
            $this->existencia = $existencia->find_first($lote);
        } else if (($this->operacion) == "BUSCA_ALMACEN_SALIDA") {

            $this->condicion = 'PRODUCTO_ID=' . Input::POST('productoId');
        } else if (($this->operacion) == "MOSTRAR_DETALLE") {

            $productoId = Input::POST('productoId');
            $producto = new producto();
            $detalle = $producto->listarXid($productoId);
            if ($detalle->NUMERO_SERIE == "S") {
                $this->var = "serie";
                if ($detalle->numero_inventario == "S") {
                    $this->var = "serieInventario";
                }
            } else if ($detalle->lote == "S") {
                $this->var = "lote";
                if ($detalle->numero_inventario == "S") {
                    $this->var = "loteInventario";
                }
            } else {
                $this->var = "ninguno";
                if ($detalle->numero_inventario == "S") {
                    $this->var = "soloInventario";
                }
            }
        } else if (($this->operacion) == "MOSTRAR_DETALLE_SALIDA") {

            $this->productoId = Input::POST('productoId');
            $this->almacenId = Input::POST('almacenId');
            $producto = new producto();
            $this->detalle = $producto->listarXid($this->productoId);
        } else if (($this->operacion) == "BUSCA_CLIENTE") {

            $clienteId = Input::POST('clienteId');

            $this->cliente = Load::model('cliente')->buscaCliente($clienteId);
        } else if (($this->operacion) == "BUSCA_CATALOGO") {
            $this->empresaId = Input::POST('empresaId');
        } else if (($this->operacion) == "BUSCA_COSTO") {
            $valor = Input::POST('valor');
            $consulta = new costoEnvio();
            $this->valor = $consulta->find_first($valor);
        } else if (($this->operacion) == "BUSCA_CLIENTE_C") {

            $clienteId = Input::POST('clienteId');

            $this->cliente = Load::model('cliente')->buscaCliente($clienteId);
        } else if (($this->operacion) == "BUSCA_CLIENTE_P") {

            $clienteId = Input::POST('clienteId');

            $this->cliente = Load::model('cliente')->buscaCliente($clienteId);
        } else if (($this->operacion) == "BUSCA_CLIENTE_AN") {

            $clienteId = Input::POST('clienteId');

            $this->cliente = Load::model('cliente')->buscaCliente($clienteId);
        } else if (($this->operacion) == "BUSCA_EMPLEADO") {

            $empleadoId = Input::POST('empleadoId');

            $this->empleado = Load::model('empleado')->buscarEmpleado($empleadoId);
            $this->departamento = Load::model('departamento')->listarXid($this->empleado->DEPARTAMENTO_ID);
        } else if (($this->operacion) == "BUSCA_EXISTENCIA_GENERAL") {

            $productoId = Input::POST('productoId');

            $this->producto = Load::model('producto')->listarXid($productoId);
        } else if (($this->operacion) == "BUSCA_EXISTENCIA_ALMACEN") {

            $productoId = Input::POST('productoId');
            $almacenId = Input::POST('almacenId');
            $this->inventario = Load::model('inventario')->existenciaProducto($productoId, $almacenId);
        } else if (($this->operacion) == "BUSCA_EXISTENCIA_LS") {


            if ((Input::POST('opcion') == "lote") || (Input::POST('opcion') == "loteInventario")) {
                $loteId = Input::POST('loteId');
                $this->resultado = Load::model('lote')->listarXid($loteId);
            } else if ((Input::POST('opcion') == "serie") || (Input::POST('opcion') == "serieInventario")) {
                $serieId = Input::POST('serieId');
                $this->resultado = Load::model('serie')->listarXid($serieId);
            }
        }
        else if (($this->operacion) == "BUSCA_SERIE") {
            $valor = Input::POST('valor');
            $consulta = new series_folios();
            $valor = $consulta->find_first($valor);
            $this->valor=$valor->consecutivo +1;
        }else if (($this->operacion) == "QUITAR_LINEAE") {
            $clase = new detalle_cotizacion();
            $linea = Input::POST('linea');
            $clase->delete("id='$linea'");
        }else if (($this->operacion) == "MOSTRAR_LISTAE") {
            $clase = new detalle_cotizacion();
            $id = Input::POST('id');
            $detalleCompra = new detalle_cotizacion();
        $this->detalleCompra = $detalleCompra->listarProductoCompra($id);
        }else if (($this->operacion) == "TIPO_PRESENTACION") {
            $producto = new producto();
            $id = Input::POST('productoId');
            
        $this->presentacion = $producto->find_by_sql("select C.codigo codigo from producto P inner join calidad C on P.calidad_id=C.id where P.id='$id'");
        }
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

    public function envio($idVenta) {
        $this->venta = $idVenta;
       

        $envio = new envio();
        if (Input::hasPost('envio')) {

            $this->envio = $envio->guardarEnvio();
            Redirect::to('venta/listadoVenta');
        }
        $this->envio = $envio->find_first("venta_id='$idVenta'");
        $envio=$this->envio;
        $empresa=new empresa();
        $this->empresa=$empresa->find_first($envio->empresa_id);
    }

    public function datosFactura($idVenta) {
        $this->venta = $idVenta;
        View::template(NULL);

        $envio = new dato_factura();
        if (Input::hasPost('factura')) {

            $this->envio = $envio->guardarDatos();
           // Redirect::to('venta/listadoVenta');
        }
        $this->factura = $envio->find_first("venta_id='$idVenta'");
    }

    public function cupon($idVenta) {
        $this->venta = $idVenta;

        $envio = new envio();
        if (Input::hasPost('envio')) {

            $this->envio = $envio->guardarEnvio();
        }
        $this->envio = $envio->find_first("venta_id='$idVenta'");
    }

    public function reporteFactura() {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '-1');
        $consulta = new venta();
        $where="";
        if (Input::hasPost('fecha_inicial')) {
            $cliente_id = Input::Post('cliente_id');
            $fecha_inicial = Input::Post('fecha_inicial');
            $fecha_final = Input::Post('fecha_final');
            $condicion = Input::Post('condicion');
            $titulo = Input::Post('titulo');

            
            if ($cliente_id != "")
                $where.=" and cliente_id='" . $cliente_id . "'";
            if ($condicion != "")
                $where.=" and condicion='" . $condicion . "'";
            $this->resultado = $consulta->find_all_by_sql("select re.documento, date_format(re.fecha_documento,'%d-%m-%Y') fecha_documento, re.nombre, re.subtotal, IF(re.estado = '1', 'Activo', 'Cancelado' ) estado,re.iva,re.monto, DF.numero_factura, DF.fecha_emision,re.condicion,re.saldo from venta re  left join dato_factura DF  on re.id=DF.venta_id where "
                    . "(re.fecha_documento >='" . strftime('%Y-%m-%d', strtotime($fecha_inicial)) . "' and re.fecha_documento <='" . strftime('%Y-%m-%d', strtotime($fecha_final)) . "' ) " . $where);
            if (trim($titulo) != "") {

                $repRelacionCobros = $this->resultado;
                session::set("repRelacionCobros", $repRelacionCobros);
                ?>
                <script>
                    window.open('reporteFacturaPdf/<?php echo $fecha_inicial . '/' . $fecha_final . '/' . $titulo ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
                </script>
                <?php
            }
        }
    }

    public function reporteFacturaPdf($fecha_inicial, $fecha_final, $titulo) {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '-1');
        Load::lib('tcpdf');
        $this->etiquetaFecha = "( " . $fecha_inicial . " - " . $fecha_final . " )";

        $this->titulo = $titulo;
        $resultado = session::get("repRelacionCobros");
        $this->resultado = $resultado;
    }

    public function reportePedidoF() {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '-1');
        $consulta = new venta();
        if (Input::hasPost('fecha_inicial')) {
            $cliente_id = Input::Post('cliente_id');
            $fecha_inicial = Input::Post('fecha_inicial');
            $fecha_final = Input::Post('fecha_final');
            $titulo = Input::Post('titulo');

            $where = "";
            if ($cliente_id != "")
                $where.=" and re.cliente_id='" . $cliente_id . "'";
            $this->resultado = $consulta->find_all_by_sql("select re.documento, date_format(re.fecha_documento,'%d-%m-%Y') fecha_documento, re.nombre, re.subtotal, IF(re.estado = '1', 'Activo', 'Cancelado' ) estado,re.iva,re.monto,re.orden_compra,re.venta from pedido re inner join cliente co on re.cliente_id=co.id where "
                    . "(re.fecha_documento >='" . strftime('%Y-%m-%d', strtotime($fecha_inicial)) . "' and re.fecha_documento <='" . strftime('%Y-%m-%d', strtotime($fecha_final)) . "' ) " . $where);
            if (trim($titulo) != "") {

                $repRelacionCobros = $this->resultado;
                session::set("repRelacionCobros", $repRelacionCobros);
                ?>
                <script>
                    window.open('reportePedidoPdf/<?php echo $fecha_inicial . '/' . $fecha_final . '/' . $titulo ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
                </script>
                <?php
            }
        }
    }

    public function reportePedidoPdf($fecha_inicial, $fecha_final, $titulo) {
        Load::lib('tcpdf');
        $this->etiquetaFecha = "( " . $fecha_inicial . " - " . $fecha_final . " )";

        $this->titulo = $titulo;
        $resultado = session::get("repRelacionCobros");
        $this->resultado = $resultado;
    }

    public function reporteCotizacionF() {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '-1');
        $consulta = new venta();
        if (Input::hasPost('fecha_inicial')) {
            $cliente_id = Input::Post('cliente_id');
            $fecha_inicial = Input::Post('fecha_inicial');
            $fecha_final = Input::Post('fecha_final');
            $titulo = Input::Post('titulo');

            $where = "";
            if ($cliente_id != "")
                $where.=" and re.cliente_id='" . $cliente_id . "'";
            $this->resultado = $consulta->find_all_by_sql("select re.documento, date_format(re.fecha_documento,'%d-%m-%Y') fecha_documento, re.nombre, re.subtotal, IF(re.estado = '1', 'Activo', 'Cancelado' ) estado,re.iva,re.monto from cotizacion re inner join cliente co on re.cliente_id=co.id where "
                    . "(re.fecha_documento >='" . strftime('%Y-%m-%d', strtotime($fecha_inicial)) . "' and re.fecha_documento <='" . strftime('%Y-%m-%d', strtotime($fecha_final)) . "' ) " . $where);
            if (trim($titulo) != "") {

                $repRelacionCobros = $this->resultado;
                session::set("repRelacionCobros", $repRelacionCobros);
                ?>
                <script>
                    window.open('reporteCotizacionPdf/<?php echo $fecha_inicial . '/' . $fecha_final . '/' . $titulo ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
                </script>
                <?php
            }
        }
    }

    public function reporteCotizacionPdf($fecha_inicial, $fecha_final, $titulo) {
        Load::lib('tcpdf');
        $this->etiquetaFecha = "( " . $fecha_inicial . " - " . $fecha_final . " )";

        $this->titulo = $titulo;
        $resultado = session::get("repRelacionCobros");
        $this->resultado = $resultado;
    }

     public function concentradoVentas() {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '-1');
        $consulta = new venta();
        if (Input::hasPost('fecha_inicial')) {
            $codigo = Input::Post('codigo');
            $fecha_inicial = Input::Post('fecha_inicial');
            $fecha_final = Input::Post('fecha_final');
            $condicion= " where V.fecha_recepcion >='$fecha_inicial' and V.fecha_recepcion <='$fecha_final' ";
            if(trim($codigo)!=''){
               $condicion = $condicion." and DV.lote='$codigo' ";  
            }
            $condicion = $condicion." and V.estado='1' "; 
            $this->resultado = $consulta->find_all_by_sql("select V.fecha_recepcion, DV.lote, SUM(DV.cantidad) cantidad, P.descripcion, PR.descripcion preparacion, P.clave,DV.pieza,DV.presentacion_venta,sum(DV.total) total,V.origen from venta V INNER join detalle_venta DV on V.id=DV.venta_id INNER JOIN producto P on DV.producto_id=P.id inner join presentacion PR on P.presentacion_id=PR.id $condicion GROUP by V.fecha_recepcion, DV.lote,P.clave, PR.descripcion ORDER BY V.fecha_recepcion ASC");
            
        }
    }
    public function resumenVM() {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '-1');
        $consulta = new venta();
        if (Input::hasPost('fecha_inicial')) {
            
            $fecha_inicial = Input::Post('fecha_inicial');
            $fecha_final = Input::Post('fecha_final');
            $lote = Input::Post('codigoS');
            $almacen = Input::Post('almacen_id');
            $condicion= " where MI.fecha_movimiento >='$fecha_inicial' and MI.fecha_movimiento <='$fecha_final' ";
            if(trim($lote)!=''){
               $condicion = $condicion." and MI.lote_id='$lote' ";  
            }
            if(trim($almacen)!=''){
               $condicion = $condicion." and MI.almacen_id='$almacen' ";  
            }
           
            $recepcion = $consulta->find_all_by_sql("select P.id,P.clave,concat_ws(', ',P.descripcion,pre.descripcion,c.descripcion,pr.descripcion) descripcion, SUM(CASE WHEN CM.tipo_movimiento = 'E' THEN MI.cantidad ELSE 0 END) entrada,SUM(CASE WHEN CM.tipo_movimiento = 'S' THEN MI.cantidad ELSE 0 END) cancelacion FROM movimiento_inventario MI inner join producto P on MI.producto_id=P.id left join preparacion pr on pr.id=P.preparacion_id left join presentacion pre on pre.id=P.presentacion_id left join calidad c on P.calidad_id=c.id INNER JOIN conceptomovimiento CM on MI.tipo_movimiento=CM.id $condicion and (MI.tipo_movimiento='2' || MI.tipo_movimiento='23')  GROUP by P.id order by P.id asc");
            $traspaso = $consulta->find_all_by_sql("select P.id,P.clave,concat_ws(', ',P.descripcion,pre.descripcion,c.descripcion,pr.descripcion) descripcion, SUM(CASE WHEN CM.tipo_movimiento = 'E' THEN MI.cantidad ELSE 0 END) entrada,SUM(CASE WHEN CM.tipo_movimiento = 'S' THEN MI.cantidad ELSE 0 END) salida FROM movimiento_inventario MI inner join producto P on MI.producto_id=P.id left join preparacion pr on pr.id=P.preparacion_id left join presentacion pre on pre.id=P.presentacion_id left join calidad c on P.calidad_id=c.id INNER JOIN conceptomovimiento CM on MI.tipo_movimiento=CM.id $condicion and (MI.tipo_movimiento='7' || MI.tipo_movimiento='18')   GROUP by P.id order by P.id asc" );
            $entradaT = $consulta->find_all_by_sql("select P.id,P.clave,concat_ws(', ',P.descripcion,pre.descripcion,c.descripcion,pr.descripcion) descripcion, SUM(CASE WHEN CM.tipo_movimiento = 'E' THEN MI.cantidad ELSE 0 END) entrada,SUM(CASE WHEN CM.tipo_movimiento = 'S' THEN MI.cantidad ELSE 0 END) cancelacion FROM movimiento_inventario MI inner join producto P on MI.producto_id=P.id left join preparacion pr on pr.id=P.preparacion_id left join presentacion pre on pre.id=P.presentacion_id left join calidad c on P.calidad_id=c.id INNER JOIN conceptomovimiento CM on MI.tipo_movimiento=CM.id $condicion and (MI.tipo_movimiento='3' || MI.tipo_movimiento='17')   GROUP by P.id order by P.id asc ");
            $salidaT = $consulta->find_all_by_sql("select P.id,P.clave,concat_ws(', ',P.descripcion,pre.descripcion,c.descripcion,pr.descripcion) descripcion, SUM(CASE WHEN CM.tipo_movimiento = 'E' THEN MI.cantidad ELSE 0 END) entrada,SUM(CASE WHEN CM.tipo_movimiento = 'S' THEN MI.cantidad ELSE 0 END) cancelacion FROM movimiento_inventario MI inner join producto P on MI.producto_id=P.id left join preparacion pr on pr.id=P.preparacion_id left join presentacion pre on pre.id=P.presentacion_id left join calidad c on P.calidad_id=c.id INNER JOIN conceptomovimiento CM on MI.tipo_movimiento=CM.id $condicion and (MI.tipo_movimiento='12' || MI.tipo_movimiento='4')   GROUP by P.id order by P.id asc");    
            $venta = $consulta->find_all_by_sql("select P.id,P.clave,concat_ws(', ',P.descripcion,pre.descripcion,c.descripcion,pr.descripcion) descripcion, SUM(CASE WHEN CM.tipo_movimiento = 'E' THEN MI.cantidad ELSE 0 END) cancelacion,SUM(CASE WHEN CM.tipo_movimiento = 'S' THEN MI.cantidad ELSE 0 END) venta FROM movimiento_inventario MI inner join producto P on MI.producto_id=P.id left join preparacion pr on pr.id=P.preparacion_id left join presentacion pre on pre.id=P.presentacion_id left join calidad c on P.calidad_id=c.id INNER JOIN conceptomovimiento CM on MI.tipo_movimiento=CM.id $condicion and (MI.tipo_movimiento='11' || MI.tipo_movimiento='16')   GROUP by P.id order by P.id asc");
            $arreglo[][]="";
            foreach ($recepcion as $datos){
               $arreglo[$datos->id][1]=$datos->descripcion." - ".$datos->descripcion;
               $arreglo[$datos->id][2]=$datos->entrada-$datos->cancelacion;
            }
            foreach ($traspaso as $datos){
               $arreglo[$datos->id][1]=$datos->descripcion." - ".$datos->descripcion;
               $arreglo[$datos->id][3]=$datos->entrada-$datos->salida;
            }
            foreach ($entradaT as $datos){
               $arreglo[$datos->id][1]=$datos->descripcion." - ".$datos->descripcion;
               $arreglo[$datos->id][4]=$datos->entrada-$datos->salida;
            }
            foreach ($salidaT as $datos){
               $arreglo[$datos->id][1]=$datos->descripcion." - ".$datos->descripcion;
               $arreglo[$datos->id][5]=$datos->entrada-$datos->salida;
            }
            foreach ($venta as $datos){
               $arreglo[$datos->id][1]=$datos->descripcion." - ".$datos->descripcion;
               $arreglo[$datos->id][6]=$datos->venta-$datos->cancelacion;
            }
            sort($arreglo);
            $this->arreglo=$arreglo;
        }
    }
    public function cancelaAnticipo($id) {
        $conexion=new anticipo();
        $datos=$conexion->find_first($id);
        $datos->estatus='0';
        $datos->update();
        Redirect::to('venta/listadoAnticipo');
    }
    public function aplicaAnticipo($id) {
        $this->accion="aplicaAnticipo";
        $conexion=new anticipo();
        $this->anticipo=$conexion->find_first($id);
        if (Input::hasPost('anticipo')) {
           
           $producto = new venta();
           $recibosCobro = new recibos_cobros();
           $listar = $producto->listarPendiente($this->anticipo->cliente_id);
           $suma=0;
           $monto=$this->anticipo->importe - $this->anticipo->importe_aplicado;
           foreach ($listar as $dato){
               if(((Input::Post('aplicar'.$dato->id))>0) and ($suma <=$monto)){
               
                 $guardaRC=new recibos_cobros();
                 $guardaRC->recibos_id=$dato->id;
                 $guardaRC->cobros_id=$this->anticipo->cobro_id;
                 $guardaRC->cliente_id=$this->anticipo->cliente_id;
                 $guardaRC->monto=Input::Post('aplicar'.$dato->id);
                 $guardaRC->estatus='1';
                 $guardaRC->save();
                 $actualiza=$producto->find_first($dato->id);
                 $actualiza->saldo=$actualiza->saldo - Input::Post('aplicar'.$dato->id);
                 $actualiza->update();
                 $suma=$suma + Input::Post('aplicar'.$dato->id);
               }
           }
           $conexion=new anticipo();
           $anticipo=$conexion->find_first($this->anticipo->id);
           $anticipo->importe_aplicado=$anticipo->importe_aplicado + $suma;
           $anticipo->save();
          Redirect::to('venta/listadoAnticipo');
        }
        
        $producto = new venta();
           $this->listar = $producto->listarPendiente($this->anticipo->cliente_id);
        
    }
    public function cancelarVenta($valeId) {
        View::select(NULL);
        $movimiento='16';
        $vale = new venta();
        $consulta =$vale->find_first($valeId);
        $consulta->estado='0';
         $consulta->fecha_cancelacion=date('Y-m-d');
        $consulta->update();
        $movimientos=new  movimiento_inventario();
       $movimientos->cancelarMovimiento($consulta->documento,$movimiento);
          Redirect::to('venta/listadoVenta');  
        
    }
    public function cancelarCotizacion($valeId) {
        View::select(NULL);
       
        $vale = new cotizacion();
        $consulta =$vale->find_first($valeId);
        $consulta->estado='0';
         $consulta->fecha_cancelacion=date('Y-m-d');
        $consulta->update();
        
          Redirect::to('venta/listadoCotizacion');  
        
    }
    public function cancelarPedido($valeId) {
        View::select(NULL);
        $vale = new pedido();
        $consulta =$vale->find_first($valeId);
        $consulta->estado='0';
         $consulta->fecha_cancelacion=date('Y-m-d');
        $consulta->update();
        
          Redirect::to('venta/listadoPedido');  
        
    }
    public function correguirSaldo() {
        View::select(NULL);
        $vale = new venta();
        $consulta =$vale->find("condicion='C'");
        
        
    }
    
}
?>

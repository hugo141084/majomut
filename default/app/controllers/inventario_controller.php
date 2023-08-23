<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class inventarioController extends AppController {

    /**
     * Muestra el partial de las nominas generadas
     */
    public function listadoAlmacen() {
        $almacen = new almacen();
        $this->producto = $almacen->find();

        if (Input::hasPost('almacen')) {

            $this->almacen = $almacen->guardarDatos();
        }
        $this->result = $almacen->listar();
        $this->campos = array(
            utf8_encode('#') => 'id',
            utf8_encode('CLAVE') => 'almacen',
            utf8_encode('DESCRIPCION') => 'descripcion',
            utf8_encode('TIPO') => 'tipo'
        );
        $this->encabezado = "ALMACEN";
    }

    public function editarAlmacen($id) {


        $almacen = new almacen();
        $this->almacen = $almacen->find_first($id);

        if (Input::hasPost('almacen')) {

            $this->almacen = $almacen->actualizarDatos();
            Redirect::to('inventario/listadoAlmacen');
        }
    }

    public function eliminarAlmacen($id) {
        $almacen = new almacen();
        $almacen = $almacen->find_first($id);
        $almacen->estatus = '0';
        $almacen->update();
        Redirect::to('inventario/listadoAlmacen');
    }

    public function listadoRecepcion() {
        $ciclocosechaId = Session::get('cicloCosecha');
        if($ciclocosechaId < 1) Redirect::to('ciclo/seleccionar');
        $vale = new vale();
        $this->result = $vale->listarEntrada();
        $this->campos = array(
            utf8_encode('#') => 'id',
            utf8_encode('TIPO DOCUMENTO') => 'tipo_documento',
            utf8_encode('DOCUMENTO') => 'documento',
            utf8_encode('F. DOCUMENTO') => 'fecha_documento',
            utf8_encode('F. RECEPCION') => 'fecha_recepcion',
            utf8_encode('ESTADO') => 'estado'
        );
        $this->encabezado = "RECEPCIÓN DE  CAFE ORO VERDE";
    }
    public function cancelarRecepcion($valeId) {
        View::select(NULL);
        $movimiento='23';
        $vale = new vale();
        $consulta =$vale->find_first($valeId);
        $consulta->estado='Cancelado';
         $consulta->fecha_cancelacion=date('Y-m-d');
        $consulta->update();
        $movimientos=new  movimiento_inventario();
       $movimientos->cancelarMovimiento($consulta->documento,$movimiento);
          Redirect::to('inventario/listadoRecepcion');  
        
    }
    public function cancelarSalidaT($valeId) {
        View::select(NULL);
        $movimiento='4';
        $vale = new vale();
        $consulta =$vale->find_first($valeId);
        $consulta->estado='Cancelado';
         $consulta->fecha_cancelacion=date('Y-m-d');
        $consulta->update();
        $movimientos=new  movimiento_inventario();
       $movimientos->cancelarMovimiento($consulta->documento,$movimiento);
          Redirect::to('inventario/listadoSalidaCT');  
        
    }
    public function cancelarEntradaT($valeId) {
        View::select(NULL);
        $movimiento='17';
        $vale = new vale();
        $consulta =$vale->find_first($valeId);
        $consulta->estado='Cancelado';
         $consulta->fecha_cancelacion=date('Y-m-d');
        $consulta->update();
        $movimientos=new  movimiento_inventario();
       $movimientos->cancelarMovimiento($consulta->documento,$movimiento);
          Redirect::to('inventario/listadoEntradaCT');  
        
    }
    public function cancelarMovimiento($valeId) {
        View::select(NULL);
        
        $vale = new vale();
        $consulta =$vale->find_first($valeId);
        $consulta->estado='Cancelado';
         $consulta->fecha_cancelacion=date('Y-m-d');
        $consulta->update();
        $movimientos=new  movimiento_inventario();
       $movimientos->cancelarMovimientoM($consulta->documento);
          Redirect::to('inventario/listadoSTraspaso');  
        
    }
    public function listadoCombinacion() {
        $ciclocosechaId = Session::get('cicloCosecha');
        if($ciclocosechaId < 1) Redirect::to('ciclo/seleccionar');
        $vale = new vale();
        $this->result = $vale->listarCombinacion();
        $this->campos = array(
            utf8_encode('#') => 'id',
            utf8_encode('TIPO DOCUMENTO') => 'tipo_documento',
            utf8_encode('DOCUMENTO') => 'documento',
            utf8_encode('F. MOVIMIENTO') => 'fecha_documento',
            utf8_encode('ESTADO') => 'estado'
        );
        $this->encabezado = "LISTADO COMBINACION DE LOTES";
    }
    public function listadoLotes() {
        $vale = new detalle_lote();
        $this->result = $vale->find_all_by_sql("select * from detalle_lote DL inner join lote L on DL.lote_id=L.id inner join producto P on DL.producto_id=P.id inner join almace A on DV.almacen_id=A.id");
        
    }

    public function listadoSTraspaso() {
        $ciclocosechaId = Session::get('cicloCosecha');
        if($ciclocosechaId < 1) Redirect::to('ciclo/seleccionar');
        $vale = new vale();
        $this->result = $vale->listarTraspaso();
        $this->campos = array(
            utf8_encode('#') => 'id',
            utf8_encode('TIPO DOCUMENTO') => 'tipo_documento',
            utf8_encode('DOCUMENTO') => 'documento',
            utf8_encode('F. MOVIMIENTO') => 'fecha_documento',
            utf8_encode('ESTADO') => 'estado'
        );
        $this->encabezado = "TRASPASO ENTRE ALMACENES (Salidas)";
    }
    public function listadoETraspaso() {
        $ciclocosechaId = Session::get('cicloCosecha');
        if($ciclocosechaId < 1) Redirect::to('ciclo/seleccionar');
        $vale = new vale();
        $this->result = $vale->listarTraspaso();
        $this->campos = array(
            utf8_encode('#') => 'id',
            utf8_encode('TIPO DOCUMENTO') => 'tipo_documento',
            utf8_encode('DOCUMENTO') => 'documento',
            utf8_encode('F. MOVIMIENTO') => 'fecha_documento',
            utf8_encode('ESTADO') => 'estado'
        );
        $this->encabezado = "TRASPASO ENTRE ALMACENES (Entradas)";
    }
    public function listadoMovimiento() {
         $ciclocosechaId = Session::get('cicloCosecha');
        if($ciclocosechaId < 1) Redirect::to('ciclo/seleccionar');
        $vale = new vale();
        $this->result = $vale->listarMovimiento();
        $this->campos = array(
            utf8_encode('#') => 'id',
            utf8_encode('TIPO DOCUMENTO') => 'tipo_documento',
            utf8_encode('DOCUMENTO') => 'documento',
            utf8_encode('F. MOVIMIENTO') => 'fecha_documento',
            utf8_encode('ESTADO') => 'estado'
        );
        $this->encabezado = "LISTADO DE MOVIMIENTOS AL INVENTARIO";
    }

    public function listadoSalidaCT() {
        $ciclocosechaId = Session::get('cicloCosecha');
        if($ciclocosechaId < 1) Redirect::to('ciclo/seleccionar');
        $vale = new vale();
        $this->result = $vale->listarSalidaCT();
        $this->campos = array(
            utf8_encode('#') => 'id',
            utf8_encode('TIPO DOCUMENTO') => 'tipo_documento',
            utf8_encode('DOCUMENTO') => 'documento',
            utf8_encode('F. DOCUMENTO') => 'fecha_documento',
            utf8_encode('F. RECEPCION') => 'fecha_salida',
            utf8_encode('ESTADO') => 'estado'
        );
        $this->encabezado = "SALIDA DE  CAFE PARA TOSTADO";
    }

    public function listadoEntradaCT() {
        $ciclocosechaId = Session::get('cicloCosecha');
        if($ciclocosechaId < 1) Redirect::to('ciclo/seleccionar');
        $vale = new vale();
        $this->result = $vale->listarEntradaCT();
        $this->campos = array(
            utf8_encode('#') => 'id',
            utf8_encode('TIPO DOCUMENTO') => 'tipo_documento',
            utf8_encode('DOCUMENTO') => 'documento',
            utf8_encode('F. DOCUMENTO') => 'fecha_documento',
            utf8_encode('F. RECEPCION') => 'fecha_salida',
            utf8_encode('ESTADO') => 'estado'
        );
        $this->encabezado = "ENTRADA DE CAFE TOSTADO";
    }

    public function listadoSalida() {
        $entrega = new entrega();
        $this->result = $entrega->listarEntrega();
        $this->campos = array(
            utf8_encode('#') => 'ID',
            utf8_encode('TIPO DOCUMENTO') => 'tipo_documento',
            utf8_encode('SOLICITANTE') => 'nombre',
            utf8_encode('F. SOLICITUD') => 'fecha_solicitud',
            utf8_encode('F. ENTREGA') => 'fecha_entrega',
            utf8_encode('DESTINATARIO') => 'descripcion',
            utf8_encode('ESTADO') => 'estado'
        );
        $this->encabezado = "ENTREGA DE PRODUCTOS";
    }

    public function crearEntradaCT() {
        $this->accion = "crearEntradaCT";

        $vale = new vale();
        $this->vale = $vale->find();

        if (Input::hasPost('vale')) {
            $array_productos = Session::get('array_productos');
            $num = count($array_productos);

            if ($num == "0") {

                echo "<script>  alert ('Primero agrege partidas para poder guardar este movimiento....!');</script>";
            } else {
                $this->vale = $vale->guardarDatosECT();
                ?>
                <script>
                    window.open('valeEntradaCT/<?php echo $this->vale; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
                </script>
                <?php
                Input::delete();
                Redirect::to('inventario/crearEntradaCT');
            }
        } else {
            session::delete('array_productos');
        }
        Input::delete();
        $folios = new series_folios();
        $datoFolios = $folios->find_first("tipo = 'VALEET'");

        $this->num = "ET-" . str_pad(($datoFolios->consecutivo) + 1, 4, "0", STR_PAD_LEFT);
    }

    public function crearSalidaCT() {
        $ciclocosechaId = Session::get('cicloCosecha');
        if($ciclocosechaId < 1) Redirect::to('ciclo/seleccionar');
        $this->accion = "crearSalidaCT";

        $vale = new vale();
        $this->vale = $vale->find();

        if (Input::hasPost('vale')) {
            $array_productos = Session::get('array_productos');
            $num = count($array_productos);

            if ($num == "0") {

                echo "<script>  alert ('Primero agrege partidas para poder guardar este movimiento....!');</script>";
            } else {
                $this->vale = $vale->guardarDatosSCT();
                ?>
                <script>
                    window.open('valeSalidaCT/<?php echo $this->vale; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
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
        $datoFolios = $folios->find_first("tipo = 'VALET'");

        $this->num = "ST-" . str_pad(($datoFolios->consecutivo) + 1, 4, "0", STR_PAD_LEFT);
    }

    public function crearTraspaso() {
        $this->accion = "crearTraspaso";

        $vale = new vale();
        $this->vale = $vale->find();

        if (Input::hasPost('traspaso')) {
            $array_productos = Session::get('array_productos');
            $num = count($array_productos);

            if ($num == "0") {

                echo "<script>  alert ('Primero agrege partidas para poder guardar este movimiento....!');</script>";
            } else {
                $this->vale = $vale->guardarDatosTSEA();
                ?>
                <script>
                    window.open('valeMovimiento/<?php echo $this->vale; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
                </script>
                <?php
                Input::delete();
                // Redirect::to('inventario/crearSalidaCT');
            }
        } else {
            session::delete('array_productos');
        }
        Input::delete();
        $folios = new series_folios();
        $datoFolios = $folios->find_first("tipo = 'VALE'");

        $this->num = "V-" . str_pad(($datoFolios->consecutivo) + 1, 4, "0", STR_PAD_LEFT);
    }
    public function ingresarTraspaso($id) {
        
        $vale = new vale();
        
 
        
            if ($id == "") {

                echo "<script>  alert ('Seleccione un registro valido ....!');</script>";
            } else {
               $this->vale = $vale->guardarDatosTSEN($id);
                ?>
                <script>
                    window.open('valeMovimiento/<?php echo $id; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
                </script>
                <?php
                Input::delete();
                 Redirect::to('inventario/listadoETraspaso');
            }
        
    }

    public function crearMovimiento() {
        $this->accion = "crearMovimiento";

        $vale = new vale();
        $this->vale = $vale->find();
        //  $this->concepto=new concepto_movimiento();
        if (Input::hasPost('traspaso')) {
            $array_productos = Session::get('array_productos');
            $num = count($array_productos);

            if ($num == "0") {

                echo "<script>  alert ('Primero agrege partidas para poder guardar este movimiento....!');</script>";
            } else {
                $movimiento = Input::Post('destino_id');
                $this->vale = $vale->guardarAjuste($movimiento);
                ?>
                <script>
                    window.open('valeSalidaAJ/<?php echo $this->vale; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
                </script>
                <?php
                Input::delete();
                // Redirect::to('inventario/crearSalidaCT');
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


        if (Input::hasPost('vale')) {
            $array_productos = Session::get('array_productos');
            $num = count($array_productos);

            if ($num == "0") {

                echo "<script>  alert ('Primero agrege partidas para poder guardar este movimiento....!');</script>";
            } else {
                $this->vale = $vale->guardarDatosSCT();
                ?>
                <script>
                    window.open('valeSalidaCT/<?php echo $this->vale; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
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

    public function crearCombinacion() {
        $this->accion = "crear";

        $almacen = new almacen();
        $this->producto = $almacen->find();

        if ((Input::Post('cantidadS')) || (Input::Post('num'))) {

            if (((Input::Post('cantidadS')) > 0) && ((Input::Post('num')) > 0)) {
                $i = Input::Post('num');
                ((Input::Post('cantidadS') > 0) ? $cantidadS = Input::Post('cantidadS') : $cantidadS = 0);
                $total = 0;
                for ($j = 1; $j < $i; $j++) {
                    $total +=Input::Post('cantidad' . $j);
                }

                if ($total == $cantidadS) {
                    $folios = new series_folios();
                    $folios->incrementarConsecutivo('VALECL');
                    $datoFolios = $folios->find_first("tipo = 'VALECL'");
                    $valeM = "V-" . str_pad(($datoFolios->consecutivo), 4, "0", STR_PAD_LEFT);

                    $fechaDocumento = date('Y-m-d');
                    $fechaMovimiento = date('Y-m-d');
                    $referencia = $valeM;
                    $proveedorId = "";
                    $vale = new vale();
                    $vale->tipo = 'CL';
                    $vale->tipo_documento = 'COMBINACION';
                    $vale->documento = $valeM;
                    $vale->estado = 'Activo';
                    $vale->observacion = Input::Post('observacion');
                    $vale->valido = Input::Post('valido');
                    $vale->fecha_documento = date('Y-m-d');
                    $vale->fecha_recepcion = date('Y-m-d');
                    $vale->fecha_salida = date('Y-m-d');
                    $vale->ciclocosecha_id=$ciclocosechaId; 
                    $vale->usuario_id = Session::get('id');
                    $vale->save();
                    $valeId = $vale->id;
                    $fechaCaducidad = "";
                    $almacen = Input::Post('combinacion');
                    $producto = Input::Post('venta');
                    $almacenId = $almacen['almacen_id'];
                    $productoId = $producto['producto_id'];
                    $fechaMovimiento = date("Y-m-d");
                    $fechaDocumento = date("Y-m-d");
                    $referencia = $valeM;
                    $proveedorId = "0";
                    $opcion = "lote";
                    $unidadXpaquete = "1";
                    $num_inventario = "";
                    $movimiento = new movimiento_inventario();
                    $producto = new producto();
                    $inventario = new inventario();
                    for ($j = 1; $j < $i; $j++) {
                        $detalleCompra = new detalle_vale();
                        $loteSerie = Input::Post('lote' . $j);
                        $cantidad = Input::Post('cantidad' . $j);
                        $tipoMovimiento = "S";
                        $numeroMovimiento = 21;
                        $detalleCompra->guardarDatos($valeId, $productoId, $cantidad, $unidadXpaquete, $almacenId, $loteSerie, $fechaCaducidad, $num_inventario);
                        $inventario->actualizaInventario($productoId, $cantidad, $tipoMovimiento, $unidadXpaquete, $almacenId);
                        $producto->actualizaProducto($productoId, $cantidad, $tipoMovimiento, $unidadXpaquete);
                        $movimiento->validaMovimiento($productoId, $fechaDocumento, $referencia, $fechaMovimiento, $proveedorId, $cantidad, $unidadXpaquete, $almacenId, $loteSerie, $opcion, $numeroMovimiento, $num_inventario);
                    }
                    $loteSerie = Input::Post('lote');
                    $cantidad = Input::Post('cantidadS');
                    $tipoMovimiento = "E";
                    $numeroMovimiento = 22;
                    $detalleCompra = new detalle_vale();
                    $detalleCompra->guardarDatos($valeId, $productoId, $cantidad, $unidadXpaquete, $almacenId, $loteSerie, $fechaCaducidad, $num_inventario);
                    $inventario->actualizaInventario($productoId, $cantidad, $tipoMovimiento, $unidadXpaquete, $almacenId);
                    $producto->actualizaProducto($productoId, $cantidad, $tipoMovimiento, $unidadXpaquete);
                    $movimiento->validaMovimiento($productoId, $fechaDocumento, $referencia, $fechaMovimiento, $proveedorId, $cantidad, $unidadXpaquete, $almacenId, $loteSerie, $opcion, $numeroMovimiento, $num_inventario);
                    ?>

                    <script>
                        window.open('valeCombinacion/<?php echo $valeId; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
                    </script>

                    <?php
                    Input::delete();
                } else {
                    $this->mensaje = "La cantidad de Lotes y Existencias no coincide con lo(s) nuevos Lotes y Existencias" . $total . "-" . $cantidadS;
                }
            }
        } else {
            $this->mensaje = "Complemente todos los datos antes de  presionar el boton de guardar ";
        }
        $folios = new series_folios();
        $datoFolios = $folios->find_first("tipo = 'VALECL'");

        $this->num = "CL-" . str_pad(($datoFolios->consecutivo) + 1, 4, "0", STR_PAD_LEFT);
    }

    public function inventarioInicial() {
        $this->accion = "inventarioInicial";
    }

    public function crearRecepcion() {
        $ciclocosechaId = Session::get('cicloCosecha');
        if($ciclocosechaId < 1) Redirect::to('ciclo/seleccionar');
        $this->accion = "crearRecepcion";

        $vale = new vale();
        $this->vale = $vale->find();

        if (Input::hasPost('vale')) {
            $array_productos = Session::get('array_productos');
            $num = count($array_productos);

            if ($num == "0") {

                echo "<script>  alert ('Primero agrege partidas para poder guardar este movimiento....!');</script>";
            } else {
                $this->vale = $vale->guardarDatos();
                ?>
                <script>
                    window.open('valeEntrada/<?php echo $this->vale; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
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
        $datoFolios = $folios->find_first("tipo = 'VALEV'");

        $this->num = "CV-" . str_pad(($datoFolios->consecutivo) + 1, 4, "0", STR_PAD_LEFT);
    }

    public function inventarioIni() {

        $vale = new vale();
        $vale = $vale->count();
        if ($vale >= 1) {
            Flash::info("<strong>" . Session::get('.') . "Ya se ha generado un evento de -INVENTARIO INICIAL-, puede realizar un movimiento de ajuste al inventario" . "<strong>");

            Redirect::to('principal/principal');
        }

        $this->accion = "inventarioIni";

        $vale = new vale();
        $this->vale = $vale->find();

        if (Input::hasPost('vale')) {
            $array_productos = Session::get('array_productos');
            $num = count($array_productos);

            if ($num == "0") {

                echo "<script>  alert ('Primero agrege partidas para poder guardar este movimiento....!');</script>";
            } else {
                $this->vale = $vale->guardarDatosIni();
                ?>
                <script>
                    window.open('valeInicial/<?php echo $this->vale; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
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
            window.open('valeEntrada/<?php echo $id; ?>', 'Imprimir', '_blank', 'titlebar=0,toolbar=location,menubar=0,width=600,height=800');
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

    public function valeInicial($compraId) {
        $compra = new vale();
        $this->datosCompra = $compra->listarXid($compraId);
        $datosCompra = $this->datosCompra;
        $detalleCompra = new detalle_vale();
        $this->detalleCompra = $detalleCompra->listarProductoCompra($compraId);
        $usuario = new usuario();
        $destino = new almacen();
        $this->destino = $destino->destino($compraId);
        $ciclo = new lote();
        $this->ciclo = $ciclo->ciclo($compraId);


        // $this->datosUsuario=$usuario->listarEmpleado($this->datosCompra->USUARIO_ID);
    }

    public function valeEntrada($compraId) {
        $compra = new vale();
        $this->datosCompra = $compra->listarXid($compraId);
        $detalleCompra = new detalle_vale();
        $this->detalleCompra = $detalleCompra->listarProductoCompra($compraId);
        $usuario = new usuario();
        $destino = new almacen();
        $this->destino = $destino->destino($compraId);
        $ciclo = new lote();
        $this->ciclo = $ciclo->ciclo($compraId);
        // $this->datosUsuario=$usuario->listarEmpleado($this->datosCompra->USUARIO_ID);
    }

    public function valeSalidaVenta($compraId) {
        $compra = new venta();
        $this->datosCompra = $compra->listarXid($compraId);
        $detalleCompra = new movimiento_inventario();
        $this->detalleCompra = $detalleCompra->listarVentaVale($this->datosCompra->documento);
        $usuario = new usuario();
        $destino = new almacen();
        $this->destino = $destino->destino($compraId);
        $ciclo = new lote();
        $this->ciclo = $ciclo->ciclo($compraId);
        // $this->datosUsuario=$usuario->listarEmpleado($this->datosCompra->USUARIO_ID);
    }

    public function valeSalidaCT($compraId) {
        $compra = new vale();
        $this->datosCompra = $compra->listarXid($compraId);
        $detalleCompra = new detalle_vale();
        $this->detalleCompra = $detalleCompra->listarProductoCompra($compraId);
        $usuario = new usuario();
        $destino = new almacen();
        $this->destino = $destino->destino($compraId);
        $ciclo = new lote();
        $this->ciclo = $ciclo->ciclo($compraId);
        // $this->datosUsuario=$usuario->listarEmpleado($this->datosCompra->USUARIO_ID);
    }

    public function valeSalidaAJ($compraId) {
        $compra = new vale();
        $this->datosCompra = $compra->listarXid($compraId);
        $detalleCompra = new detalle_vale();
        $this->detalleCompra = $detalleCompra->listarProductoMovimiento($compraId);
        $usuario = new usuario();
        $destino = new almacen();
        $this->destino = $destino->destino($compraId);
        $ciclo = new lote();
        $this->ciclo = $ciclo->ciclo($compraId);
        // $this->datosUsuario=$usuario->listarEmpleado($this->datosCompra->USUARIO_ID);
    }

    public function valeEntradaCT($compraId) {
        $compra = new vale();
        $this->datosCompra = $compra->listarXid($compraId);
        $datosCompra = $this->datosCompra;
        $detalleCompra = new detalle_vale();
        $this->detalleCompra = $detalleCompra->listarProductoCompra($compraId);
        $usuario = new usuario();
        $destino = new almacen();
        $this->destino = $destino->destino($compraId);
        $ciclo = new lote();
        $this->ciclo = $ciclo->ciclo($compraId);

        $this->referencia = $compra->find_by_sql("select documento from vale where id='$datosCompra->referencia_id '");
        // $this->datosUsuario=$usuario->listarEmpleado($this->datosCompra->USUARIO_ID);
    }

    public function valeSalida($entregaId) {
        $compra = new vale();
        $this->datosCompra = $compra->listarXid($entregaId);
        $detalleCompra = new detalle_vale();
        $this->detalleCompra = $detalleCompra->listarProductoCompra($entregaId);
        $usuario = new usuario();
        $destino = new almacen();
        $this->destino = $destino->destino($entregaId);
        $ciclo = new lote();
        $this->ciclo = $ciclo->ciclo($entregaId);
    }

    function libreriaInventario() {
        View::template(NULL);

        $this->operacion = Input::POST('operacion');


        if (($this->operacion) == "MOSTRAR") {
            $producto = new producto();
            $this->listar = $producto->listarJunto();
        } else if (($this->operacion) == "MOSTRAR_ALMACEN") {
            $id = Input::POST('almacen');
            $almacen = new almacen();
            $this->listar = $almacen->listarProductoAlmacen($id);
        } else if (($this->operacion) == "MOSTRAR_INICIAL") {
            $id = Input::POST('almacen');
            $almacen = new almacen();
            $this->listar = $almacen->listarProductoAlmacen($id);
        } else if (($this->operacion) == "AGREGAR_PRODUCTO") {

            $inventario = new inventario();


            $this->resultado = $inventario->guardarDatos(Input::POST('producto'), Input::POST('almacen'), Input::POST('minimo'), Input::POST('maxima'));
        } else if (($this->operacion) == "AGREGAR_PARTIDA") {

            $clase = new clase_recepcion();
            $this->mensaje = $clase->introduce_movimiento(Input::POST('cantidad'), Input::POST('productoId'), Input::POST('almacenId'), Input::POST('lote'), Input::POST('fecha_caducidad'), Input::POST('serie'), Input::POST('opcion'), Input::POST('numInventario'), Input::POST('unidad'));
        } else if (($this->operacion) == "AGREGAR_PARTIDAF") {

            $clase = new clase_recepcion();
            $this->mensaje = $clase->introduce_movimientoF(Input::POST('cantidad'), Input::POST('productoId'), Input::POST('almacenId'), Input::POST('lote'), Input::POST('fecha_caducidad'), Input::POST('serie'), Input::POST('opcion'), Input::POST('numInventario'), Input::POST('unidad'));
        } else if (($this->operacion) == "AGREGAR_PARTIDA_SALIDA") {

            $clase = new claseSalida();
            $this->mensaje = $clase->introduce_movimiento(Input::POST('cantidad'), Input::POST('productoId'), Input::POST('almacenId'), Input::POST('loteId'), Input::POST('serieId'), Input::POST('opcion'), Input::POST('numInventario'));
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
            $clase = new clase_recepcion();
            $this->mensaje = $clase->quita_movimiento(Input::POST('linea'));
        } else if (($this->operacion) == "QUITAR_PARTIDA_SALIDA") {
            $clase = new claseSalida();
            $this->mensaje = $clase->quita_movimiento(Input::POST('linea'));
        } else if (($this->operacion) == "MOSTRAR_LISTA") {
            
        } else if (($this->operacion) == "MOSTRAR_LISTA_SALIDA") {
            
        } else if (($this->operacion) == "BUSCA_ALMACEN") {

            $this->condicion = 'PRODUCTO_ID=' . Input::POST('productoId');
        } else if (($this->operacion) == "BUSCA_ALMACEN_SALIDA") {

            $this->condicion = 'PRODUCTO_ID=' . Input::POST('productoId');
        } else if (($this->operacion) == "MOSTRAR_DETALLE") {

            $productoId = Input::POST('productoId');
            $producto = new producto();
            $detalle = $producto->listarXid($productoId);
            if ($detalle->NUMERO_SERIE == "S") {
                $this->var = "serie";
                if ($detalle->NUMERO_INVENTARIO == "S") {
                    $this->var = "serieInventario";
                }
            } else if ($detalle->LOTE == "S") {
                $this->var = "lote";
                if ($detalle->NUMERO_INVENTARIO == "S") {
                    $this->var = "loteInventario";
                }
            } else {
                $this->var = "ninguno";
                if ($detalle->NUMERO_INVENTARIO == "S") {
                    $this->var = "soloInventario";
                }
            }
        } else if (($this->operacion) == "MOSTRAR_DETALLE_SALIDA") {

            $this->productoId = Input::POST('productoId');
            $this->almacenId = Input::POST('almacenId');
            $producto = new producto();
            $this->detalle = $producto->listarXid($this->productoId);
        } else if (($this->operacion) == "BUSCA_PROVEEDOR") {

            $proveedorId = Input::POST('proveedorId');

            $this->proveedor = Load::model('proveedor')->buscarProveedor($proveedorId);
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
            $loteId = Input::POST('lote');
            $productoId = Input::POST('producto');
            $almacenId = Input::POST('almacen');

            $existencia = new detalle_lote();
            $lote = $existencia->find_first($loteId);
            $existencia = new detalle_lote();
            $this->resultado = $existencia->busca_existenciaT($lote->lote_id, $productoId, $almacenId);
        } else if (($this->operacion) == "BUSCA_LOEXT_PRODUCTO") {
            $productoId = Input::POST('productoId');
            $almacenId = Input::POST('almacenId');
            $datos = new detalle_lote();
            $this->datos = $datos->find_all_by_sql("select lo.codigo, dl.existencia,lo.id from detalle_lote dl inner join lote lo on dl.lote_id=lo.id  where dl.producto_id='$productoId' and dl.almacen_id='$almacenId' and dl.existencia >0 ");
        }
    }

    public function valeMovimiento($compraId) {
        $compra = new vale();
        $this->datosCompra = $compra->listarXid($compraId);
        $detalleCompra = new detalle_vale();
        $this->detalleCompra = $detalleCompra->listarProductoMovimiento($compraId);
        $usuario = new usuario();
        $destino = new almacen();
        $this->destino = $destino->destino($compraId);
        $ciclo = new lote();
        $this->ciclo = $ciclo->ciclo($compraId);
        // $this->datosUsuario=$usuario->listarEmpleado($this->datosCompra->USUARIO_ID);
    }

    public function valeCombinacion($compraId) {
        $compra = new vale();
        $this->datosCompra = $compra->listarXid($compraId);
        $detalleCompra = new detalle_vale();
        $this->detalleCompra = $detalleCompra->listarProductoMovimiento($compraId);
        $usuario = new usuario();
        $destino = new almacen();
        $this->destino = $destino->destino($compraId);
        $ciclo = new lote();
        $this->ciclo = $ciclo->ciclo($compraId);
        // $this->datosUsuario=$usuario->listarEmpleado($this->datosCompra->USUARIO_ID);
    }
   
    public function validarExistencia() {

        View::select(NULL);

        $producto = new producto();
        $producto->update_all("existencia=0");
        $inventario = new inventario();
        $inventario->update_all("existencia=0");
        $lote = new detalle_lote();
        $lote->update_all("existencia=0");
        $consulta = new movimiento_inventario();
        $producto = $consulta->find_all_by_sql("SELECT DISTINCT (P.id) FROM producto P inner join movimiento_inventario MI on P.id=MI.producto_id ORDER BY `P`.`id` ASC");

        foreach ($producto as $datoP) {
            $existenciaP = 0;
            $producto_id = $datoP->id;

            $almacen = $consulta->find_all_by_sql("SELECT DISTINCT(almacen_id) FROM  movimiento_inventario where producto_id='$producto_id' ");

            foreach ($almacen as $datoA) {
                $existenciaA = 0;
                $almacen_id = $datoA->almacen_id;
                $lote = $consulta->find_all_by_sql("SELECT DISTINCT(lote_id) FROM movimiento_inventario where producto_id='$producto_id' and almacen_id='$almacen_id'");
                foreach ($lote as $datoL) {
                    $lote_id = $datoL->lote_id;

                    $existencia = $consulta->find_all_by_sql("SELECT CM.tipo_movimiento,MI.cantidad FROM movimiento_inventario MI inner join concepto_movimiento CM on MI.tipo_movimiento=CM.id where producto_id='$producto_id' and almacen_id='$almacen_id' and lote_id='$lote_id' ");
                    $existenciaL = 0;
                    foreach ($existencia as $cantidad) {
                        if($cantidad->tipo_movimiento=='E'){
                        $existenciaP +=$cantidad->cantidad;
                        $existenciaA +=$cantidad->cantidad;
                        $existenciaL +=$cantidad->cantidad;
                        }else{
                        $existenciaP -=$cantidad->cantidad;
                        $existenciaA -=$cantidad->cantidad;
                        $existenciaL -=$cantidad->cantidad;  
                        }
                    }
                    
                    $lote = new detalle_lote();
                    $lote->update_all("existencia=existencia+$existenciaL", "producto_id=$producto_id and almacen_id=$almacen_id and lote_id=$datoL->lote_id");
                }
                $inventario = new inventario();
                $inventario->update_all("existencia=existencia+$existenciaA", "producto_id=$producto_id and almacen_id=$almacen_id ");
            }
            $productoG = new producto();
                $productoG->update_all("existencia=existencia+$existenciaP", "id=$producto_id ");
        }
    }
    
    public function validarMovimiento() {
         View::select(NULL);
        $consulta = new movimiento_inventario();
        $producto = $consulta->find_all_by_sql("SELECT DISTINCT (producto_id), almacen_id FROM  movimiento_inventario  ORDER BY producto_id ASC");
        foreach ($producto as $datoP) {
            $existencia = $consulta->find_all_by_sql("SELECT M.*, C.tipo_movimiento FROM movimiento_inventario M INNER join concepto C on M.tipo_movimiento=C.id where producto_id='$datoP->producto_id' and almacen_id='$datoP->almacen_id'");
                $cantidad=0;
                foreach ($existencia as $datoE) {
                   if($datoE->tipo_movimiento=="E"){
                       $datoE->cantidad=$datoE->cantidad * (1);
                   }else{
                       $datoE->cantidad=$datoE->cantidad * (-1);
                   }
                  $cantidad +=$datoE->cantidad;
                  $actualiza=$consulta->find_first($datoE->id);
                  $actualiza->existencia=$cantidad;
                  $actualiza->update();
                }
        }
    }

    public function reporteSalidaT() 
    {
    ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '-1');
        $consulta = new venta();
        if (Input::hasPost('fecha_inicial')) {
            $lote = Input::Post('lote');
            $producto = Input::Post('producto');
            //$almacen = Input::Post('almacen');
            $fecha_inicial = Input::Post('fecha_inicial');
            $fecha_final = Input::Post('fecha_final');
            $condicion= " where V.fecha_documento >='$fecha_inicial' and V.fecha_documento <='$fecha_final'  ";
            if(trim($producto)!=''){
               $condicion = $condicion." and DV.producto_id='$producto' ";  
            }
            if(trim($lote)!=''){
               $condicion = $condicion." and DV.lote_id='$lote' ";  
            }
         /*  if(trim($almacen)!=''){
             //  $condicion = $condicion." and M.almacen_id='$almacen' ";  
            }*/
            $this->resultado = $consulta->find_all_by_sql("select V.fecha_documento, V.documento,V.estado,DV.cantidad, concat_ws(' - ',P.clave,P.descripcion) producto, L.codigo,A.descripcion almacen, DV.cantidad  from vale V inner join detalle_vale DV on V.id=DV.compra_id  INNER JOIN producto P on DV.producto_id=P.id inner join lote L on DV.lote_id=L.id inner join almacen A on DV.almacen_id=A.id  $condicion  and V.tipo='ST' ORDER BY V.fecha_documento ASC");
            
        }
    }
    
}
?>

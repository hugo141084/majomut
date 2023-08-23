<?php

class cobros extends ActiveRecord {

    protected function initialize() {
        $sesionBD = Session::get('baseMunicipio');
        $this->set_database($sesionBD);
    }

    public function guardarDatosC($fechaCaptura, $concepto, $referencia, $fecha, $monto, $idCuenta, $idEmpleado, $tipoOperacion, $fechaDocumento,$uso_cfdi,$cliente_id,$tipo=NULL) {
        $ciclocosechaId = Session::get('cicloCosecha');                   
        $datos = new cobros();
        $datos->fecha_captura = $fechaCaptura;
        $datos->concepto_id = $concepto;
        $datos->referencia = $referencia;
        $datos->monto = $monto;
        if (($tipoOperacion == "A") || ($tipoOperacion == "B")) {
            $datos->fecha_referencia = strftime("%Y-%m-%d", strtotime($fecha));
            $datos->bancos_cuentas_id = $idCuenta;
        } else {
        
        }
        $datos->empleado_id = $idEmpleado;
        $datos->estatus = '1';
        $datos->fecha_documento = $fechaDocumento;
        $datos->uso_cfdi = $uso_cfdi;
        $datos->cliente_id = $cliente_id;
        $datos->tipo = $tipo;
        $datos->ciclocosecha_id=$ciclocosechaId; 
        $datos->save();

        return $datos->id;
    }

    public function datosCobro($id) {
        return $this->find("id=$id");
    }

    public function cancelaCobro($idCobro) {
        return $this->update_all("estatus='0'", "id=$idCobro");
    }

    public function listadoCobro() {
        return $this->find_all_by_sql("SELECT C.id, CL.nombrecompleto,C.monto, CON.concepto, BC.numero_cuenta, B.nombre_banco, C.referencia,C.fecha_referencia,C.fecha_captura,C.tipo,C.estatus FROM cobros C inner join conceptos CON on C.concepto_id=CON.id INNER JOIN cliente CL on C.cliente_id=CL.id LEFT JOIN bancos_cuentas BC on C.bancos_cuentas_id=BC.id left  JOIN bancos B on BC.bancos_id=B.id and ciclocosecha_id='$ciclocosechaId' order by C.id desc");
       
    }

    public function listadoCobroSarqueo() {
        return $this->find_all_by_sql("SELECT  rec.num_recibo, concat_ws( ' ', cat.nombre, cat.ape_pat, cat.ape_mat ) AS nombre, cat.rfc, DATE_FORMAT( rec.fecha_documento, '%d-%m-%Y' ) fecha, rec.monto
FROM recibos_cobros cob 
INNER JOIN recibos rec ON cob.recibos_id=rec.id
INNER JOIN cobros c ON cob.cobros_id=c.id
INNER JOIN cat_sujetos_contribuyentes cat ON cob.cat_sujetos_contribuyentes_id=cat.id
WHERE rec.id = cob.recibos_id
AND cob.cat_sujetos_contribuyentes_id = cat.id and rec.estatus='1' and arqueo_id IS NULL");
    }

    public function obtenerCobrosEmpleado($empleado) {
        if ($empleado != '') {
            $empleado = stripcslashes($empleado);
            $sql = "SELECT c.id,c.nombre,c.ape_pat,c.ape_mat
                FROM cobros cob
                INNER JOIN cat_sujetos_empleados c ON cob.cat_sujetos_empleados_id=c.id
                WHERE concat_ws(' ',c.nombre,c.ape_pat,c.ape_mat) LIKE '%" . $empleado . "%' AND cob.arqueo_id is NULL and cob.estatus != '0' GROUP BY concat_ws(' ',c.nombre,c.ape_pat,c.ape_mat)";
            $res = $this->find_all_by_sql($sql);
            if ($res) {
                foreach ($res as $empleado) {
                    $empleados[] = array(
                        'id' => $empleado->id,
                        'label' => $empleado->nombre . " " . $empleado->ape_pat . " " . $empleado->ape_mat,
                    );
                }
                return $empleados;
            }
        }
        return array('NO EXISTE COBROS REALIZADOS POR EL EMPLEADO');
    }

    public function cobrosSistema($idEmpleado, $fecha) {
        return $this->find_by_sql("SELECT sum(cob.monto) as monto FROM cobros as cob WHERE  cob.cat_sujetos_empleados_id=$idEmpleado and cob.estatus='1' and DATE(cob.fecha_documento)='$fecha' and cob.arqueo_id is NULL ");
    }
    public function cobrosSistemaAux($idArqueo) {
        return $this->find_by_sql("SELECT sum(cob.monto) as monto FROM cobros as cob WHERE   cob.estatus='1'  and cob.arqueo_id=$idArqueo ");
    }
    public function cobrosDocumentos($idEmpleado, $fecha) {
        return $this->find_all_by_sql("SELECT sum(cob.monto) as monto, con.concepto FROM cobros as cob, concepto_movimientos as con WHERE cob.concepto_movimientos_id=con.id  and con.concepto!='Efectivo' and cob.cat_sujetos_empleados_id=$idEmpleado and cob.estatus='1' and DATE(cob.fecha_documento)='$fecha'  and cob.bancos_cuentas_id=0 and cob.arqueo_id is NULL");
    }
public function cobrosDocumentosBancosGeneral($idEmpleado, $fecha) {
        return $this->find_all_by_sql("SELECT  con.concepto, cue.numero_cuenta, ban.nombre_banco FROM cobros as cob, concepto_movimientos as con, bancos_cuentas as cue, bancos as ban WHERE cob.concepto_movimientos_id=con.id  and con.concepto!='Efectivo' and cob.cat_sujetos_empleados_id=$idEmpleado and cob.estatus='1' and DATE(cob.fecha_documento)='$fecha'  and cob.bancos_cuentas_id=cue.id  and cue.bancos_id=ban.id and cob.arqueo_id is NULL ");
    }
    public function cobrosDocumentosBancos($idEmpleado, $fecha) {
        return $this->find_all_by_sql("SELECT sum(cob.monto) as monto, con.concepto, cue.numero_cuenta, ban.nombre_banco FROM cobros as cob, concepto_movimientos as con, bancos_cuentas as cue, bancos as ban WHERE cob.concepto_movimientos_id=con.id  and con.concepto!='Efectivo' and cob.cat_sujetos_empleados_id=$idEmpleado and cob.estatus='1' and DATE(cob.fecha_documento)='$fecha'  and cob.bancos_cuentas_id=cue.id  and cue.bancos_id=ban.id and cob.arqueo_id is NULL group by cob.bancos_cuentas_id");
    }

    public function cobrosDocumentosGeneral($idEmpleado, $fecha) {
        return $this->find_all_by_sql("SELECT sum(cob.monto) as monto, con.concepto FROM cobros as cob, concepto_movimientos as con WHERE cob.concepto_movimientos_id=con.id   and cob.cat_sujetos_empleados_id=$idEmpleado and cob.estatus='1' and DATE(cob.fecha_documento)='$fecha' and cob.arqueo_id is NULL and cob.bancos_cuentas_id=0 AND con.id !=6 GROUP BY con.concepto");
    }

    public function actualizaCobros($idArqueo, $idEmpleado, $fecha) {
        return $this->update_all("arqueo_id=$idArqueo", "cat_sujetos_empleados_id=$idEmpleado and estatus='1' and DATE(fecha_documento)='$fecha' and arqueo_id is NULL");
    }

    
    

    public function cobrosDocumentosBancosId($idArqueo) {
        return $this->find_all_by_sql("SELECT sum(cob.monto) as monto, con.concepto, cue.numero_cuenta, ban.nombre_banco FROM cobros as cob, concepto_movimientos as con, bancos_cuentas as cue, bancos as ban WHERE cob.concepto_movimientos_id=con.id  and con.concepto!='Efectivo'  and cob.estatus='1'   and cob.bancos_cuentas_id=cue.id  and cue.bancos_id=ban.id  and arqueo_id=$idArqueo group by cob.bancos_cuentas_id");
    }

    public function cobrosDocumentosGeneralId($idArqueo) {
        return $this->find_all_by_sql("SELECT sum(cob.monto) as monto, con.concepto FROM cobros as cob, concepto_movimientos as con WHERE cob.concepto_movimientos_id=con.id  and cob.estatus='1'  and cob.bancos_cuentas_id=0 and arqueo_id=$idArqueo GROUP BY con.concepto");
    }

    public function cobrosIngresos($idArqueo) {
        return $this->find_by_sql("SELECT sum( cu.impuesto ) AS total
FROM cuenta_cobrar AS cu, concepto_movimientos AS co, cobros AS cob
WHERE cu.cobros_id = cob.id
AND cu.concepto_movimientos_id = co.id
AND co.movimiento_caja = 'A'
AND cob.arqueo_id =$idArqueo
AND co.id !=6 AND cu.estatus='1'");
    }

    


    public function cobrosBancoSistema($idArqueo) {
        return $this->find_all_by_sql("SELECT co.concepto,  cu.impuesto, cob.bancos_cuentas_id
FROM cuenta_cobrar AS cu, concepto_movimientos AS co, cobros AS cob
WHERE cu.cobros_id = cob.id
AND cu.concepto_movimientos_id = co.id
AND co.clasificacion_concepto = 'A'
AND cob.arqueo_id =$idArqueo
AND cob.bancos_cuentas_id!=0
AND co.id !=6 ");
    }

    public function cobrosGeneral($idArqueo) {
        return $this->find_by_sql("SELECT sum(monto) as impuesto
FROM  cobros 
WHERE  arqueo_id =$idArqueo
AND concepto_movimientos_id !=6 ");
    }

    

    //FUNCION PARA CANCELAR COBROS
    public function cancelandoCobros($idArqueo) {
        return $this->update_all("arqueo_id= NULL", "arqueo_id=$idArqueo");
    }

    //REVERTIR MOVIMIENTO DE INGRESOS
    public function revertirMovimientoIngreso($idRc, $polizaC) {
        return $this->update_all("poliza_cancelada_ingreso=$polizaC", "id=$idRc ");
    }

    //cobros
    public function cobrosRecibosT($id) {
        return $this->find_by_sql("SELECT rc.cobros_id AS id,co.genero_poliza,co.poliza_cancelada_ingreso
                FROM cobros co
                INNER JOIN recibos_cobros rc ON rc.cobros_id=co.id
                INNER JOIN recibos r ON rc.recibos_id=r.id
                WHERE r.id=$id");
    }

    public function before_save() {
        foreach($this->fields as $field) {
            if(strpos(" " . $this->_data_type[$field], "varchar"))
                $this->$field = strtoupper($this->$field);
        }
    }
}

?>
<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class cuenta_cobrar extends ActiveRecord {

    protected function initialize() {
        
    }

    public function registraMovimiento($origen, $concepto_cuenta, $documento, $fecha_aplicacion, $fecha_vencimiento, $cliente_id,$saldo) {
        $cuenta = new cuenta_cobrar();
        $cuenta->origen =$origen;
        $cuenta->concepto_cuenta =(($concepto_cuenta=='')?'0':$concepto_cuenta);
        $cuenta->documento =$documento;
        $cuenta->fecha_aplicacion =$fecha_aplicacion;
        $cuenta->fecha_vencimiento =$fecha_vencimiento;
        $cuenta->cliente_id =$cliente_id;
        $cuenta->saldo=$saldo;
        $cuenta->save();
        }

}

?>
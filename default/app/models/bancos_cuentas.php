<?php

   
class bancosCuentas extends ActiveRecord {

       protected function initialize() {
        
    }

        public function datosCuenta($id=NULL) {
            return $this->find_all_by_sql("SELECT ban.nombre_banco, cu.id, cu.numero_cuenta, cu.sucursal,  DATE_FORMAT( cu.fecha_apertura, '%d-%m-%Y' ) fecha_apertura, cu.saldo,  cu.telefono, IF( cu.estatus ='1', 'Activo', IF( cu.estatus = '0', 'Inactivo', '' ) ) estado, cu.observacion
                    FROM bancos_cuentas AS cu,  bancos as ban
                    WHERE ban.id=cu.bancos_id ");
        }

        public function buscarCuentas($id) {
            return $this->find_all_by_sql("select * from bancos_cuentas where bancos_id=$id");
        }

        public function buscarCuentaId($id) {
            return $this->find_by_sql("select * from bancos_cuentas where id=$id");
        }
        public function buscarId($id) {
            return $this->find_by_sql("select id from bancos_cuentas where id=$id");
        }
        public function asignarSaldo($idCuenta,$saldo){
             
         $this->update_all("saldo=$saldo","id=$idCuenta ");
             
        
    }
         public function actualizaSaldo($ramo,$fuente,$banco,$movimiento,$saldo){
             if($movimiento=="C"){
         $this->update_all("saldo_inicial=saldo_inicial + $saldo,saldo=saldo + $saldo","clasif_tab_gral_id=$banco and cat_ramos_id=$ramo and cat_fuente_f_id=$fuente");
             }else if($movimiento=="A"){
          $this->update_all("saldo_inicial=saldo_inicial - $saldo,saldo=saldo - $saldo","clasif_tab_gral_id=$banco and cat_ramos_id=$ramo and cat_fuente_f_id=$fuente");
             }
        
    }
    
         public function regresaSaldo($ramo,$fuente,$banco,$movimiento,$saldo){
             if($movimiento=="A"){
         $this->update_all("saldo_inicial=saldo_inicial + $saldo,saldo=saldo + $saldo","clasif_tab_gral_id=$banco and cat_ramos_id=$ramo and cat_fuente_f_id=$fuente");
             }else if($movimiento=="C"){
          $this->update_all("saldo_inicial=saldo_inicial - $saldo,saldo=saldo - $saldo","clasif_tab_gral_id=$banco and cat_ramos_id=$ramo and cat_fuente_f_id=$fuente");
             }
        
    }
    public function regresaSaldoNormal($ramo,$fuente,$banco,$movimiento,$saldo){
             if($movimiento=="A"){
         $this->update_all("saldo=saldo + $saldo","clasif_tab_gral_id=$banco and cat_ramos_id=$ramo and cat_fuente_f_id=$fuente");
             }else if($movimiento=="C"){
          $this->update_all("saldo=saldo - $saldo","clasif_tab_gral_id=$banco and cat_ramos_id=$ramo and cat_fuente_f_id=$fuente");
             }
        
    }
    public function regresaSaldoNormalNuevo($ramo,$fuente,$banco,$movimiento,$saldo){
             if($movimiento=="A"){
         $this->update_all("saldo=saldo - $saldo","clasif_tab_gral_id=$banco and cat_ramos_id=$ramo and cat_fuente_f_id=$fuente");
             }else if($movimiento=="C"){
          $this->update_all("saldo=saldo + $saldo","clasif_tab_gral_id=$banco and cat_ramos_id=$ramo and cat_fuente_f_id=$fuente");
             }
        
    }    
        public function listarCuentasBancos() {
            $sql = "SELECT bc.id, concat(numero_cuenta,' - ', nombre_banco) cuenta, bc.numero_cuenta as numero, b.nombre_banco as nombreBanco,  bc.saldo
                   from bancos_cuentas bc inner join bancos b on bc.bancos_id = b.id where b.estatus='1' and bc.estatus='1'";
            return $this->find_all_by_sql($sql);
        }
        
        
         public function ramoFuenteBanco($ctas) {
            $sql = "SELECT bc.id, concat(numero_cuenta,' - ', nombre_banco) cuenta 
                   FROM bancos_cuentas bc 
                   INNER JOIN bancos b ON bc.bancos_id = b.id 
                   WHERE bc.cat_ramos_id='$ctas[0]' AND bc.cat_fuente_f_id='$ctas[1]' AND b.estatus='1' and bc.estatus='1'";
            return $this->find_all_by_sql($sql);
        }
        


        public function guardarDatos() {

            
            //TOMAMOS EL VALOR DEL ID DE CLASIF_TAB_GRAL SI SE ENCUENTRA EN ALGUNA DE LAS CLASIFICACIONES SUJETOS(EMPLEADO,PROVEEDORES,CONTRATISTA Y DEPENDENCIAS)               
            $guardaCuenta = new bancosCuentas(Input::post('cuenta'));
            $guardaCuenta->fecha_apertura = strftime("%Y-%m-%d", strtotime($guardaCuenta->fecha_apertura));
                        
                        if ($guardaCuenta->save()) {  //GUARDAMOS EL BANCO CORRECTAMENTE   
                                                   
                            Redirect::to('banco/');
                            
                           }
           
        }

        public function cuentaBanco($id) {
            $sql = "SELECT concat( nombre_banco,' - ',numero_cuenta) cuenta , saldo, cat_ramos_id, cat_fuente_f_id, numero_cuenta, clasif_tab_gral_id
                    from bancos_cuentas bc inner join bancos b on bc.bancos_id = b.id where b.estatus=1 and bc.estatus=1
                    and bc.id = $id";
            return $this->find_by_sql($sql);
        }

        public function cuentaBancoId($idCuenta) {
            return $this->find_by_sql("select cl.id from bancos_cuentas as ba, clasif_tab_gral as cl where ba.id=$idCuenta and ba.clasif_tab_gral_id=cl.id");
        }
        
        public function BancosClasif($ramo, $fuente) {   //ALGUIEN MAS OCUPA ESTA FUNCION?? LA MODIFIQUE EN LA FUNCION DE ABAJO. ATTE. ANA 
            $sql = "SELECT bc.clasif_tab_gral_id, concat(numero_cuenta,' - ', nombre_banco) cuenta,bc.id 
                    from bancos_cuentas bc inner join bancos b on bc.bancos_id = b.id 
                    inner join plan_cuentas_personalizado pcp on bc.clasif_tab_gral_id = pcp.clasif_tab_gral_id
                    where b.estatus='1' and bc.estatus='1' and pcp.cat_ramos_id =$ramo and  pcp.cat_fuente_f_id=$fuente";
            return $this->find_all_by_sql($sql);
        }
        public function BancosXrf($parametro) {   //ALGUIEN MAS OCUPA ESTA FUNCION?? LA MODIFIQUE EN LA FUNCION DE ABAJO. ATTE. ANA 
            $sql = "SELECT bc.id, concat(numero_cuenta,' - ', nombre_banco) cuenta 
                    from bancos_cuentas bc inner join bancos b on bc.bancos_id = b.id 
                    where b.estatus='1' and bc.estatus='1' and cat_ramos_id =$parametro[0] and cat_fuente_f_id=$parametro[1]";
            return $this->find_all_by_sql($sql);
        }
        
        public function BancosxClasif($ramo, $fuente) {   
            $sql = "SELECT bc.id, concat(numero_cuenta,' - ', nombre_banco) cuenta 
                    from bancos_cuentas bc inner join bancos b on bc.bancos_id = b.id 
                    where b.estatus='1' and bc.estatus='1' and cat_ramos_id =$ramo and cat_fuente_f_id=$fuente";
            return $this->find_all_by_sql($sql);
        }
        
        /*public function listarBancosCuentas($arrayDatos) {
            $sql = "SELECT BC.id, CONCAT( B.nombre_banco,  ' - ', BC.numero_cuenta ) cuenta
                    FROM bancos_cuentas BC
                    INNER JOIN bancos B ON BC.bancos_id = B.id
                    INNER JOIN cat_sujetos_empleados_id CSE ON BC.cat_sujeto_empleados_id=CSE.id
                    INNER JOIN plan_cuentas_personalizado PCP on BC.clasif_tab_gral_id = PCP.clasif_tab_gral_id
                    WHERE B.estatus =  '1'
                    AND BC.estatus =  '1'
                    AND PCP.cat_ramos_id=$arrayDatos[0]
                    AND PCP.cat_fuente_f_id=$arrayDatos[1]";
            return $this->find_all_by_sql($sql);
        }*/
        
        public function listarBancosCajasCuentas($arrayDatos) {
            $infoAux=($arrayDatos[2]=="cajas")?"BC.descripcion, ' - ' ,CSE.nombre,' ',CSE.ape_pat,' ',if(CSE.ape_mat is null,'',CSE.ape_mat )":"B.nombre_banco,  ' - ', BC.numero_cuenta,' - ', if(BC.observacion is null,'',BC.observacion )";
            $innerAux=($arrayDatos[2]=="cajas")?"INNER JOIN cat_sujetos_empleados CSE ON BC.cat_sujetos_empleados_id = CSE.id":" INNER JOIN bancos B ON BC.bancos_id = B.id ";
            
            $sql = "SELECT BC.id, CONCAT( $infoAux ) cuenta, saldo
                    FROM $arrayDatos[2] BC
                    $innerAux                    
                    AND BC.estatus =  '1'
                    AND BC.cat_ramos_id=$arrayDatos[0]
                    AND BC.cat_fuente_f_id=$arrayDatos[1]";
            return $this->find_all_by_sql($sql);
        }
        
        public function updSaldoBancosCajas($tabla,$ramo,$fuente,$mov,$importe,$sujeto) {
             switch($tabla){
                 case'bancos_cuentas':  
                            $saldoAct=$this->find_by_sql("select BC.id,saldo from $tabla BC WHERE BC.estatus =  '1' AND BC.cat_ramos_id=$ramo AND BC.cat_fuente_f_id=$fuente AND BC.clasif_tab_gral_id=$sujeto");
                            $saldo=($mov=='C')?$saldoAct->saldo+$importe:$saldoAct->saldo-$importe;
                            return $this->sql("UPDATE $tabla BC SET saldo = $saldo WHERE id=$saldoAct->id");
                            break;
                 case 'cajas':
                            $saldoAct=$this->find_by_sql("select BC.id,saldo from $tabla BC INNER JOIN cat_sujetos_empleados  CSE ON BC.cat_sujetos_empleados_id=CSE.id WHERE BC.estatus =  '1' AND BC.cat_ramos_id=$ramo AND BC.cat_fuente_f_id=$fuente AND CSE.clasif_tab_gral_id=$sujeto");
                            $saldo=($mov=='C')?$saldoAct->saldo+$importe:$saldoAct->saldo-$importe;
                            return $this->sql("UPDATE $tabla BC SET saldo = $saldo WHERE id=$saldoAct->id");
                            break;
             }
        }
        
        public function cuentaxId($idCta){
            return $this->find_by_id($idCta);
        }
        
        public function actualizarSujeto(){
            $datos = new bancosCuentas(Input::post('cuentas'));
          $datosBanco = Load::model('bancos')->nombreBanco($datos->bancos_id);
          $idP=$datos->clasif_tab_gral_id;
          $concepto = $datosBanco->nombre_banco . " " . $datos->numero_cuenta;
          $sujeto = new ClasifTabGral();
                $sujetoActualizar = $sujeto->find_first('id=' . $idP);
                $sujetoActualizar->concepto = $concepto;

               $sujetoActualizar->update();
        }
        
        public function configuracionCheque($idCuenta){
        return $this->exists('id='.$idCuenta.' and folio_final=0');    
        }
        public function disponibleCheque($idCuenta){
         return $this->exists('id='.$idCuenta.' and consecutivo < folio_final');    
        }
        public function consecutivoCheque($idCuenta){
         return $this->find_by_sql("select consecutivo from bancos_cuentas where id=".$idCuenta);    
        }
         public function aumentoConsecutivo($idCuenta){
          return $this->update_all("consecutivo = consecutivo +1" , "id=$idCuenta");    
          
        }
        public function buscarCuenta($condicion) {
            return $this->find_all_by_sql("SELECT ban.id, CONCAT( ban.numero_cuenta, ' - ', cla.nombre_banco ) concepto
FROM bancos AS cla, bancos_cuentas AS ban
WHERE cla.id = ban.bancos_id
       ");
            
        }
        
        public function before_save() {
            foreach($this->fields as $field) {
                if(strpos(" " . $this->_data_type[$field], "varchar"))
                    $this->$field = strtoupper($this->$field);
            }
        }
    }
   
 ?>
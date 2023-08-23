<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php

class recibos_cobros extends ActiveRecord{
		
		protected function initialize(){
                        $sesionBD= Session::get('baseMunicipio');    
                        $this->set_database($sesionBD);                   
		}
               public function guardarDatosRC($idRecibos, $idCobros, $idSujeto,$monto,$estatus){                                          
                   $datos = new recibos_cobros();  
                   $datos->recibos_id=$idRecibos;
                      $datos->cobros_id=$idCobros;
                      $datos->cliente_id=$idSujeto;
                      $datos->monto=$monto;
                      $datos->estatus=$estatus;
                      $datos->save();                     
                }
                public function busquedaRecibos($idRe) {
                    return $this->find_by_sql("Select rc.id,concat_ws(' ',csc.nombre,csc.ape_pat,csc.ape_mat) AS contribuyente,csc.domicilio,csc.rfc 
                            FROM recibos_cobros rc
                            INNER JOIN cobros c ON rc.cobros_id=c.id
                            INNER JOIN contribuyente csc ON rc.contribuyente_id=csc.id
                            WHERE recibos_id='$idRe'");
                    
                }
                
                public function cancelacionCobrosRecibos($idArqueo){
                    return $this->find_by_sql("Select c.id,r.id 
                        FROM recibos_cobros rc 
                        INNER JOIN cobros c ON rc.cobros_id=c.id
                        INNER JOIN recibos r ON rc.recibos_id=r.id
                        WHERE c.arqueo_id='$idArqueo'");
                }
                 public function datosReciboId($id){
                     return $this->find_by_sql("SELECT co.arqueo_id,  co.estatus, co.bancos_cuentas_id,co.id as id_cobro, re.id as id_recibo, co.monto, co.referencia,co.fecha_referencia,co.concepto_movimientos_id,co.estatus,re.fecha_documento
FROM recibos AS re, recibos_cobros AS reca, cobros AS co
WHERE re.id = reca.recibos_id
AND co.id = reca.cobros_id
AND re.id =$id ");
                     
                     
                 }
                 
                 
                 
                 
                 
}
?>
<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php
	class envio extends ActiveRecord{
		
		
                
                
                public function datosEmpresa(){
                    return $this->find();
                }
                
                public function guardarEnvio() {

            
            //TOMAMOS EL VALOR DEL ID DE CLASIF_TAB_GRAL SI SE ENCUENTRA EN ALGUNA DE LAS CLASIFICACIONES SUJETOS(EMPLEADO,PROVEEDORES,CONTRATISTA Y DEPENDENCIAS)               
            $guardaEnvio = new envio(Input::post('envio'));
            $venta=$guardaEnvio->venta_id;
            $contExiste = $this->exists("venta_id ='$venta' ");
           
                      
            if ($contExiste > 0) {
                $guardaEnvio->update(); 
            }else{
          $guardaEnvio->save();            
                            
                           }
           
        }
                 
        }
 ?>
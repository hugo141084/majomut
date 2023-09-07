<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php
	class dato_factura extends ActiveRecord{
		
		
                
                
                public function datosEmpresa(){
                    return $this->find();
                }
                
                public function guardarDatos() {

            
            
            $guardaEnvio = new dato_factura(Input::post('factura'));
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

<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php
	class costoEnvio extends ActiveRecord{
		
		
                
                public function nombreEmpresa($idEmpresa){
                    return $this->find_first("conditions: id=$idEmpresa ","");
                }
                public function datosCosto(){
                    return $this->find();
                }
                
                public function guardarCosto() {

            
            //TOMAMOS EL VALOR DEL ID DE CLASIF_TAB_GRAL SI SE ENCUENTRA EN ALGUNA DE LAS CLASIFICACIONES SUJETOS(EMPLEADO,PROVEEDORES,CONTRATISTA Y DEPENDENCIAS)               
            $guardaCosto = new costoEnvio(Input::post('costo'));
            
                        
                        if ($guardaCosto->save()) {  //GUARDAMOS EL BANCO CORRECTAMENTE   
                                                   
                            Redirect::to('envios/');
                            
                           }
           
        }
                 
        }
 ?>
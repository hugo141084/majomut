<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php
	class empresa extends ActiveRecord{
		
		
                
                public function nombreEmpresa($idEmpresa){
                    return $this->find_first("conditions: id=$idEmpresa ","");
                }
                public function datosEmpresa(){
                    return $this->find();
                }
                
                public function guardarDatos() {

            
            //TOMAMOS EL VALOR DEL ID DE CLASIF_TAB_GRAL SI SE ENCUENTRA EN ALGUNA DE LAS CLASIFICACIONES SUJETOS(EMPLEADO,PROVEEDORES,CONTRATISTA Y DEPENDENCIAS)               
            $guardaEmpresa = new empresa(Input::post('empresa'));
            
                        
                        if ($guardaEmpresa->save()) {  //GUARDAMOS EL BANCO CORRECTAMENTE   
                                                   
                            Redirect::to('empresa/');
                            
                           }
           
        }
                 
        }
 ?>
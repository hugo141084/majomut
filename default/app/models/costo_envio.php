<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php

class costoEnvio extends ActiveRecord {

    public function nombreEmpresa($idEmpresa) {
        return $this->find_first("conditions: id=$idEmpresa ", "");
    }

    public function datosCosto() {
        return $this->find("estatus='1'");
    }
    public function datos() {
        return $this->find_all_by_sql("select  costo id, concat_ws(' - ',codigo,costo) costo from costo_envio where estatus='1'");
    }

    public function guardarCosto() {
        //TOMAMOS EL VALOR DEL ID DE CLASIF_TAB_GRAL SI SE ENCUENTRA EN ALGUNA DE LAS CLASIFICACIONES SUJETOS(EMPLEADO,PROVEEDORES,CONTRATISTA Y DEPENDENCIAS)               
        $guardaCosto = new costoEnvio(Input::post('costo'));
        if ($guardaCosto->save()) {  //GUARDAMOS EL BANCO CORRECTAMENTE                                                    
            //     Redirect::to('envios/');                            
        }
    }
    public function actualizarDatos() {
        //TOMAMOS EL VALOR DEL ID DE CLASIF_TAB_GRAL SI SE ENCUENTRA EN ALGUNA DE LAS CLASIFICACIONES SUJETOS(EMPLEADO,PROVEEDORES,CONTRATISTA Y DEPENDENCIAS)               
        $guardaCosto = new costoEnvio(Input::post('costo'));
        if ($guardaCosto->update()) {  //GUARDAMOS EL BANCO CORRECTAMENTE                                                    
            //     Redirect::to('envios/');                            
        }
    }

}

?>

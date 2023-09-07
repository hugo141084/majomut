<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php

class empresa extends ActiveRecord {

    public function nombreEmpresa($idEmpresa) {
        return $this->find_first("conditions: id=$idEmpresa ", "");
    }
      public function listar() {
        return $this->find("conditions: estatus='1' ");
    }
    public function datosEmpresa() {
        return $this->find_all_by_sql("SELECT em.id, em.*,concat_ws(' - ',ba.nombre_banco,em.numero_cuenta,em.clabe_interbancaria) datosPago 
                    FROM empresa  em   left join  bancos  ba on em.banco_id=ba.id
                  WHERE em.estatus='1'");
    }

    public function guardarDatos() {

        $guardaEmpresa = new empresa(Input::post('empresa'));
        if ($guardaEmpresa->save()) {  //GUARDAMOS EL BANCO CORRECTAMENTE                                                      
          //  Redirect::to('empresa/');
        }
    }
    public function actualizarDatos() {

        $guardaEmpresa = new empresa(Input::post('empresa'));
        if ($guardaEmpresa->update()) {  //GUARDAMOS EL BANCO CORRECTAMENTE                                                      
        //    Redirect::to('empresa/');
        }
    }

}

?>

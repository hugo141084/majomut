<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of periodicidadCobro
 *
 * @author INFORMATICA
 */
class usoCfdi extends ActiveRecord {

    //put your code here
   

    public function listar() {
        return $this->find();
    }
    
}

?>



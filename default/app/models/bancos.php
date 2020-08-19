<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php
	class bancos extends ActiveRecord{
		
		
                public function nombreBanco($idBanco){
                    return $this->find_first("conditions: id=$idBanco ","");
                }
                
                public function datosBanco(){
                    return $this->find();
                }
                
                public function before_save() {
                    foreach($this->fields as $field) {
                        if(strpos(" " . $this->_data_type[$field], "varchar"))
                            $this->$field = strtoupper($this->$field);
                    }
                }
                 
        }
 ?>

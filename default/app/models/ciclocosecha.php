<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php
	class ciclocosecha extends ActiveRecord{
		
		
         public function listar(){
                    return $this->find();
                }       
                 
        }
 ?>

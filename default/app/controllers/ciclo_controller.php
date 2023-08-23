<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class cicloController extends AppController{
    
    /**
     * Muestra el partial de las nominas generadas
     */
    public function index() {
         $cuentas = new ciclocosecha();
        if (Input::hasPost('ciclo')) {

            $ciclo = new ciclocosecha(Input::Post('ciclo'));
            $ciclo->save();
            
        }

        $this->result = $cuentas->find();
        $this->campos = array(
            utf8_encode('#') => 'id',
            utf8_encode('Ciclo de Cosecha') => 'ciclo'
            
        );
        $this->encabezado= "";
    }
    public function editarCiclo($id){
        
     
        $cuenta = new ciclocosecha();
        $this->ciclo = $cuenta->find_first($id);

        if (Input::hasPost('ciclo')) {

            $ciclo = new ciclocosecha(Input::Post('ciclo'));
            $ciclo->update();
            Redirect::to('ciclo/index');
        }
       
    }
     public function seleccionar() 
    {
    {
        if(input::hasPost('ciclo')){
            $datos=input::Post('ciclo');
           
            $ciclo=$datos['id'];
            $cuenta = new ciclocosecha();
            $cuenta=$cuenta->find_first($ciclo);
            Session::set('cicloCosecha', $ciclo);
            Session::set('textoCicloCosecha', $cuenta->ciclo);
            Redirect::to('index');
            
        }
    }
    }
   
}
?>

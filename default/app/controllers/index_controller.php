<?php

/**
 * Controller por defecto si no se usa el routes
 *
 */
class indexController extends AppController
{

    public function index()
    {
        
        error_reporting(0);
        $valido = Load::model('usuario')->isValid(); 
        if ($valido) { 
            Redirect::to('principal/principal');
            
     } else {
        $users = new Usuario();
        if (Input::hasPost("login") && Input::hasPost("clave")) {
            $datosUsu = Load::model('usuario')->validarUsuario(Input::Post("login"), Input::Post("clave"));
            if (isset($datosUsu->Id)) {
                switch ($datosUsu->Estatus) {
                    case 1:
                        
                        if ($users->iniciarSesion()) {                        
                         $operador = Session::get('Usr');
                       $this->usuarioactivo = $operador;

                            Redirect::to('principal/principal');
                            
                        }
                        break;
                }
            } else {
                print "<script>jAlert('Datos Incorrectos', 'ATENCION')</script>";
            }
        }
    }
    }
    public function salir() {
        $users = new Usuario();
        if ($users->cerrarSesion()) {
            Flash::info("La sesion ha sido cerrada correctamente.");
        }
      Redirect::to('index/index');
    }
    
    public function principal(){
       
    }
}

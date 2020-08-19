<?php
class usuario extends ActiveRecord {
    
    public static function isValid() {
        $auth = Auth2::factory('model');
        return $auth->isValid();
    }

    protected function initialize() {
        
    }
    public function listar() {
        return $this->find_all_by_sql("SELECT ua.id, ua.Usr, nombre_completo, ua.estado, ua.tipo,ua.Estatus as estatus FROM usuario ua INNER JOIN empleado e ON ua.empleado_id = e.id AND e.estatus = '1'");
    }
    public function iniciarSesion() {
        //verifica si tiene una sesion valida con ip
        if (self::isValid()) {
            return true;
        } else {
            //verifica si se ha autentificado
            if (Input::hasPost('login') && Input::hasPost('clave') ) {
                $nombreUsuario = Input::post('login'); //Filtro el usuario
                $contrasenia = Input::post('clave'); //Filtro la contraseña

                $auth = Auth2::factory('model'); //crea el objeto auth con el 
                $auth->setLogin('Usr'); //defino cual es el campo del nombre usuario
                $auth->setPass('Pas'); //defino cual es el campo de la contraseña
                $auth->setCheckSession(true);
                $auth->setModel('usuario');
                $auth->setFields(array('Id', 'Usr', 'Nombre', 'ApPaterno', 'ApMaterno', 'empleado_id', 'Tipo'));

                return ($auth->identify($nombreUsuario, $contrasenia) && $auth->isValid());
            }
        }
        return false;
    }

    /*     * *Metodo para cerrar sesion */

    public function cerrarSesion() {
        //verifico si tiene sesion
        if (!self::isValid()) {
            Flash::error("No has iniciado sesion o ha caducado.<br /> Por favor identifiquese");
            return false;
        } else {
            $auth = Auth2::factory('model');
            $auth->logout();
            //Elimino todas las variables de sesion de la app
            unset($_SESSION['KUMBIA_SESSION'][APP_PATH]);
            return true;
        }
    }
    
    //BUSCAR SI EXISTE UN NOMBREUSUARIO
    public function validarUsuario($username,$password) {
        return $this->find_by_sql("SELECT * FROM usuario WHERE Usr = '".$username."' and Pas='".md5($password)."'");
    }
    public function tipoUsuario(){
      return  Session::get('Tipo');
    }
    public function buscaEmpleadoId($idUsuario){
        return $this->find_by_sql("SELECT E.id as empleado_id
        FROM usuario U
        JOIN empleado E ON E.id = U.empleado_id
        WHERE U.id='$idUsuario'");
    }
    public function BloqueaUsuario($id){
        $this->update_all("estatus='0'","empleado_id=$id");
    }
     public function buscaUsuariovisualizar($idUsuario,$tipoUsuario){
return $this->find_by_sql("select  usu.id,en.nombre_completo, usu.Usr,en.etiqueta from usuario usu inner join empleado en on usu.empleado_id=en.id where usu.empleado_id='$idUsuario' and usu.Tipo='$tipoUsuario'");
    }
}

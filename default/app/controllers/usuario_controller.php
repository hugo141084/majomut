<?php




class usuarioController extends AppController {

    public function index($page = 1) {
       $usuario=new usuario();
        $this->result =$usuario->listar();
        $this->campos = array('#' => 'id',
            'USUARIO' => 'Usr',            
            'NOMBRE' => 'nombre_completo',
            'TIPO' => 'tipo',
            'ESTADO' => 'estatus',
        );
        $this->encabezado = "Usuarios";
    }

    public function crear() {
        $usuario=new usuario();
        $this->usuario= $usuario->find();
        if (Input::haspost('usuario')) {
           $datosu = new usuario(Input::post('usuario'));
            $datosu->Pas = md5(Input::post("clave"));
            $datosu->Estatus = "1";
            if($datosu->save()){
                 Redirect::to('usuario/index');
            }
               
        }
    }
    
    public function editar($id = null) {
        
        $usuarios = new usuario();
            $this->usuario = $usuarios->find_first($id);
           if (Input::haspost('usuario')) {
           $datosu = new usuario(Input::post('usuario'));
            $datosu->Pas = md5(Input::post("clave"));

            if($datosu->update()){
                 Redirect::to('usuario/index');
            }
               
        }
    }    

    public function bloquear($id) {
       $usuario=new usuario();
        $user =$usuario->find_first($id);
       $user->Estatus='0';
       if($user->update()){
                 Redirect::to('usuario/index');
            }
    }

    public function autocomplete() {
        View::template(NULL);
        View::select(NULL);
        if (Input::isAjax()) { //solo devolvemos los contribuyentes si se accede desde ajax 
            $busqueda = Input::post('busqueda');
            $empleados = Load::model('empleado')->obtenerNombreEmpleado($busqueda);
            die(json_encode($empleados)); // solo devolvemos los datos, sin template ni vista;
        }
    }
   
    
    
    
}

?>

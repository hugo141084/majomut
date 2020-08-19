<?php

Load::model( 'usuario');


class usuarioController extends AppController {

    public function index($page = 1) {
        $this->result = Load::model('usuario')->listar();
        $this->campos = array('#' => 'id',
            'USUARIO' => 'Usr',            
            'NOMBRE' => 'nombre_completo',
            'TIPO' => 'tipo',
            'ESTADO' => 'estatus',
        );
        $this->encabezado = "Usuarios";
    }

    public function crear() {
        $this->accion = "crear";
        $this->prefijo = "usuario";
        if (Input::post('funcion') == "guardar") {
            View::template(NULL);
            View::select(NULL);
            $respuesta = new stdClass();
            $array_var = array("usuario","clave","nombre","apellidoPaterno","apellidoMaterno","empleado_id","tipo");
            $datosu = Load::model('usuarios_administrador');
            foreach ($array_var as $datos)
                $datosu->$datos = Input::post("$datos");
            $datosu->clave = md5(Input::post("clave"));
            $datosu->estatus = "1";
            $datosu->empleado_id = Input::post("empleado_id");
            if($datosu->save()) 
                $respuesta->exito = "si";
            else
                $respuesta->exito = "no";
            echo json_encode($respuesta);
        }
    }
    
    public function editar($id = null) {
        $this->prefijo = "usuario";
        $this->accion = 'editar';
        view::select('crear');
        $usuarios = new usuario();
        if ($id != null) {
            $this->usuario = $usuarios->find_first($id);
            $this->usuario->contrasenia = "";
            $empleado = Load::model('empleado')->find_first($this->usuario->empleado_id);
            $arr_tit = array("id", "rfc", "curp", "nombres", "apellidop", "apellidom");
            foreach ($arr_tit as $dat) 
                $this->usuario->$dat = $empleado->$dat;
        } elseif (Input::post('funcion') == "guardar") {
            View::template(NULL);
            View::select(NULL);
            $respuesta = new stdClass();
            $array_var = array("nombre_usuario","tipo");
            $datosu = Load::model('usuario')->find_first(Input::post('id'));
            foreach ($array_var as $datos)
                $datosu->$datos = Input::post("$datos");
            if(Input::post("contrasenia") != "")
                $datosu->contrasenia = md5(Input::post("contrasenia"));
            if($datosu->update()) 
                $respuesta->exito = "si";
            else
                $respuesta->exito = "no";
            echo json_encode($respuesta);
        }
    }    

    public function cambiaestado() {
        View::template(NULL);
        View::select(NULL);
        $respuesta = new stdClass();
        $id = Input::post("id");
        $user = Load::model('usuario')->find_first($id);
        if(Input::post('tipo') == 1)
            $user->Estatus = "0";
        elseif(Input::post('tipo') == 2)
            $user->Estatus = "1";
        if($user->update()) 
            $respuesta->exito = "si";
        else
            $respuesta->exito = "no";
        echo json_encode($respuesta);
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
   
    public function buscarempleado() {
        View::template(NULL);
        View::select(NULL);
        if (Input::isAjax()) { 
            $texto = Input::post('texto');
            $sql = "SELECT count(e.id) veces   
                    FROM empleados e LEFT JOIN usuario u ON e.id = u.empleado_id
                   where u.empleado_id IS NULL AND concat_ws(' ',e.nombres, e.apellidop, e.apellidom) LIKE '%".$texto."%' AND e.estatus = '1'";
            $veces = Load::model('empleados')->find_by_sql($sql);
            if ($veces->veces > 0) {
                $sql = "SELECT distinct(e.id) id, concat_ws(' ',e.nombres, e.apellidop, e.apellidom) nombre  
                    FROM empleados e LEFT JOIN usuario u ON e.id = u.empleado_id
                   where u.empleado_id IS NULL AND concat_ws(' ',e.nombres, e.apellidop, e.apellidom) LIKE '%".$texto."%' AND e.estatus = '1'";
                $row = Load::model('empleados')->find_all_by_sql($sql);
                $listadocont = array();
                foreach ($row as $datos) 
                    $listadocont[] = array('id' => $datos->id, 'label' => $datos->nombre); 
            } else 
                $listadocont[] = array('id' => 0, 'label' => 'SIN VALORES'); 
            die(json_encode($listadocont));
        }
    }
    
    public function buscaempleado() {
        View::template(NULL);
        View::select(NULL);
        $respuesta = new stdClass();
        $tuplac = Load::model('empleados')->find_first(Input::post('id'));
        $respuesta->rfc = $tuplac->rfc;
        $respuesta->curp = $tuplac->curp;
        $respuesta->apellidop = $tuplac->apellidop;
        $respuesta->apellidom = $tuplac->apellidom;
        $respuesta->nombres = $tuplac->nombres;
        echo json_encode($respuesta);
    }
    
    
}

?>

<?php








class empleadoController extends AppController {

    public function index($page = 1) {
        $empleado = new empleado();
        $this->empleado = $empleado->listar();
        $this->campos = array('ID' => 'id',
            'NOMBRE' => 'nombre_completo',
            'RFC' => 'rfc',
            'PERFIL' => 'escolaridad',
            'E-MAIL' => 'correo',
            'TELEFONO' => 'telefono',
        );
        $this->encabezado = "";
        $this->result1 = $empleado->listarInactivos();
    }

    public function crear() {
        
        $this->accion = 'crear';
        $empleado = new empleado();
        $empleado_datos = new empleado(Input::post('empleado')); 
        $cont = $empleado_datos->verificaRFC($empleado_datos->rfc);
        
        if (Input::hasPost('empleado')) {
            if($cont->num > 0){
                Flash::warning('Ya ha sido registrado un empleado con este rfc'); 
            }
            else{
                
                    $this->empleado = $empleado->guardarDatosEmpleados();
                
            }
        }
        $this->empleado = new empleado();
    }

    public function editar($id = null) {
        $this->accion = 'editar';
        $empleado = new empleado();
        view::select('crear');

        if ($id != null) {
            $emp = $empleado->find_by_id($id);
            $this->empleado = $emp;
            
            
            
            $empleado_id = $id;
            
        } else if (Input::hasPost('empleado')) {
            $empleadoEditados = new empleado(Input::post('empleado'));
            $empleadoEditados->fecha_nac = strftime("%Y-%m-%d", strtotime($empleadoEditados->fecha_nac));
            $empleadoEditados->fecha_ingreso = strftime("%Y-%m-%d", strtotime($empleadoEditados->fecha_ingreso));
            $empleadoEditados->fecha_baja = strftime("%Y-%m-%d", strtotime($empleadoEditados->fecha_baja));
            $empleadoEditados->nombre = ($empleadoEditados->nombre);
            $empleadoEditados->ape_pat = ($empleadoEditados->ape_pat);
            $empleadoEditados->ape_mat = ($empleadoEditados->ape_mat);
            $empleadoEditados->nombre_completo = $empleadoEditados->nombre." ".$empleadoEditados->ape_pat." ".$empleadoEditados->ape_mat;
            $empleadoEditados->rfc = ($empleadoEditados->rfc);
            $empleadoEditados->curp = ($empleadoEditados->curp);
            $empleadoEditados->ife = ($empleadoEditados->ife);
            $empleadoEditados->tipo_contrato = ($empleadoEditados->tipo_contrato);
            $empleadoEditados->numero_seguro = ($empleadoEditados->numero_seguro);
            $empleadoEditados->escolaridad = ($empleadoEditados->escolaridad);
            $empleadoEditados->domicilio = ($empleadoEditados->domicilio);
            $empleadoEditados->etiqueta = ($empleadoEditados->etiqueta);
            
            if ($empleadoEditados->save() ) {
                
                Input::delete();
               
                Flash::valid('Operación exitosa, se ha actualizado el Empleado');
                ?>
                <script> 
                window.location.replace("<?php echo PUBLIC_PATH."empleado"; ?>");
                </script>
                <?php
            } else {
                $this->empleado = Input::post('empleado');
                Flash::error('Falló Operación');
            }
        }
    }
    
    /**
     * Permite desactivar los empleado.
     */
    public function bloquear($id = NULL) {
        $this->accion = 'borrar';
        if ($id != null) {
            
            $empleado = new empleado();
            $UsuarioEmpleado = new usuario();
            $users = $UsuarioEmpleado->count(" tipo='1' and empleado_id!='$id' AND estatus='1'");
            if($users>0){
                $this->empleado = $empleado->find_by_id($id);
                $empleado->estatus = '0';
                $empleado->fecha_baja =date('Y-m-d H:m:s');
                if($empleado->update()){
                    /* Bloquea al usuario */
                    $usuario_busca = $UsuarioEmpleado->find("conditions: empleado_id='$id'");
                    foreach ($usuario_busca as $usuarioEmp){
                        $this->result = $UsuarioEmpleado->bloqueaUsuario($id);
                       
                    }
                    Flash::valid('Operación Exitosa');
                    Input::delete();
                    Redirect::to('empleado/');
                }
            } else {
                Flash::error("No puede deshabilitar al empledo, debido a que es el unico usuario Administrador, debe dar de alta otro usuario primero.");
                Input::delete();
                Redirect::to('empleado/');
            }
        }
        view::select(NULL);
    }
    
    
    /**
     * Permite reactivar a los empleado inactivos.
     */
    public function activar($id = NULL) {
        $this->accion = 'reactivar';
        $empleado = new empleado();
        if ($id != null) {
            $this->empleado = $empleado->find_by_id($id);
            $empleado->estatus = '1';
            if($empleado->update(Input::post('empleado'))){
                Flash::valid('Operación Exitosa');
                Input::delete();
                Redirect::to('empleado');
            } else {
                Flash::warning('No se ha podido ejecutar la operacion.');
                Input::delete();
                Redirect::to('empleado');
            }
        }
        view::select(NULL);
       Redirect::to('empleado');
    }
    
    public function desactivados() {
        $empleado = new empleado();
        $this->empleado = $empleado->listar(0);
        $this->campos = array('ID' => 'id',
            'NOMBRE' => 'nombre',
            'PUESTO' => 'cargo',
            'RFC' => 'rfc',
        );
        $this->encabezado = "Empleados Desactivados";
    }
     public function autocomplete() {
        View::template(NULL);
        View::select(NULL);
        if (Input::isAjax()) { //solo devolvemos los contribuyentes si se accede desde ajax 
            $busqueda = Input::post('busqueda');
            $empleado = Load::model('empleado')->buscaEmpleado($busqueda);
            die(json_encode($empleado)); // solo devolvemos los datos, sin template ni vista;
        }
    }

     
   
}

?>

<?php


class clienteController extends AppController {

    protected function before_filter() {
        $this->limit_params = false;
        // Si es AJAX enviar solo el view
        if (Input::isAjax()) {
            View::template(NULL);
        }
    }

    public function index() {
        $this->cliente = Load::model('cliente')->find();
        $this->campos = array(
            utf8_encode('RFC') => 'rfc',
            utf8_encode('NOMBRE') => 'nombrecompleto',
            utf8_encode('PROPIETARIO/REPRESENTANTE LEGAL') => 'propietario',
            utf8_encode('Email') => 'correoelectronico',
            utf8_encode('TELEFONO') => 'telefono'
        );
        $cliente=new cliente();
        $this->result = $cliente->listarInactivo();
        $this->encabezado = "cliente";
    }

    public function crear() {
        
        $this->accion = "crear";
        $this->prefijo = "cliente";
         $this->accion="crear";
        
        $cliente = new cliente();
        $this->cliente = $cliente->find();

        if (Input::hasPost('cliente')) {

            $this->cliente = $cliente->guardarDatos();
        }
    }

    public function editar($id = null) {
        View::select('crear');
        $this->prefijo = "cliente";
        $this->accion = "editar";
        if ($id != NULL) {
            $this->cliente = Load::model('cliente')->find_first($id);
          
        } else  {
           $datosc = new cliente(Input::post('cliente'));
            
            if ($datosc->update())
                echo "<script>  alert ('Registro Actualizado....!');</script>"; 
            else
               echo "<script>  alert ('Problemas al Actualizar el Registro....!');</script>";
            
        } 
    }

    

    public function listado($page = 1) {
        $datos = new cliente();
        // partial
        $this->result = $datos->listarProcesamiento();
        $this->campos = array(
            '#' => 'id',
            'DESCRIPCION' => 'descripcion',
            'FECHA CREACION' => 'fechacreacion',
            'AVANCE' => 'dias_adicionales');
        $this->encabezado = "Plan de Manejo Orgánico (PMO) PROCESAMIENTO";

        //buscar si existe la poliza de Presupuesto de Egresos  
    }

    public function solicitud() {
        $idUsuario = Session::get('Id');
        $contenedor = new usuario();
        $buscaEntidad = $contenedor->buscaUsuario($idUsuario);
        $cliente = new cliente();

        $avanza = FALSE;
        $this->prefijo = "cliente";
        $this->{$this->prefijo} = $cliente->buscaEnte($buscaEntidad->id);
        $this->responsable = "responsable";
        $responsable = new responsableoperacion();
        $this->{$this->responsable} = $responsable->find_first("cliente_id=$buscaEntidad->id");
        $this->alterna = "alterna";

        if (Input::hasPost('responsable')) {
            $operacion = new responsableoperacion();
            $operacion = $operacion->guardarResponsable();
            $avanza = TRUE;
        }
        if (Input::hasPost('alterna')) {
            $alterna = new ubicacionalterna();
            $guarda = $alterna->guardarUbicacion();
            $avanza = TRUE;
        }
        if (Input::hasPost('tipo')) {
            $alterna = new tipoproduccion();
            $guarda = $alterna->guardarTipoProduccion();
            $avanza = TRUE;
        }
        if (Input::hasPost('estandar')) {
            $alterna = new estandares();
            $guarda = $alterna->guardarEstandar();
            $avanza = TRUE;
        }
         Input::delete();
       
       if($avanza==TRUE) Redirect::to('planGanaderia/historial/'.$idPlanOrganico);
    }
  
}

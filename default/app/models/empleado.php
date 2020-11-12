<?php

class empleado extends  ActiveRecord {
    
    protected function initialize() {
    }
     public function listar($estatus=1) {
        $sql = "SELECT *
        FROM empleado E
        where  E.estatus = '$estatus' order by nombre desc ";
        return $this->find_all_by_sql($sql);
    }
    public function listarInactivos($estatus=0) {
        $sql = "SELECT *
        FROM empleado E
        where  E.estatus = '$estatus' order by nombre asc ";
        return $this->find_all_by_sql($sql);
    }
    public function buscarEmpleado($empleadoId){
        return $this->find_by_sql("SELECT ID,concat_ws(' ',nombre, ape_pat, ape_mat) as nombre,  etiqueta
FROM empleado
WHERE id ='' ");
        
    }
    public function listarFiltro($estatus=1) {
        $sql = "SELECT e.id,e.nombre_completo FROM empleado E right join inspector I on I.empleado_id= E.id ";
        return $this->find_all_by_sql($sql);
    }
    public function verificaRFC($rfc) {
        $sql = "Select count(rfc) as num from empleado where rfc='$rfc' ";
        return $this->find_by_sql($sql);
    }
     public function guardarDatosEmpleados() {
    

            //para dar de alta el nuevo empleado en la tabla de empleados
            $empleados = new empleado(Input::post('empleado'));

            //para dar de alta el nuevo empleado en la tabla de sujetos                
            
            if ($empleados->nombre != '' && $empleados->ape_pat != '') {//porque si no hay nombre no tiene caso guardar
                $empleados->nombre = ($empleados->nombre);
                $empleados->ape_pat = ($empleados->ape_pat);
                $empleados->ape_mat = ($empleados->ape_mat);
                $empleados->nombre_completo = ($empleados->nombre)." ".($empleados->ape_pat)." ".($empleados->ape_mat);
                $empleados->rfc = ($empleados->rfc);
                $empleados->curp = ($empleados->curp);
                $empleados->ife = ($empleados->ife);
               
                $empleados->tipo_contrato = ($empleados->tipo_contrato);
                $empleados->numero_seguro = ($empleados->numero_seguro);
                $empleados->escolaridad = ($empleados->escolaridad);
                $empleados->domicilio = ($empleados->domicilio);

                
              
                    if($empleados->fecha_nac != ''){ $empleados->fecha_nac = strftime("%Y-%m-%d", strtotime($empleados->fecha_nac)); } else { $empleados->fecha_nac=NULL; }
                    if($empleados->fecha_ingreso != ''){ $empleados->fecha_ingreso = strftime("%Y-%m-%d", strtotime($empleados->fecha_ingreso)); } else { $empleados->fecha_ingreso=NULL; }
                    if($empleados->fecha_baja != ''){ $empleados->fecha_baja = strftime("%Y-%m-%d", strtotime($empleados->fecha_baja)); } else { $empleados->fecha_baja=NULL; }
                    //$empleados->fecha_nac = strftime("%Y-%m-%d", strtotime($empleados->fecha_nac));
                    //$empleados->fecha_ingreso = strftime("%Y-%m-%d", strtotime($empleados->fecha_ingreso));
                    //$empleados->fecha_baja = strftime("%Y-%m-%d", strtotime($empleados->fecha_baja));
                    $empleados->estatus = '1'; //estatus activo=1
                    
                    if ($empleados->save()) {  //GUARDAMOS EL EMPLEADO CORECTAMENTE.. Y TADAA!!   
                        
                        
                             ?>
                <script> 
                window.location.replace("<?php echo PUBLIC_PATH."empleado"; ?>");
                </script>
                <?php
                        
                    } else {
                        $this->empleados = $empleados;
                        Flash::error('Falló Operación');
                    }
                
            }
        
    }
   
    
}

<?php
class cliente extends ActiveRecord {

    //put your code here
    protected function initialize() {
    }   
    
    public function listar() {
        return $this->find('conditions: estatus = "1"', '');
    } 
    public function listarInactivo() {
        return $this->find('conditions: estatus = "0"', 'order: id asc');
    }
    public function datoEnte($idEntidad) {
        return $this->find_by_id($idEntidad);
    }  
     public function actualizaAutorizado($cadena){
        $this->update_all("estatus='1'","texto_activacion='$cadena'");
    }
    public function guardarDatos(){
        $producto = new cliente(Input::post('cliente'));
        $producto->costo_promedio=$producto->costo; 
        $producto->fecha_usuario=date('Y-m-d H:i:s'); 
        $producto->usuario_id=Session::get('id'); 
        $producto->estatus='1'; 
        
        if( $producto->save()){
         // echo "<script>  alert ('Registro Insertado....!');</script>"; 
          Redirect::to('cliente/index');
        }
    }
    public function before_save() {
        foreach($this->fields as $field) {
            if(strpos(" " . $this->_data_type[$field], "varchar"))
                $this->$field = $this->$field;
        }
    }
    public function buscaCliente($idEntidad) {
        return $this->find_by_sql("select * from  cliente    where id=$idEntidad");
    }
    
}
?>

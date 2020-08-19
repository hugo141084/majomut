<?php
class series_folios extends ActiveRecord{

    protected function initialize(){
            $sesionBD= Session::get('baseMunicipio');    
            $this->set_database($sesionBD);                   

    }


   public function buscarConsecutivo($tipo){
   return $this->find_by_sql("select * from series_folios where tipo='".$tipo."' ");

    }
    public function incrementarConsecutivo($tipo){
        $this->update_all("consecutivo=consecutivo+1","tipo='$tipo'");
    }
    public function datosSerie($serie,$folio){
      return  $this->find_by_sql("select * from series_folios where serie='$serie' and  ($folio >= folio_inicial and $folio<= folio_final)");
    }
    
    public function before_save() {
        foreach($this->fields as $field) {
            if(strpos(" " . $this->_data_type[$field], "varchar"))
                $this->$field = strtoupper($this->$field);
        }
    }
}
 ?>
<?php
class series_folios extends ActiveRecord{

    


   public function buscarConsecutivo($tipo){
   return $this->find_by_sql("select * from series_folios where tipo='".$tipo."' ");

    }
    public function listarCot(){
   return $this->find("tipoComprobante='cotizacion'");

    }
    public function incrementarConsecutivo($tipo){
        $this->update_all("consecutivo=consecutivo+1","tipo='$tipo'");
    }
    
    public function incrementarConsecutivoId($id){
        $this->update_all("consecutivo=consecutivo+1","id='$id'");
    }
    
    public function datosSerie($serie,$folio){
      return  $this->find_by_sql("select * from series_folios where serie='$serie' and  ($folio >= folio_inicial and $folio<= folio_final)");
    }
    
    
}
 ?>
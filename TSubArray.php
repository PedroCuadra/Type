<?php
namespace Type;
class TSubArray extends Type{
  public static $arraySplitter = ";";
  public $newArray;

  function __construct($base, $struct){
    if(!($base instanceof TArray))
      throw new \Exception('$base is not TArray');

    // Si TArray(TAny) entonces no importa que argumento vaya 
    $this->newArray = $base;
    if ($this->newArray->getTypes() instanceof TAny){
      return;
    }

    // "!a,b;&f;;&t;c,d" => ["!a,b",false,false,true,"c,d"] 
    //
    if( gettype($struct) === "string"){
      $struct = explode(self::$arraySplitter,$struct);
      foreach($struct as &$rules){
        switch($rules){
        case "&t": $rules = true;
        case "&f": $rules = false;
        case "":   $rules = false;
        }
      }
    }

    $countStruct = count($struct);
    $tManyTypes = $this->newArray->getTypes()->getTypes();
    if($countStruct === count($tManyTypes)){
      for($i = $countStruct - 1; $i >=0; $i-- ){
        if($struct[$i] === false){
          $tManyTypes = array_merge(
            array_slice($tManyTypes,0,$i),
            array_slice($tManyTypes,$i+1)
          );
        }elseif(gettype($struct[$i])==="string"){
          $tManyTypes[$i] = new TSubObject($tManyTypes[$i],$struct[$i]);
        }
        // Para arrays de arrays; not tested
        elseif(gettype($struct[$i])==="array"){
          $tManyTypes[$i] = new TSubArray($tManyTypes[$i],$struct[$i]);
        }
      }
    }else{
      throw new \Exception('$countStruct !== count($types)');
    }

    //... ver EntrustRequest.r.php:49

  }

  private function buildValidator($index,&$typeValidator){
    $typeValidator = $this->newArray->getTypes()->getTypes();

  }

  function validate($x){
    $this->newArray->validate($x);
    return $this;
  }

  function getData(){
    return $this->newArray->getData();
  }

  function getTypes(){

  }
}
?>
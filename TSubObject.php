<?php
namespace Type;
class TSubObject extends Type{
  public static $paramSeparator = ",";
  public static $excludingSymbol = "!";

  public $newObject;

  function __construct($base, $struct){
    if(!($base instanceof TObject))
      throw new \Exception('$base is not TObject');
    
    $this->newObject = $base; 
    if($struct===true)
      return;
    
    $excluding = false;
    $includingKeys = [];
    // "!bar,baz" => ["bar","baz"] (excluyente)
    // "bar,baz" => ["bar","baz"] (incluyente)
    if(gettype($struct) === "string"){
      if($struct[0] === self::$excludingSymbol){
        $excluding=true;
        $struct = substr($struct,1);
      }
      $struct = explode(self::$paramSeparator,$struct);
    }


    // ["bar","baz"] => ["bar"=>true, "baz"=>true] 
    if(is_array($struct) and !Type::isAssoc($struct)){
      foreach($struct as $key){
        $includingKeys[$key] = true;
      }
    }else{
      foreach($struct as $key=>&$value){
        $includingKeys[$key] = $value;
      }
    }


    // Si no pudo parsearse en los pasos anteriores
    // Lanza excepción
    if(!is_array($struct)){
      throw new \Exception('$struct is not Valid.');
    }

    // Borrar claves innecesarias
    $onlyKeys = array_keys($includingKeys);
    foreach(array_keys($this->newObject->getDefinition()) as &$key){
      // si es excluyente ("!foo,bar") y la clave está en el array ("foo")
      // o si no es excluyente ("foo,bar") y la clave no está en el array ("baz")
      // se quita la clave
      $inArray = in_array($key,$onlyKeys);
      if($excluding == $inArray){
        $this->newObject->deleteKey($key);
      }
    }

    // invertir $includingKeys si $excluding === true
    if($excluding === true){
      $keys = array_keys($this->newObject->getDefinition());
      $includingKeys = [];
      foreach($keys as $key ){
        $includingKeys[$key] = true;
      }
    }

    // Recursividad de TSubObject
    foreach($this->newObject->getDefinition() as $noKey=>&$noVal){
      //if(in_array(gettype($includingKeys[$noKey]),["string","array"])){
      //  $noVal = new TSubObject($noVal,$includingKeys[$noKey]);
      //}
      $incKey = &$includingKeys[$noKey];

      if($incKey === true)
        continue;

      if($noVal instanceof TObject){
        $noVal = new TSubObject($noVal,$incKey);
      }
      elseif($noVal instanceof TArray){
        $noVal = new TSubArray($noVal,$incKey);
      }
    }
  }

  function validate($x){
    $this->newObject->validate($x);
    return $this;
  }

  function getData(){
    return $this->newObject->getData();
  }
}
?>
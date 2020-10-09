<?php
namespace Type;
class TMany extends Type{
  private $types;
  function __construct($types){
    $this->types = is_array($types) ? $types : [ $types ];
  }

  function validate($x){
    $lastErrorMessage = null;
    foreach($this->types as &$type){
      try{
        if($type instanceof Type){
          $this->validatedData = $type
            ->validate($x)
            ->getData();
          return $this;
        }else if ($x === $type){
          $this->validatedData = $x;
          return $this;
        }
      }catch(Exception $e){
        $lastErrorMessage = $e->getMessage();
      }
    }
    if(!$lastErrorMessage)
      $lastErrorMessage = "INVALID_VALUE";
    throw new TypeException($lastErrorMessage . TypeException::TRACE_SEPARATOR . $this->index);
  }

  private $default;
  function hasDefault(){
    foreach($this->types as &$type){
      if($type instanceof Type) 
        continue;
      $this->default = $type;
      return true;
    }
    return false;
  }

  function getDefault(){
    return $this->default;
  }

}
?>
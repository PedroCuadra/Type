<?php
namespace Type;
class TEnum extends Type{
  private $values;
  private $errorMessage;

  function __construct(array $values, string $errorMessage = 'NO_ENUM'){
    $this->values = $values;
    $this->errorMessage = $errorMessage;
  }
  
  function validate($x){
    if(!in_array($x,$this->values,true))
      throw new TypeException($this->errorMessage);
    $this->validatedData = $x;
    return $this;
  }
}
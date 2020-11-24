<?php
namespace Type;
class TTransform extends Type{

  private $transformFunction;
  private $TValidator;
  /**
   * @param TValidator typeof Type
   * @param TransformFunction fn($x)=>y
   */
  function __construct($TValidator,$transformFunction){
    $this->TValidator = $TValidator;
    $this->transformFunction = $transformFunction;
  }

  function validate($x){
    $t = $this->transformFunction;
    $this->validatedData = $t($this->TValidator->validate($x)->getData());
    return $this;
  }
}
?>
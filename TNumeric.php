<?php
namespace Type;
abstract class TNumeric extends Type{
  protected $range;
  protected $noNumericErrorMessage;
  protected $noRangeErrorMessage;

  function __construct($range = null){
    $this->range = $range;
  }

  function validate($x){
    if(is_numeric($x)){
      $this->inRange($x);
      return $this;
    }
    throw new TypeException($this->noNumericErrorMessage);
  }

  function inRange($x){
    $range = &$this->range;
    $callable = is_callable($range);
    if(!$callable or ( $callable and $range($x))){
      $this->validatedData = $x;
      return $this;
    }else{
      throw new TypeException($this->noRangeErrorMessage);
    }
  }
}
?>
<?php
namespace Type;
class TDateTime extends Type{
  private $format;
  private $validationFunction;

  function __construct($format = 'Y-m-d',$validationFunction = null){
    $this->format = $format;
    $this->validationFunction = $validationFunction;
  }

  function validate($x){
    $x = (new TString)->validate($x)->getData();
    $date = \DateTime::createFromFormat($this->format, $x);
    if(!$date)
      throw new TypeException('INVALID_DATETIME_FORMAT');

    $xx = $date->format($this->format);
    if($date and ($xx === $x)){
      $val = $this->validationFunction;
      $cal = is_callable($val);
      if($cal and $val($x) or !$cal )
        $this->validatedData = $xx;
      else
        throw new TypeException('DATETIME_NOT_IN_RANGE');
    }else
      throw new TypeException('INVALID_DATETIME');
    return $this;
  }
}
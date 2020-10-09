<?php
namespace Type;
class TFloat extends TNumeric{
  function __construct($range = null){
    parent::__construct($range);
    $this->noNumericErrorMessage = 'NO_FLOAT';
    $this->noRangeErrorMessage = 'FLOAT_NOT_IN_RANGE';
  }

  function validate($x){
    $x = (float) parent::validate($x)->getData();
    parent::inRange($x);
    return $this;
  }
}
?>
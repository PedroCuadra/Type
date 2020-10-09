<?php
namespace Type;
class TInt extends TNumeric{
  function __construct($range = null){
    parent::__construct($range);
    $this->noNumericErrorMessage = 'NO_INT';
    $this->noRangeErrorMessage = 'INT_NOT_IN_RANGE';
  }

  function validate($x){
    $x = (int) parent::validate($x)->getData();
    parent::inRange($x);
    return $this;
  }
}
?>
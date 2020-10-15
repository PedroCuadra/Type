<?php
namespace Type;
class TFloat extends TNumeric{
  function __construct($range = null){
    parent::__construct($range);
    $this->noNumericErrorMessage = 'NO_FLOAT';
    $this->noRangeErrorMessage = 'FLOAT_NOT_IN_RANGE';
  }

  function validate($x){
    $this->validatedData = (float) parent::validate($x)->getData();
    return $this;
  }
}
?>
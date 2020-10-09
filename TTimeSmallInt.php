<?php
namespace Type;
class TTimeSmallInt extends Type implements TypeEncodable{
  const TIME_REGEX = '/^([01][0-9]|2[0-3])\:([0-5][0-9])$/';

  function validate($x){
    $x = (new TRegex(self::TIME_REGEX,'INVALID_TIME'))
      ->validate($x)
      ->getData();
    $this->validatedData = $this->encode($x);
    return $this;
  }

  function encode($x){
    return ((int)$x[1]) * 60 + ((int)$x[2]);
  }

  function decode($x){
    return ((int)($x/60)) . ':' . ($x % 60);
  }
}
?>
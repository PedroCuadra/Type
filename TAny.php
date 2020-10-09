<?php
namespace Type;
class TAny extends Type{
  function validate($x){
    $this->validatedData = $x;
    return $this;
  }
}
?>

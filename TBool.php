<?php
namespace Type;
class TBool extends Type{
  function validate($x){
    if (in_array( $x, [true,  'true',  1],true)){
      $this->validatedData = true; 
    }elseif(in_array( $x, [false, 'false', 0],true)){
      $this->validatedData = false; 
    }else{
      throw new TypeException('NO_BOOL');
    }
    return $this;
  }
}
?>
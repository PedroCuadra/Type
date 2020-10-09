<?php
namespace Type;
class TString extends Type{
  const NO_STRING = 'NO_STRING';
  const ERROR = 1;
  const CUT   = 2;

  private $maxlen;
  private $action;

  function __construct($maxlen = null, $action = self::CUT){
    $this->maxlen = $maxlen;
    $this->action = $action;
  }

  function validate($x){
    if ( gettype($x) == 'string'){
      if ( $this->maxlen == null or strlen($x) <= $this->maxlen ){
        $this->validatedData = $x;
      }else{
        if($this->action == self::ERROR){
          throw new TypeException( 'STRING_MAX_LEN.' . $this->maxlen );
        }else{
          $this->validatedData = substr($x,0,$this->maxlen);
        }
      }
    }else{
      throw new TypeException( TString::NO_STRING );
    }    
    return $this;
  }
}
?>

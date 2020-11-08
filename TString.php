<?php
namespace Type;
class TString extends Type{
  const NO_STRING = 'NO_STRING';
  const EMPTY_STRING = 'EMPTY_STRING';
  const ERROR = 1;
  const CUT   = 2;
  const NO_EMPTY = 4;

  private $maxlen;
  private $action;

  function __construct($maxlen = null, $action = self::CUT | self::NO_EMPTY){
    $this->maxlen = $maxlen;
    $this->action = $action;
  }

  function validate($x){
    if ( gettype($x) == 'string'){
      if ( $this->maxlen == null || strlen($x) <= $this->maxlen ){
        if( ($this->action & self::NO_EMPTY) && strlen($x) === 0){
          throw new TypeException(TString::EMPTY_STRING);
        }else{
          $this->validatedData = $x;
        }
      }else{
        if($this->action & self::ERROR){
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

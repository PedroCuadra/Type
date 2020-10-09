<?php
namespace Type;
class TRegex extends Type{
  const REGEX_EMAIL = '/.+@.+\..+/';
  const ERROR_EMAIL = 'REGEX_INVALID_EMAIL';

  private $regex;
  private $errorMessage;

  function __construct($regex, $errorMessage = 'INVALID_REGEX'){
    $this->regex = $regex;
    $this->errorMessage = $errorMessage;
  }
  
  function validate($x){
    if(is_string($x)){
      if ( preg_match($this->regex,$x,$match) ){
        $this->validatedData = $match;
      } else {
        $this->validatedData = null;
        throw new TypeException($this->errorMessage);
      }
    }else{
      throw new TypeException( TString::NO_STRING );
    }
    return $this;
  }

}
?>
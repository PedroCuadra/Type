<?php
namespace Type;
class TRegex extends Type{
  const REGEX_EMAIL = '/.+@.+\..+/';
  const ERROR_EMAIL = 'REGEX_INVALID_EMAIL';

  private $regex;
  private $errorMessage;
  private $returnMatchIndex;

  function __construct($regex, $errorMessage = 'INVALID_REGEX',$returnMatchIndex = null){
    $this->regex = $regex;
    $this->errorMessage = $errorMessage;
    $this->returnMatchIndex = $returnMatchIndex;
  }
  
  function validate($x){
    if(is_string($x)){
      if ( preg_match($this->regex,$x,$match) ){
        if($this->returnMatchIndex===null){
          $this->validatedData = $match;
        }else{
          $this->validatedData = $match[$this->returnMatchIndex];
        }
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
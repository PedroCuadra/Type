<?php 
namespace Type;
class TRecursive extends Type {
  private $struct;

  function setStruct( $struct ){
    if($struct instanceof TObject or $struct instanceof TArray )
      $this->struct = $struct;
    return $this;
  }

  function validate($x){
    $this->struct->validate($x);
    return $this;
  }

  function getData(){
    return $this->struct->getData();
  }
}
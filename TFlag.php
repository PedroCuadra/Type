<?php
namespace Type;
class TFlag extends Type implements TypeEncodable{
  private $elements;

  function __construct(array $elements){
    $this->elements = array_unique($elements);
  }

  function validate($x){
    $x = (new TArray(new TString))
      ->validate($x)
      ->getData();
    $x = array_unique($x);
    $x = $this->encode($x);
    $this->validatedData = $x;
    return $this;
  }

  function getElements(){
    return $this->elements;
  }

  function encode($x){
    $encoded = 0;
    foreach($x as &$value){
      $index = array_search($value,$this->elements);
      if($index === false){
        throw new TypeException('NO_FLAG.'.$value);
      }else{
        $encoded |= 1 << $index;
      }
    }
    return $encoded;
  }

  function decode($x){
    $decoded = [];
    $value = $x;
    $index = 0;
    while($value>0){
      if($value % 2)
        array_push($decoded,$this->elements[$index]);        
      $index ++;
      $value = $value >> 1;
    }
    return $decoded;
  }
}
?>
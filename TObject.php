<?php
namespace Type;
class TObject extends Type{
  private $definition;
  private $validationFunction;

  function __construct(array $definition, $validationFunction = null){
    if( (!self::isAssoc($definition) or !is_array($definition)) and count($definition))
      throw new Exception('Definition is not associative');
    
    $keys = array_keys($definition);
    $this->definition = [];
    foreach($definition as $key => &$def){
      //$this->definition[$key] = ($def instanceof TMany or is_callable($def)) ? $def : new TMany($def);
      $this->definition[$key] = is_array($def) ? new TMany($def) : $def; 
    }
    $this->validationFunction = $validationFunction;
  }

  function validate($x){
    // Definición de atributos
    if( (!$x and !is_array($x)) or (!self::isAssoc($x) or !is_array($x)) and count($x))
      throw new TypeException('NO_OBJECT');

    $bufferData = [];
    $xKeys = array_keys($x);
    foreach($this->definition as $key => &$types){
      if(is_callable($types)){
        $types = $types($bufferData);
      }

      if(in_array($key,$xKeys)){
        try{
          if($types !== null)
            $bufferData[$key] = $types->validate($x[$key])->getData();
        }catch( \Exception $e ){
          throw new TypeException( $e->getMessage() . TypeException::TRACE_SEPARATOR . $key);
        }
      }else {
        if($types instanceof TMany and $types->hasDefault())
          $bufferData[$key] = $types->getDefault();
        else if($types !== null)
          throw new TypeException('NO_KEY.'.$key);
      }
    }
    $this->validatedData = &$bufferData;

    // Funcion de validacion entre elementos
    $val = &$this->validationFunction;
    if(is_callable($val))
      $val($x);

    return $this;
  }

  function getDefinition(){
    return $this->definition;
  }

  function deleteKey($key){
    unset($this->definition[$key]);
  }
}


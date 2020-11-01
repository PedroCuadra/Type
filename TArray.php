<?php
namespace Type;
class TArray extends Type{
  
  private $maxsize;
  private $types;

  function __construct($types = null, int $maxsize = null){
    if($types == null)
      $this->types = new TAny;
    elseif(!($types instanceof TMany))
      $this->types = new TMany($types);
    $this->maxsize = $maxsize <= 0 ? null : $maxsize;
  }

  /**
   * Evalua que $x sea un array de uno o más tipos
   * @param type Tipos posibles dentro del array $x. Se puede obviar el array si es uno solo.
   * @param maxsize:int tamaño máximo del array $x
   * Ejemplo:
   * $struct = Type::ARRAY([
   *   Type::INT(),
   *   Type::STRING(10)
   * ]);
   * 
   * entonces $struct solo acepta enteros y strings de largo 10
   */
  function validate($x){
    if(parent::isAssoc($x) or !is_array($x))
      throw new TypeException('NO_ARRAY');

    if($this->maxsize !=null and count($x) > $this->maxsize)
      throw new TypeException('MAX_LEN.'.$this->maxsize);

    $xx = [];
    foreach($x as $index => &$element)
      try{
        $xx [] = $this->types->validate($element)->getData();
      }catch( \Exception $e ){
        throw new TypeException( $e->getMessage() . TypeException::TRACE_SEPARATOR . $index);
      }
    $this->validatedData = &$xx;
    return $this;
  }

  function getTypes(){
    return $this->types;
  }
}
<?php
namespace Type;
class TypeException extends \Exception{
  const TRACE_SEPARATOR = '<<<';
};
abstract class Type{
  protected $validatedData;
  protected $index; 

  function setIndex($index){
    $this->index = $index;
    return $this;
  }

  protected static function isAssoc($array)
  {
    if(!is_array($array)) 
      return false;
    $keys = array_keys($array);
    return array_keys($keys) !== $keys;
  }

  protected static function variableName( &$var, $scope=false, $prefix='UNIQUE', $suffix='VARIABLE' ){
    if($scope) {
        $vals = $scope;
    } else {
        $vals = $GLOBALS;
    }
    $old = $var;
    $var = $new = $prefix.rand().$suffix;
    $vname = FALSE;
    foreach($vals as $key => $val) {
        if($val === $new) $vname = $key;
    }
    $var = $old;
    return $vname;
  }
  
  /**
   * Función de validación del tipo.
   * @return this
   */
  public abstract function validate($x);

  public function getData(){
    return $this->validatedData;
  }
}
?>
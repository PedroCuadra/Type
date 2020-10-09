<?php
namespace Type;
class TRut extends Type implements TypeEncodable{

  const RUT_ERROR = "RUT_INVALIDO";

  const NINGUNO     = 0;
  const GUION       = 1;
  const PUNTO_GUION = 2;
  const FLEXIBLE    = 3;

  const RUT_REGEX = [
    self::NINGUNO     => "/^\d+k?$/",
    self::GUION       => "/^\d+-[\dk]$/",
    self::PUNTO_GUION => "/^\d{1,3}(?:\.\d{3})*-[\dk]$/",
    self::FLEXIBLE    => "/^(?:(?:\d{1,3}(?:\.\d{3})+)-(?:\d|k)|(?:\d+k|\d+-?[\dk]))$/i"
  ];


  private $formaValidacion;
  private $decodeFlag;

  function __construct(int $formaValidacion = self::NINGUNO ){
    if(!in_array($formaValidacion, array_keys( self::RUT_REGEX )))
      throw new \Exception('Forma no correcta');

    $this->formaValidacion = $formaValidacion;
    $this->decodeFlag = self::NINGUNO;
  }


  function validate($x){
    $x = (new TRegex(
        self::RUT_REGEX[$this->formaValidacion],
        self::RUT_ERROR
      ))
      ->validate($x)
      ->getData()[0];
    
    //quitar puntos
    $this->validatedData = $this->encode($x);

    return $this;
  }

  function encode($x){
    $x = str_replace(['.','-'],'',$x);
    $len          = strlen($x) - 1;
    $rutSinDigito = substr($x,0,$len);
    $verifEntrada = strtolower($x[$len]);

    // https://es.wikipedia.org/wiki/Anexo:Implementaciones_para_algoritmo_de_rut    
    $M = 0;
    $S = 1;
    $T = (int) $rutSinDigito;
    for(; $T; $T = (int)($T/10) )
       $S = ( $S + $T % 10 * ( 9 - $M++ % 6 ) ) % 11;

    $realVerificador = $S ? $S-1 : 'k'; 

    if( (int) $verifEntrada === (int) $realVerificador)
      return $rutSinDigito . $realVerificador;
    else
      throw new TypeException(self::RUT_ERROR);    
  }

  function decode($x){
    $number = (float) substr($x,0,strlen($x)-1);
    $thousand = $this->decodeFlag === self::PUNTO_GUION ? '.' : '';
    $verif = ( $this->decodeFlag !== self::NINGUNO ? '-' : '') . $x[-1];

    return number_format( $number ,0,'',$thousand).$verif;
  }
  
  function setDecodeFlag(int $flag){
    if(!in_array ($flag, [ self::NINGUNO, self::GUION , self::PUNTO_GUION ]) )
      throw new \Exception('Forma no correcta');
    else
      $this->decodeFlag = $flag;
    return $this;
  }

}
?>
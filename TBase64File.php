<?php
namespace Type;
class TBase64File extends Type{
  private $mimeTypes;
  function __construct($mimeTypes, $maxSize = null){
    $mimeTypes = is_array($mimeTypes) ? $mimeTypes : explode(";",$mimeTypes);

    $discretes = "text|image|audio|video|application";
    $mimeRegex = "/($discretes)\/.+/";
    $this->mimeTypes = [];
    foreach($mimeTypes as &$mime){
      if($mime === "*")
        $this->mimeTypes[] = $mimeRegex;
      else if(preg_match($mimeRegex,$mime)){
        $mime = strtolower($mime);
        if(preg_match("/($discretes)\/(.+)/",$mime,$match)){
          if($match[2] == "*")
            $this->mimeTypes[] = "/^$match[1]\/.+$/";
          else{
            $match[2] = preg_replace_callback("/(\.|\/)/",fn($x)=>"\\".$x[1],$match[2]);
            $this->mimeTypes[] = "/^$match[1]\/$match[2]$/";
          }
        }
      }else{
        throw new \Exception("Invalid mime definition");
      }
    }
  }

  function checkMime($testMime){
    $testMime = strtolower($testMime);
    foreach($this->mimeTypes as &$validMime){
      if(preg_match($validMime,$testMime))
        return true;
    }
    return false;
  }

  function validate($x){
    if(gettype($x) !== "string")
      throw new TypeException("NO_BASE64_FILE");

    $file = explode(",",$x);

    if(count($file) === 1)
      throw new TypeException("NO_BASE64_FILE");
    
    if(preg_match("/^data:(?P<mime>.+);base64/",$file[0],$match)){
      if(!$this->checkMime($match['mime']))
        throw new TypeException("NO_VALID_MIME");

      $this->validatedData = [
        "mime" => $match['mime'],
        "raw" => base64_decode($file[1]),
        "B64" => $x
      ];
    }else{
      throw new TypeException("NO_BASE64_FILE");
    }
    return $this;
  }
}
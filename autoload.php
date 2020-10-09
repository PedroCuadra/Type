<?php
spl_autoload_register(function($file){
  $prefix = substr($file,0,5);
  $suffix  = substr($file,5);
  $fileName = dirname(__FILE__).'/'.$suffix.'.php';
  if($prefix === 'Type\\' and file_exists($fileName)){
    require_once $fileName;
  }
});
?>
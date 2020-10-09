<?php
namespace Type;
interface TypeEncodable{
  function encode($x);
  function decode($x);
}
?>
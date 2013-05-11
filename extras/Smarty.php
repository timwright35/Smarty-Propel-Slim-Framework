<?php

class SmartyExtrasExtends extends \Slim\Extras\Views\Smarty {

  public function setSmartyDirectory($path){
    self::$smartyDirectory = $path;
  }

  public function setSmartyCompileDirectory($path){
    self::$smartyCompileDirectory = $path;
  }

  public function setSmartyCacheDirectory($path){
    self::$smartyCacheDirectory = $path;
  }

  public function setSmartyTemplateDirectory($path){
    self::$smartyTemplatesDirectory = $path;
  }


}
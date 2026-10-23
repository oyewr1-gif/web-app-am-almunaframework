<?php
  //Mulai session
  session_name('ppam_session');
  ob_start();
  session_start();

  //echo 'init';
  require_once("config/Config.php");
  require_once("core/ErrorHandler.php");
  set_exception_handler("ErrorHandler::exceptionHandler");

  spl_autoload_register(function($class) {
	  //if(file_exists("Core/".$class.".php")) 
	  require_once "core/$class.php";
  });

<?php
  //Mulai session
  session_name('almuna_session');
  ob_start();
  session_start();

  //require_once("core/ErrorHandler.php");
  //set_exception_handler("ErrorHandler::exceptionHandler");

  spl_autoload_register(function($class) {
	  if(file_exists(APP_PATH . "core/".$class.".php")) 
	    require_once APP_PATH . "core/$class.php";
  });

  //Load Function Helper
  require_once APP_PATH . 'core/Functions.php';

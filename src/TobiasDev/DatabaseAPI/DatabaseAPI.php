<?php

	 namespace TobiasDev\DatabaseAPI;

	 use pocketmine\plugin\PluginBase;

	 class DatabaseAPI extends PluginBase
	 {

		  private static $instance;


		  public function onEnable ()
		  {

				self::$instance = $this;
		  }


		  public static function constructConnection ( String $host, String $user, String $password ) : Connection
		  {

				return new Connection( $host, $user, $password );
		  }


		  public static function getInstance () : DatabaseAPI
		  {

				return self::$instance;
		  }
	 }
<?php
/**
 * Copyright (c) 2019.
 * This Software may not be shared without explicit permission from the Developers of this System.
 * You are not allowed to redistribute this under any terms or conditions without explicit permission.
 * #     #   #     #  #  #    #     #   #      #      # # # # #
 * #     # #     # #    #   #     #   #      #      #     # #     #
 *
 */

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
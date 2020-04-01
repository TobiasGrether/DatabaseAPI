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

	 use Closure;
	 use pocketmine\Server;
	 use TobiasDev\DatabaseAPI\Task\InsertionTask;
	 use TobiasDev\DatabaseAPI\Task\SelectorTask;

	 class Connection
	 {

		  public $password;

		  public $host;

		  public $user;


		  public function __construct ( String $host, String $user, String $password )
		  {

				$this->password = $password;
				$this->host = $host;
				$this->user = $user;
		  }


		  /**
			* @param String        $query
			* @param String        $database
			* @param Closure|null $datahandler
			* @param Closure|null $action
			* @param array         $data
			* @param Closure[]    $closures
			* Executes a query which returns a data value
			* $datahandler is if you wanna pass the data in a special format
			* $action is executed after you get the results
			* Statements like INSERT
			*/
		  public function executeQuery ( String $query, String $database, Closure $datahandler = null, Closure $action = null, array $data = [], Closure ...$closures)
		  {

				DatabaseAPI::getInstance()->getServer()->getAsyncPool()->submitTask(new SelectorTask( $query, $this, $datahandler, $action, $database , $data, $closures) );
		  }


		  /**
			* @param String        $query
			* @param String        $database
			* @param Closure|null $action
			* @param array         $extra_data
			* For Database Actions like INSERT, UPDATE
			*/
		  public function execute ( String $query, String $database, ?Closure $action, array $extra_data = [] )
		  {

				Server::getInstance()->getAsyncPool()->submitTask( new InsertionTask( $query, $this, $database, $action, $extra_data) );
		  }
	 }
<?php
/**
 * Copyright (c) 2019.
 * This Software may not be shared without explicit permission from the Developers of this System.
 * You are not allowed to redistribute this under any terms or conditions without explicit permission.
 * #     #   #     #  #  #    #     #   #      #      # # # # #
 * #     # #     # #    #   #     #   #      #      #     # #     #
 *
 */

namespace TobiasDev\DatabaseAPI\Task;

	 use Closure;
	 use mysqli;
	 use mysqli_result;
	 use pocketmine\scheduler\AsyncTask;
	 use pocketmine\Server;
	 use TobiasDev\DatabaseAPI\Connection;

	 class SelectorTask extends AsyncTask
	 {

		  /** @var Closure */
		  private $action;

		  /** @var String */
		  private $query;

		  /** @var Connection */
		  private $connection;

		  /** @var String */
		  private $db;

		  /** @var Closure */
		  private $handledata;

		  /** @var array */
		  private $extra_data;

		  /** @var Closure[] */
		  private $closures;


		  public function __construct ( String $query, Connection $connection, Closure $handledata, Closure $action, String $database, array $extra_data = [], array $closures = [] )
		  {

				$this->action = $action;
				$this->db = $database;
				$this->query = $query;
				$this->handledata = $handledata;
				$this->connection = $connection;
				$this->extra_data = $extra_data;
				$this->closures = $closures;
		  }


		  public function onRun ()
		  {

				$db = new mysqli( $this->connection->host, $this->connection->user, $this->connection->password, $this->db );
				$stmt = $db->prepare($this->query);
			  	$stmt->execute();
			  	$result = $stmt->get_result();
				if ( $result instanceof mysqli_result ) {

					 if ( $this->handledata === null ) {
						  $this->setResult( $result );
					 } else {
						  $a = $this->handledata;
						  $this->setResult( $a( $result ) );
					 }
				}
				$result->close();
				$db->close();
		  }


		  public function onCompletion ( Server $server )
		  {
				$action = $this->action;
				$action( $this->getResult(), $this->extra_data );
				foreach ( $this->closures as $closure ) {
					 $closure( $server, $this->getResult(), $this->extra_data );
				}
		  }
	 }

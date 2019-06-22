<?php

	 namespace TobiasDev\DatabaseAPI\Task;

	 use pocketmine\scheduler\AsyncTask;
	 use pocketmine\Server;		
	 use TobiasDev\DatabaseAPI\Connection;

	 class InsertionTask extends AsyncTask
	 {

		  /** @var \Closure */
		  private $action;

		  /** @var String */
		  private $query;

		  /** @var Connection */
		  private $connection;

		  /** @var String */
		  private $db;
		  /** @var array  */
		  private $extra_data;
		  public function __construct ( String $query, Connection $connection, String $database, ?\Closure $closure, array $extra_data)
		  {

				$this->action = $closure;
				$this->db = $database;
				$this->query = $query;
				$this->connection = $connection;
				$this->extra_data = $extra_data;
		  }


		  public function onRun ()
		  {

				$db = new \mysqli( $this->connection->host, $this->connection->user, $this->connection->password, $this->db );
				$this->setResult( $db->query( $this->query ) );
				$db->close();
		  }


		  public function onCompletion ( Server $server )
		  {
				if($this->action !== null) {
					 $action = $this->action;
					 $action( $this->getResult(), $this->extra_data );
				}

		  }
	 }
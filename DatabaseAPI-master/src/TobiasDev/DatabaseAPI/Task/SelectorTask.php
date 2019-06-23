<?php

	 namespace TobiasDev\DatabaseAPI\Task;

	 use pocketmine\scheduler\AsyncTask;
	 use pocketmine\Server;
	 use TobiasDev\DatabaseAPI\Connection;

	 class SelectorTask extends AsyncTask
	 {

		  /** @var \Closure */
		  private $action;

		  /** @var String */
		  private $query;

		  /** @var Connection */
		  private $connection;

		  /** @var String */
		  private $db;

		  /** @var \Closure */
		  private $handledata;

		  /** @var array */
		  private $extra_data;

		  /** @var array */
		  private $plugin_access;


		  public function __construct ( String $query, Connection $connection, \Closure $handledata, \Closure $action, String $database, array $extra_data = [],bool $plugin_access = false)
		  {

				$this->action = $action;
				$this->db = $database;
				$this->query = $query;
				$this->handledata = $handledata;
				$this->connection = $connection;
				$this->extra_data = $extra_data;
				$this->plugin_access = $plugin_access;
		  }


		  public function onRun ()
		  {

				$db = new \mysqli( $this->connection->host, $this->connection->user, $this->connection->password, $this->db );
				$result = $db->query( $this->query );
				if ( $result instanceof \mysqli_result ) {

					 if ( $this->handledata === null ) {
						  $this->setResult( $result );
					 } else {
						  $a = $this->handledata;
						  // var_dump($a($result));
						  $this->setResult( $a( $result) );
					 }
				}
				$result->close();
				$db->close();
		  }


		  public function onCompletion ( Server $server )
		  {
		      if ($this->plugin_access) {
		          Server::getInstance()->getPluginManager()->getPlugin("DatabaseAPI")->getScheduler()->scheduleDelayedTask(new pluginAccessTask($this->getResult(), $this->extra_data, $this->action), 1);
              } else {
                  $action = $this->action;
                  $action( $this->getResult() , $this->extra_data);
              }
		  }
	 }

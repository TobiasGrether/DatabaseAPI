<?php
/**
 * Copyright (c) 2022.
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
    private $handledata;
    /** @var Closure[] */
    private $closures;
    /** @var String */
    private $query;
    private $db;
    /** @var Connection */
    private $connection;
    /** @var array */
    private $extra_data;

    public function __construct(string $query, Connection $connection, Closure $handledata, Closure $action, string $database, array $extra_data = [], array $closures = []){
        $this->action = $action;
        $this->db = $database;
        $this->query = $query;
        $this->handledata = $handledata;
        $this->connection = $connection;
        $this->extra_data = $extra_data;
        $this->closures = $closures;
    }


    public function onRun(): void{
        $db = new mysqli($this->connection->host, $this->connection->user, $this->connection->password, $this->db);
        $stmt = $db->prepare($this->query);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result instanceof mysqli_result) {

            if ($this->handledata === null) {
                $this->setResult($result);
            } else {
                $a = $this->handledata;
                $this->setResult($a($result));
            }
        }
        $result->close();
        $db->close();
    }


    public function onCompletion(): void{
        $server = Server::getInstance();
        $action = $this->action;
        $action($this->getResult(), $this->extra_data);
        foreach ($this->closures as $closure) {
            $closure($server, $this->getResult(), $this->extra_data);
        }
    }
}

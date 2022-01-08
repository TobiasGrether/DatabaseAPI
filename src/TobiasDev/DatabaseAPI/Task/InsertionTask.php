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
	 use pocketmine\scheduler\AsyncTask;
	 use pocketmine\Server;		
	 use TobiasDev\DatabaseAPI\Connection;

class InsertionTask extends AsyncTask
{

    /** @var Closure */
    private $action;
    /** @var String */
    private $query;
    private $db;
    /** @var Connection */
    private $connection;
    /** @var array */
    private $extra_data;

    public function __construct(string $query, Connection $connection, string $database, ?Closure $closure, array $extra_data)
    {

        $this->action = $closure;
        $this->db = $database;
        $this->query = $query;
        $this->connection = $connection;
        $this->extra_data = $extra_data;
    }

    public function onRun(): void{
        $db = new mysqli($this->connection->host, $this->connection->user, $this->connection->password, $this->db);
        $s = $db->prepare($this->query);
        $s->execute();
        $this->setResult($s->get_result());
        if (!empty($db->error_list)) {
            $this->setResult($db->error_list);
        }
        $db->close();
    }

    public function onCompletion(): void{
        if (is_array($this->getResult())) {
            var_dump($this->getResult());
            return;
        }
        if ($this->action !== null) {
            $action = $this->action;
            $action($this->getResult(), $this->extra_data);
        }

    }
}

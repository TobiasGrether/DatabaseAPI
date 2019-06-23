<?php


namespace TobiasDev\DatabaseAPI\Task;


use pocketmine\scheduler\Task;

class pluginAccessTask extends Task
{

    private $action;
    private $extra_data;
    private $result;

    public function __construct($result,$extra_data,\Closure $action)
    {
        $this->action = $action;
        $this->extra_data = $extra_data;
        $this->result = $result;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return mixed
     */
    public function getExtraData()
    {
        return $this->extra_data;
    }

    /**
     * @return \Closure
     */
    public function getAction(): \Closure
    {
        return $this->action;
    }

    public function onRun(int $currentTick)
    {
        $action = $this->getAction();
        $action( $this->getResult() , $this->extra_data);
    }
}
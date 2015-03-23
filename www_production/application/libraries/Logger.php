<?php

class Logger
{

    public $logger;

    public function Logger()
    {
        $this->logger = new CI_Log();
    }
    public function logerror($message)
    {
        $this->logger->log('error',$message);
    }

    public function loginfo($message)
    {
        $this->logger->log('info',$message);
    }

    public function logdebug($message)
    {
        $this->logger->log('debug',$message);
    }
}
?>

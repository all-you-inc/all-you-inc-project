<?php

namespace shop\services;

use yii\log\Logger;

class MessageLogger
{
    private $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function send($message): void
    {
        $this->logger->log($message, Logger::LEVEL_INFO);
    }
}
<?php

trait MessageFactory
{
    public function createMessage(
        bool $status,
        string $entityName,
        string $entity,
        string $operation
    ): AbstractMessage {

    }

}

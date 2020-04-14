<?php

namespace QuizApp\Services;

use Framework\Session\Session;

/**
 * A class work with success or error message for inter pages communication
 * Class MessageService
 * @package QuizApp\Services
 */
class MessageService
{
    /**
     * @var Session
     */
    private $session;


    /**
     * @param Session $session
     */
    public function setSession(Session $session)
    {
        $this->session = $session;
    }

    /**
     * Adds error or success message to session
     * @param bool $operationStatus
     * @param string $entityName
     * @param string $entity
     * @param string $operation
     */
    public function addMessage(
        bool $operationStatus,
        string $entityName,
        string $entity,
        string $operation
    ): void {
        $message = ($operationStatus) ? "Success! " : "Error! ";
        $message .= ucfirst($entityName);
        $message .= ($operationStatus)
                ? " $entity was $operation" . "."
                : " $entity was not $operation" . ".";

        ($operationStatus)
            ? $this->addSuccessMessage($message)
            : $this->addErrorMessage($message);
    }

    /**
     * Get the message and delete it from session
     * @return string
     */
    public function getMessage(): string
    {
        $message = ($this->session->get("success")) ?? $this->session->get("error");
        $message = ($message) ?? "";
        $this->deleteMessage();

        return $message;
    }

    /**
     * Checks whether a message exists
     * @return bool
     */
    public function isSet()
    {
        return $this->session->isSet("success") || $this->session->isSet("error");
    }

    /**
     * Checks whether a success message exists
     * @return bool
     */
    public function isSuccess()
    {
        return $this->session->isSet("success");
    }

    /**
     * Add a success message
     * @param string $text
     */
    private function addSuccessMessage(string $text = "Success!"): void
    {
        $this->session->set("success", $text);
    }

    /**
     * Add an error message
     * @param string $text
     */
    private  function addErrorMessage(string $text = "An unknown error occurred!!!"): void
    {
        $this->session->set("error", $text);
    }

    /**
     * Delete the message from session
     */
    private function deleteMessage(): void
    {
        $this->session->delete("success");
        $this->session->delete("error");
    }
}
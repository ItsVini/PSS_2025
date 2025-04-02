<?php
class Messages {
    public $messages = [];

    public function addMessage($message) {
        $this->messages[] = $message;
    }
}

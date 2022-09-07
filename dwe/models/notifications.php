<?php

// NOTE: Experimental feature, don't use in production

// TODO: use PHPMailer

const NOTIFICATION_TYPE_EMAIL = 'email';
const NOTIFICATION_TYPE_SMS = 'sms';

abstract class Notification
{
    protected $title = '';
    protected $text = '';
    protected $fields = [];
    protected $sender = '';
    protected $recipients = [];
    protected $type = NOTIFICATION_TYPE_EMAIL;

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setText(string $text)
    {
        $this->text = $text;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function addField(string $field)
    {
        $this->fields[] = $field;
    }

    public function addFields(array $fields)
    {
        $this->fields += $fields;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function setSender(string $sender)
    {
        $this->sender = $sender;
    }

    public function getSender(): string
    {
        return $this->sender;
    }

    public function addRecipient(string $recipient)
    {
        $this->recipients[] = $recipient;
    }

    public function addRecipients(array $recipients)
    {
        $this->recipients += $recipients;
    }

    public function getRecipients(): array
    {
        return $this->recipients;
    }

    public function getType(): string
    {
        return $this->type;
    }
}

class NotificationEmail extends Notification
{
    protected $type = NOTIFICATION_TYPE_EMAIL;
}

class NotificationEmailUser extends NotificationEmail
{
}

class NotificationEmailAdmin extends NotificationEmail
{
}

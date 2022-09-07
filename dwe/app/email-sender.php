<?php
namespace DifferentWebEngine\EmailSender;

// NOTE: Helper class to send emails, but you can just use PHPMailer directly if you prefer.

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use DifferentWebEngine\SMTP\SMTPServer;

require_once $_SERVER['APP_ROOT'] . 'config/smtp.php';

require_once $_SERVER['APP_ROOT'] . 'vendor/phpmailer/PHPMailer.php';
require_once $_SERVER['APP_ROOT'] . 'vendor/phpmailer/language/phpmailer.lang-it.php';
require_once $_SERVER['APP_ROOT'] . 'vendor/phpmailer/Exception.php';
require_once $_SERVER['APP_ROOT'] . 'vendor/phpmailer/SMTP.php';

class Email
{
    protected $fromAddress = '';
    protected $toAddresses = [];
    protected $ccAddresses = [];
    protected $bccAddresses = [];
    protected $replyToAddresses = [];
    protected $subject = '';
    protected $body = '';

    public function setFrom(string $from) : void
    {
        $this->fromAddress = $from;
        return;
    }

    public function addAddressCc(string $address) : void
    {
        $this->ccAddresses[] = $address;
        return;
    }

    public function addAddressBcc(string $address) : void
    {
        $this->bccAddresses[] = $address;
        return;
    }

    public function addAddressTo(string $address) : void
    {
        $this->toAddresses[] = $address;
        return;
    }

    public function addReplyTo(string $address) : void
    {
        $this->replyToAddresses[] = $address;
        return;
    }

    public function setSubject(string $subject) : void
    {
        $this->subject = $subject;
        return;
    }

    public function setBody(string $body) : void
    {
        $this->body = $body;
        return;
    }

    public function send()
    {
        try {
            $email = new PHPMailer(true);
            $email->isHTML(true);
            $email->Subject = $this->subject;
            $email->SetFrom($this->fromAddress);
            $email->Body = $this->body;

            foreach ($this->toAddresses as $toAddress) {
                $email->AddAddress($toAddress);
            }

            foreach ($this->ccAddresses as $ccAddress) {
                $email->AddCC($ccAddress);
            }

            foreach ($this->bccAddresses as $bccAddress) {
                $email->AddBCC($bccAddress);
            }

            foreach ($this->replyToAddresses as $replyToAddresses) {
                $email->AddReplyTo($replyToAddresses);
            }

            $email->IsSMTP();
            $email->Port = SMTPServer::$port;
            $email->CharSet = 'utf-8';
            $email->SMTPSecure = 'tls';
            $email->SMTPDebug = false;
            $email->SMTPAuth = true;
            $email->Host = SMTPServer::$host;
            $email->Username = SMTPServer::$username;
            $email->Password = SMTPServer::$password;

            $email->Send();
        } catch (LogicException $e) {
            $e->getMessage();
        }
    }
}

<?php
namespace PHPMailer\PHPMailer;

class Exception extends \Exception {}

class PHPMailer
{
    const ENCRYPTION_STARTTLS = 'tls';

    public $Host;
    public $SMTPAuth = false;
    public $Username;
    public $Password;
    public $SMTPSecure;
    public $Port;
    public $SMTPDebug = 0;
    public $From;
    public $FromName;
    public $Subject;
    public $Body;
    public $AltBody;

    private $addresses = [];
    private $lastError = '';

    public function __construct($exceptions = false)
    {
        // shim constructor - nothing to do
    }

    public function isSMTP(): void
    {
        // shim - does nothing
    }

    public function setFrom(string $address, string $name = ''): bool
    {
        $this->From = $address;
        $this->FromName = $name;
        return true;
    }

    public function addAddress(string $address): bool
    {
        $this->addresses[] = $address;
        return true;
    }

    public function isHTML(bool $bool): void
    {
        // shim - does nothing
    }

    public function send(): bool
    {
        if (empty($this->addresses)) {
            $this->lastError = 'No recipients specified';
            return false;
        }

        $to = implode(', ', $this->addresses);
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: " . ($this->FromName ?: $this->From) . " <" . $this->From . ">\r\n";
        $headers .= "X-Mailer: LearnBoost AI (PHPMailer shim)\r\n";

        $result = @mail($to, $this->Subject ?? '', $this->Body ?? '', $headers);
        if (!$result) {
            $this->lastError = 'Native mail() failed in PHPMailer shim';
            return false;
        }
        return true;
    }

    public function getLastError(): string
    {
        return $this->lastError;
    }
}

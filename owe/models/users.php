<?php

require_once 'model.php';

class Users extends DbTable
{
}

class UserBase extends DbTableRecord
{
}

class UsersTokens extends DbTable
{
    protected $dbTable = 'users_tokens';
    protected $recordClassToCreate = 'UserToken';
    protected $primaryKey = 'token';

    final public function insertRecord(array $fields): bool
    {
        global $_messages;

        if (!isset($fields['ip'])) {
            $fields['ip'] = Database::escapeLiteral($_SERVER['REMOTE_ADDR']);
        }

        if (!isset($fields['timestamp_expiration'])) {
            $fields['timestamp_expiration'] = "now() + '" . LOGIN_SESSION_DURATION_SECONDS . "s'";
        }

        return parent::insertRecord($fields);
    }

    final public function deleteExpiredSessions(): bool
    {
        $query = '';

        if ($this->getAllRecords("timestamp_expiration < 'now()'")) {
            $query = "DELETE FROM $this->dbTable WHERE timestamp_expiration < NOW()";
        }

        if ($query and !Database::query($query)) {
            $_messages->add(ALERT_TYPE_DEBUG, 'Error removing expires essions.');
            return false;
        }

        return false;
    }

    public function renderHtml(): string
    {
        return parent::renderHtml();
    }
}

class UserTokenBase extends DbTableRecord
{
    protected $dbTable = 'users_tokens';
    protected $userId = 0;
    protected $userIP = '';
    protected $timestampLogin = '';
    protected $timestampExpiration = '';
    protected $token = '';

    public function setDataByObject($object)
    {
        parent::setDataByObject($object);

        $this->token = $object->auth_token ?? '';
        $this->userId = $object->user_id ?? 0;
        $this->userIP = $object->ip ?? '';
        $this->loginTimestamp = $object->timestamp_login ?? '';
        $this->expirationTimestamp = $object->timestamp_expiration ?? '';
        return;
    }

    final public function getUserId(): int
    {
        return $this->userId;
    }

    final public function getUserIp(): string
    {
        return $this->userIP;
    }

    final public function getLastLoginTimestamp(): string
    {
        return $this->timestampLogin;
    }

    final public function getExpirationTimestamp(): string
    {
        return $this->timestampExpiration;
    }

    final public function isExpired(): bool
    {
        return false;
    }

    public function getTokenValueFromCookie(string $cookieName): string
    {
        $token = $_COOKIE[$cookieName] ?? '';
        return $token;
    }

    final public function getRecordByToken(string $token)
    {
        return $this->getRecordByColumn('auth_token', "'$token'");
    }

    public function getRecordByCookie(string $cookieName)
    {
        if (isset($_COOKIE[$cookieName]) and $token = $_COOKIE[$cookieName]) {
            return $this->getRecordByToken($token);
        } else {
            return null;
        }
    }

    final public function deleteSession(string $token = ''): bool
    {
        global $_messages;

        if (!$token) {
            $tokenRecord = static::getRecordByCookie();
            $token = $tokenRecord->auth_token;
        }

        if (!$token) {
            $_messages->add(ALERT_TYPE_DEBUG, 'Error deleting sessions.');
            return false;
        }

        $query = "DELETE FROM $this->dbTable WHERE auth_token = '$token'";

        if (!Database::query($query)) {
            $_messages->add(ALERT_TYPE_ERROR, 'Error logging out.');
            return false;
        }

        return true;
    }

    public function renderHtml(): string
    {
        return parent::renderHtml();
    }
}

if (is_file($_SERVER['APP_ROOT'].'models/custom/users-custom.php')) {
    include_once $_SERVER['APP_ROOT'].'models/custom/users-custom.php';
} else {
    throw new LogicException('Missing custom users file');
}

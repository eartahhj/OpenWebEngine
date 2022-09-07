<?php

require_once 'users.php';

class Admins extends Users
{
    protected $dbTable='admins';
    protected $recordClassToCreate='AdminCustom';
}

class AdminBase extends UserBase
{
    protected $dbTable = 'admins';
    protected $dbTableTokens = 'admin_tokens';
    protected static $cookieName = 'authAdm';
    protected $level = 0;


    public function setDataByObject($object): void
    {
        parent::setDataByObject($object);
        $this->level = $object->level ?? '';
        return;
    }

    public function getLevel(): AdminGroup
    {
        $groupId = $this->group;
    }

    public function login(): bool
    {
        global $_messages;

        if ($this->isLogged()) {
            return true;
        }

        $newToken = generateUniqueToken($this->username);

        $token = new AdminToken();
        $tokens = new AdminsTokens();

        if ($oldToken = $token->getRecordByCookie() and $oldToken != $newToken) {
            $queryDelete = "DELETE FROM $this->dbTableTokens WHERE auth_token = '{$oldToken->auth_token}'";
            if (!Database::query($queryDelete)) {
                $_messages->add(ALERT_TYPE_DEBUG, 'Error deleting old token for this admin');
            }
        }

        $newTokenEscaped = Database::escapeLiteral($newToken);

        $queryInsert = "INSERT INTO $this->dbTableTokens (auth_token, admin_id) VALUES ({$newTokenEscaped}, {$this->getId()})";

        if (!Database::query($queryInsert)) {
            $_messages->add(ALERT_TYPE_ERROR, 'Login failed.');
            $_messages->add(ALERT_TYPE_DEBUG, 'Error inserting token in the DB.');
            return false;
        }

        setcookie(static::getCookieName(), $newToken, time() + LOGIN_SESSION_DURATION_SECONDS, '/', $_SERVER['HTTP_HOST'], false, true);

        $this->setLogged(true);
        $this->updateLastAccess();
        return true;
    }

    public function logout($token = null): bool
    {
        if (!$token or !is_a($token, 'AdminToken')) {
            $token = new AdminToken();
        }

        if ($token->deleteSession()) {
            setcookie(static::getCookieName(), '', time() - 1, '/', $_SERVER['HTTP_HOST'], false, true);
            $this->setLogged(false);
            return true;
        }

        return false;
    }

    public function isAdminLoggedWithThisToken(string $token): bool
    {
        $adminToken = new AdminToken();
        if ($record = $adminToken->getRecordByCondition("auth_token = '{$token}'")) {
            $adminToken->setDataByObject($record);
            $this->token = $adminToken;
            return true;
        }
        return false;
    }

    public function renderHtml(): string
    {
    }
}

class AdminsTokens extends UsersTokens
{
    protected $dbTable = 'admin_tokens';
}

class AdminToken extends UserToken
{
    protected $dbTable = 'admin_tokens';
    protected $adminId = 0;
    protected $token = '';
    protected $ip = '';
    protected $loginTimestamp = '';
    protected $expirationTimestamp = '';

    public function setDataByObject($object): void
    {
        parent::setDataByObject($object);
        $this->token = $object->auth_token ?? '';
        $this->adminId = $object->admin_id ?? 0;
        $this->ip = $object->ip ?? '';
        $this->loginTimestamp = $object->timestamp_login ?? '';
        $this->expirationTimestamp = $object->timestamp_expiration ?? '';
        return;
    }

    final public function getAdminId(): int
    {
        return $this->adminId;
    }

    final public function getToken(): string
    {
        return $this->token;
    }

    final public function getTokenValueFromCookie(string $cookieName): string
    {
        return parent::getTokenValueFromCookie($cookieName);
    }

    final public function getRecordByCookie(string $cookieName)
    {
        return parent::getRecordByCookie($cookieName);
    }

    public function renderHtml(): string
    {
    }
}

if (is_file($_SERVER['APP_ROOT'].'models/custom/admin-custom.php')) {
    include_once $_SERVER['APP_ROOT'].'models/custom/admin-custom.php';
} else {
    throw new LogicException('Missing custom users file');
}

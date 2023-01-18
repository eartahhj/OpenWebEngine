<?php
/*
// Examples:
$_messages->add(ALERT_TYPE_ERROR, _('Test_Error'));
$_messages->add(ALERT_TYPE_INFO, _('Test_Info'));
$_messages->add(ALERT_TYPE_CONFIRM, _('Test_Confirm'));
$_messages->add(ALERT_TYPE_DEBUG, _('Test_Debug'));
$_messages->add(ALERT_TYPE_DEBUGQUERY, 'SELECT * FROM pages');
*/

const ALERT_TYPE_NORMAL = 'alert';
const ALERT_TYPE_CONFIRM = 'confirm';
const ALERT_TYPE_ERROR = 'error';
const ALERT_TYPE_INFO = 'info';
const ALERT_TYPE_DEBUG = 'debug';
const ALERT_TYPE_DEBUGQUERY = 'debugquery';

class Alerts
{
    protected $list=[];

    public function getList(): array
    {
        return $this->list;
    }

    public function add(string $type, string $alert, $optionalParam = ''): void
    {
        $classToCreate = 'Alert';

        if ($type != ALERT_TYPE_NORMAL) {
            $classToCreate .= ucfirst($type);
        }
        if ($optionalParam) {
            $this->list[] = new $classToCreate($alert, $optionalParam);
        } else {
            $this->list[] = new $classToCreate($alert);
        }
        return;
    }

    public function checkIfAlertTypeExistsAndReturnAlerts(string $type): array
    {
        $found=0;
        $alerts=[];
        foreach ($this->list as $k => $alert) {
            if ($alert->type == $type) {
                $found++;
                $alerts[] = $alert;
            }
        }
        return $alerts;
    }

    public function __toString()
    {
        global $_config, $_debugMode;
        $html = '';
        if (!empty($this->list)) {
            foreach ($this->list as $alert) {
                if ($alert->type == 'debug' and !$_debugMode) {
                    continue;
                }
                $html .= $alert;
            }
        }
        return $html;
    }
}

class Alert
{
    public $id = 0;
    public $type = ALERT_TYPE_NORMAL;
    public $text = '';
    public $cssClass = 'alert';

    public function getType()
    {
        return $this->type;
    }

    public function __construct(string $text='')
    {
        $this->text = $text;
    }

    public function __toString()
    {
        $html = '<div class="' . $this->cssClass . '"><h4>' . $this->text . "</h4></div>\n";
        return $html;
    }
}

class AlertConfirm extends Alert
{
    public $type = ALERT_TYPE_CONFIRM;
    public $cssClass = 'alert confirm';

    public function __toString()
    {
        $html = '<div class="' . $this->cssClass . '"><h4>' . $this->text . "</h4></div>\n";
        return $html;
    }
}

class AlertInfo extends Alert
{
    public $type = ALERT_TYPE_INFO;
    public $cssClass = 'alert info';

    public function __toString()
    {
        $html = '<div class="' . $this->cssClass . '"><h4>' . $this->text . "</h4></div>\n";
        return $html;
    }
}

class AlertDebug extends Alert
{
    public $type = ALERT_TYPE_DEBUG;
    public $cssClass = 'alert debug';

    public function __toString()
    {
        $html = '<div class="' . $this->cssClass . '"><h4><strong>(' . _('Debug') . ')</strong> ' . $this->text . "</h4></div>\n";
        return $html;
    }
}

class AlertDebugQuery extends Alert
{
    public $type = ALERT_TYPE_DEBUGQUERY;
    public $cssClass = 'alert debug debug-query';

    public function __construct(string $query, string $text='')
    {
        parent::__construct($text);

        $this->text = _('Query').": <strong>$query</strong>";

        if ($text) {
            $this->text .= _('with message') . "<strong>$text</strong>";
        }
    }

    public function __toString()
    {
        $html = '<div class="' . $this->cssClass . '"><h4><strong>(' . _('Debug') . ')</strong> ' . $this->text . "</h4></div>\n";
        return $html;
    }
}

class AlertError extends ErrorException
{
    public $row = 0;
    public $scriptName = '';
    public $code = 0;
    public $stackTrace = '';
    public $type = ALERT_TYPE_ERROR;
    public $cssClass = 'alert error';

    public function __construct($text='')
    {
        parent::__construct($text);
        $this->text = $this->getMessage();
        $this->row = $this->getLine();
        $this->scriptName = $this->getFile();
        $this->code = $this->getCode();
        $this->stackTrace = $this->getTraceAsString();
    }

    public function getType()
    {
        return $this->type;
    }


    public function __toString()
    {
        global $_config, $_debugMode;
        $html = '<div class="' . $this->cssClass . '"><h4>' . $this->text . "</h4>";
        $html .= "</div>\n";
        if ($_debugMode) {
            $htmlDebug='';
            // TODO: su Debug ora viene fuori la riga dello stesso messages.php. Avevo trovato un modo per fare diversamente, da riguardare.
            $htmlDebug.='<p>'._('Debug').': <strong>'._('row').' '.$this->row.' '._('script').' '.$this->scriptName.'</strong>'.($this->code ? ' '._('with code').' '.$this->code : '')."</p>\n";
            $htmlDebug.='<p>'._('Stack trace').':<br />'.nl2br($this->stackTrace)."</p>\n";
            $debugMessage=new AlertDebug();
            $html.='<div class="'.$debugMessage->cssClass.'">'.$htmlDebug."</div>\n";
        }
        return $html;
    }
}

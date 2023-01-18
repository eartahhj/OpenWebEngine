<?php
# NOTE: See /models/navigation.php
if ($this->getUrl()) {
    $html.='<a href="'.$this->getUrl().'">';
} else {
    $html .= '<span>';
}

$html .= $this->getTitle();

if ($this->getUrl()) {
    $html.="</a>";
} else {
    $html .= '</span>';
}

if ($this->getSubVoices()) {
    $html .= '<input id="nav-item-' . $this->id . '-handler" type="checkbox" value=""' . ($this->isOpenByDefault() ? ' checked="checked"' : '') . '>';
    $html .= '<label for="nav-item-' . $this->id . '-handler" title="' . _('Apri') . ' ' . htmlspecialchars($this->returnDefaultTitle()) . '"></label>';
    $html .= "\n<ul>\n";
    foreach ($this->getSubVoices() as $subVoiceId => $subVoice) {
        $html .= '<li id="nav-item-' . $subVoiceId . '" class="nav-item"';
        if ($subVoice->getCssClass()) {
            $html .= ' class="' . $subVoice->getCssClass() . '"';
        }
        $html .= '>';
        $html .= $subVoice->renderHtml();
        $html .= "</li>\n";
    }
    $html .= "</ul>\n";
}

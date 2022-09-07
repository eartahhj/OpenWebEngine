<?php
# NOTE: See /models/custom/navigation-guest.php
if ($this->getVoices()) {
    $html .= '<ul';
    if ($this->cssClass) {
        $html .= ' class="' . $this->cssClass . '"';
    }
    $html .= '>' . "\n";
    foreach ($this->getVoices() as $voiceId => $voice) {
        $html .= '<li id="nav-item-' . $voiceId . '" class="nav-item' . ($voice->getCssClass() ? ' ' . $voice->getCssClass() : '') . '">';
        $html .= $voice->renderHtml();
        $html .= "</li>\n";
    }
    $html .= "</ul>\n";
}

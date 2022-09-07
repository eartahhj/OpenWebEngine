<?php
$html.='<div class="page-attachments">'."\n";
$html.='<h4>'._('Allegati').'</h4>';
$html.='<ul>'."\n";
while ($r=pg_fetch_object($ris)) {
    $html.='<li><a href="'.$this->datiURL.$r->id.'-'.$r->nomefile.'" type="'.htmlspecialchars($r->mimefile).'"';
    if(isset($r->target_blank) and $r->target_blank=='t') {
        $html.=' target="_blank"';
    }
    $html.='>';
    $html.=htmlspecialchars($r->titolo);
    $html.="</a></li>\n";
}
pg_free_result($ris);
$html.="</ul>\n";
$html.="</div>\n";

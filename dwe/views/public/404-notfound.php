<?php
Header('HTTP/1.0 404 Not Found');

if($_messages->getList()) {
    echo '<div id="site-alerts">' . $_messages . "</div>\n";
}
?>

<h2>404 - Page not found</h2>
<h3>We are sorry, the content you were looking for is not available.</h3>

<?php
exit();

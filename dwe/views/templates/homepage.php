<?php
function templateStartHomepage() : bool
{
    global $_templateNav;
?>
<main class="template-homepage">
    <div class="banner">
        <img src="/img/banner-homepage.jpg" alt="">
    </div>
<?php
    return true;
}

function templateEndHomepage() : bool
{
?>
</main>
<?php
    return true;
}
?>

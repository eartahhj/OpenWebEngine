<?php
if(is_file(Config::$templatesDirectory . $_template->getFileName().'.php')) {
    include Config::$templatesDirectory . $_template->getFileName().'.php';
}

if ($_page->getPropertyMultilang('seoTitle')) {
    $_template->addEntity('title', $_page->getPropertyMultilang('seoTitle'));
} elseif ($_page->getPropertyMultilang('title')) {
    $_template->addEntity('title', $_page->getPropertyMultilang('title'));
} else {
    $_template->addEntity('title', $_page->getTitle());
}

$_template->addEntity('html', $_page->getPropertyMultilang('html'));

$canonicalUrl = $alternateUrl = '';

if (isset($category) and is_a($category, 'PageCategory')) {
    $canonicalUrl = 'https://www.yourwebsite.com/' . $_language . '/' . $category->getPropertyMultilang('url') . '-' . $category->getId() . '/' . $_page->getPropertyMultilang('url') . '-' . $_page->getId();
    if (
        $_language == 'it' and $_page->getPropertyMultilang('html', 'en') or
        $_language == 'en' and $_page->getPropertyMultilang('html', 'it')
    ) {
        $alternateUrl = 'https://www.yourwebsite.com/' . ($_language == 'it' ? 'en' : 'it') . '/'
        . $category->getPropertyMultilang('url', ($_language == 'it' ? 'en' : 'it')) . '-' . $category->getId()
        . '/' . $_page->getPropertyMultilang('url', ($_language == 'it' ? 'en' : 'it')) . '-' . $_page->getId();
    }
}

require_once $_SERVER['APP_ROOT'] . 'models/custom/navigation-guest.php';
?>
<!DOCTYPE html>
<html lang="<?=$_language?>" dir="ltr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="Content-type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0" />
        <title><?=htmlspecialchars($_template->getEntity('title'))?> - OpenWebEngine</title>
        <link rel="shortcut icon" href="/img/logo.png" />
        <?php if ($canonicalUrl):?>
        <link rel="canonical" href="<?=$canonicalUrl?>" />
        <?php endif?>
        <?php if ($alternateUrl):?>
        <link rel="alternate" href="<?=$alternateUrl?>" hreflang="<?=($_language == 'it' ? 'en' : 'it')?>" />
        <?php endif?>
        <link rel="preload" href="<?=Config::$javascriptFolder?>jquery.min.js" as="script">
        <link rel="preload" href="<?=Config::$javascriptFolder?>global.js" as="script">
        <?php foreach($_template->getJavascript() as $javascriptFile):?>
        <link rel="preload" href="<?=$javascriptFile?>" as="script">
        <?php endforeach?>
        <style media="screen" title="Default">
        <?php loadFile('css/style-global.css', 'style')?>
        </style>
        <style media="screen">
        <?php loadFile('css/bootstrap.css', 'style')?>
        </style>
        <style media="screen" title="Default">
        <?php loadFile('css/style-custom.css', 'style')?>
        </style>
        <?php foreach($_template->getCss() as $cssFile):?>
        <style media="screen" title="Default">
        <?php loadFile($cssFile, 'style')?>
        </style>
        <?php endforeach?>
        <script type="text/javascript" src="<?=Config::$javascriptFolder?>jquery.min.js" defer></script>
        <script type="text/javascript" src="<?=Config::$javascriptFolder?>global.js" defer></script>
        <?php foreach($_template->getJavascript() as $javascriptFile):?>
        <script type="text/javascript" src="<?=$javascriptFile?>" defer></script>
        <?php endforeach?>
        <?php endif?>
        <script type="text/javascript">
          var _paq = window._paq = window._paq || [];
          /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
          _paq.push(['enableHeartBeatTimer', 15]);
          // NOTE: No need for trackPageView if you are using Matomo Server Side Tracking
          // _paq.push(['trackPageView']);
          _paq.push(['enableLinkTracking']);
          (function() {
            var u="https://matomo.yourwebsite.com/";
            _paq.push(['setTrackerUrl', u+'matomo.php']);
            _paq.push(['setSiteId', '1']);
            var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
            g.type='text/javascript'; g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
          })();
        </script>
        <?php if ($_page->getPropertyMultilang('seoDescription')):?>
        <meta name="description" content="<?=htmlspecialchars($_page->getPropertyMultilang('seoDescription'))?>">
        <meta property="og:description" content="<?=htmlspecialchars($_page->getPropertyMultilang('seoDescription'))?>">
        <?php endif?>
        <meta property="og:locale" content="<?=($_language == 'it' ? 'it_IT' : 'en_US')?>" />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="https://www.yourwebsite.com<?=Router::getUrl()?>" />
        <meta property="og:title" content="<?=htmlspecialchars($_template->getEntity('title'))?>" />
        <?php if ($_page->getImage()):?>
        <meta property="og:image" content="https://www.yourwebsite.com/uploads/<?=$_page->getImage()?>" />
        <?php endif?>
        <meta property="og:site_name" content="OpenWebEngine">
        <meta property="fb:pages" content="" />
    </head>
    <body>
        <header id="header-main">
            <div class="container">
                <div class="grid">
                    <span>
                        <a href="/<?=$_language?>">
                            <picture>
                                <source srcset="/img/webp/logo.webp" type="image/webp">
                                <source srcset="/img/logo.png" type="image/png">
                                <img src="/img/logo.png" alt="OpenWebEngine" width="100" height="100">
                            </picture>
                        </a>
                    </span>
                    <div class="nav-container">
                        <input id="nav-main-handler" type="checkbox" value="" tabindex="0" class="sr-only">
                        <label for="nav-main-handler" tabindex="-1"><span class="sr-only"><?=_('Open navigation menu')?></span></label>
                        <nav id="nav-main">
                            <?php
                            if ($_templateNav->getVoices()) {
                                echo $_templateNav->renderHtml();
                            }
                            ?>
                        </nav>
                    </div>
                </div>
            </div>
        </header>
<?php
if(!function_exists('templateStart' . ucfirst($_template->getName()))) {
    $_messages->add(ALERT_TYPE_ERROR, _('Error loading template: Header not configured'));
    $_template->exit();
}
if(!call_user_func('templateStart' . ucfirst($_template->getName()))) {
    $_messages->add(ALERT_TYPE_ERROR, _('Error loading template: Header could not be loaded'));
    $_template->exit();
}

$_template->setStarted(true);
?>

<?php if($_messages->getList()):?>
    <div id="site-alerts" role="alert">
        <div class="container">
            <?=$_messages?>
        </div>
    </div>
<?php endif?>

<?php
$_template->getEntity('html');

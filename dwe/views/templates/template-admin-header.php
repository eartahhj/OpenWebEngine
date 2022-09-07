<?php
$_template = new TemplateAdmin();
require_once $_SERVER['APP_ROOT'] . 'models/custom/navigation-admin.php';
?>

<!DOCTYPE html>
<html lang="<?=$_language?>" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0" />
        <title>differentWebEngine - Admin</title>
        <link rel="stylesheet" href="<?=Config::$cssFolder?>style-global.css" title="Default">
        <link rel="stylesheet" href="<?=Config::$cssFolder?>bootstrap.css">
        <link rel="stylesheet" type="text/css" href="<?=Config::$javascriptFolder?>chosen/chosen.css" />
        <script type="text/javascript" src="<?=Config::$javascriptFolder?>jquery.min.js"></script>
        <script type="text/javascript" src="<?=Config::$javascriptFolder?>global.js"></script>
        <script type="text/javascript" src="<?=Config::$javascriptFolder?>tinymce/tinymce.min.js"></script>
        <script type="text/javascript" src="<?=Config::$javascriptFolder?>chosen/chosen.jquery.min.js"></script>
        <script type="text/javascript">
            $(function() {
                tinymce.init({
                    "selector":".tinymce textarea",
                    "fix_list_elements":true,
                    "entity_encoding":"raw",
                    "convert_urls":false,
                    "height": 600,
                    "max_height": 600,
                    "plugins":"link autolink autoresize anchor image charmap codesample code table insertdatetime media preview searchreplace wordcount lists advlist visualblocks autosave emoticons fullscreen visualchars",
                    "menubar": "file edit insert view format table tools",
                    "toolbar":"undo redo | alignleft aligncenter alignright | formatselect | searchreplace | bullist numlist outdent indent | bold italic underline | link anchor image | insertdatetime table | media | codesample code | charmap restoredraft emoticons fullscreen visualchars",
                    "toolbar_mode": "wrap",
                    "link_list":"/admin/listafile.php/file",
                    "media_live_embeds":true,
                    "noneditable_class":"no-modifiche",
                    "insertdatetime_formats":["%d/%m/%Y","%H:%M:%S","%d/%m","%H:%M","%d/%m/%Y %H:%M:%S","%Y-%m-%d","%m-%d","%Y-%m-%d %H:%M:%S"],
                    "content_css":"dark,/css/tinymce-custom.css",
                    "image_dimensions":true,
                    "image_title:":true,
                    "image_caption":true,
                    "image_advtab":true,
                    "image_class_list": [
                        {title:"None", value:""},
                        {title:"No border", value:"image-no-border"},
                        {title:"Border", value:"img-with-border"}
                    ],
                    "skin": "oxide-dark",
                    "codesample_languages": [
                        {"text": 'HTML/XML', value: 'markup'},
                        {"text": 'JavaScript', value: 'javascript'},
                        {"text": 'CSS', value: 'css'},
                        {"text": 'PHP', value: 'php'},
                        {"text": 'SQL', value: 'sql'},
                        {"text": 'Python', value: 'python'},
                        {"text": 'Bash', value: 'bash'}
                    ],
                    "rel_list": [
                        {"title": "Link normale", "value": ""},
                        {"title": "External Link", "value": "external noreferrer nofollow noopener"},
                        {"title": "No Referrer", "value": "noreferrer"}
                    ],
                    "autosave_retention":"300m",
                    "style_formats":[
                        {"name":"cta-1", "title":"Call to Action 1", "selector":"a", "classes":["cta", "cta-1"], "inline":"a"},

                        {"name":"alert-info", "title":"Alert info", "classes":["alert", "alert-info"], "block":"div", "merge_siblings":true, "exact":true, "wrapper":true},
                        {"name":"alert-error", "title":"Alert error", "classes":["alert", "alert-error"], "block":"div", "merge_siblings":true, "exact":true, "wrapper":true},
                        {"name":"alert-warning", "title":"Alert warning", "classes":["alert", "alert-warning"], "block":"div", "merge_siblings":true, "exact":true, "wrapper":true},
                        {"name":"alert-confirm", "title":"Alert confirm", "classes":["alert", "alert-confirm"], "block":"div", "merge_siblings":true, "exact":true, "wrapper":true},

                        {"name":"highlighted-1", "title":"Highlighted cyan", "classes":["highlighted", "highlighted-1"], "inline":"span"},
                        {"name":"highlighted-2", "title":"Highlighted golden", "classes":["highlighted", "highlighted-2"], "inline":"span"},
                        {"name":"highlighted-3", "title":"Highlighted white", "classes":["highlighted", "highlighted-3"], "inline":"span"},
                        {"name":"highlighted-4", "title":"Highlighted orange", "classes":["highlighted", "highlighted-4"], "inline":"span"},

                        {"name":"textblock-background-1", "title":"Block with purple background", "classes":["textblock", "background-color-1"], "block":"div", "merge_siblings":true, "exact":true, "wrapper":true},
                        {"name":"textblock-background-2", "title":"Block with golden background", "classes":["textblock", "background-color-2"], "block":"div", "merge_siblings":true, "exact":true, "wrapper":true},
                        {"name":"textblock-background-3", "title":"Block with white background", "classes":["textblock", "background-color-3"], "block":"div", "merge_siblings":true, "exact":true, "wrapper":true},
                        {"name":"textblock-background-4", "title":"Block with orange background", "classes":["textblock", "background-color-4"], "block":"div", "merge_siblings":true, "exact":true, "wrapper":true},

                        {"name":"textblock-code-1", "title":"Code", "classes":["code"],"block":"div"},

                        {"name":"table-of-contents", "title":"Table of Contents", "classes":["table-of-contents"], "block":"div", "merge_siblings":true, "exact":true, "wrapper":true},

                        {"name":"spoiler", "title":"Spoiler", "classes":["text-spoiler"], "block":"div", "merge_siblings":true, "exact":true, "wrapper":true},
                    ],
                    "style_formats_merge":true,
                    "visualblocks_default_state":true,
                    "visualchars_default_state":true,
                    "end_container_on_empty_block":false,
                    "extended_valid_elements":"img[class=image|src|alt=|title|width=|height=|loading=lazy]",
                    "a11y_advanced_options":true
                });
                $(".chosen").chosen();
            });
        </script>
        <script type="text/javascript" src="<?=Config::$javascriptFolder?>forms.js"></script>
        <script type="text/javascript" src="<?=Config::$javascriptFolder?>forms-panel.js"></script>
        <link rel="stylesheet" href="<?=Config::$cssFolder?>style-panel.css" title="Default">
    </head>
    <body>
        <header id="header-main">
            <h1>Administration Panel</h1>
            <?php if (isset($_admin) and $_admin->getId()):?>
            <input id="nav-handler" type="checkbox" value="">
            <label for="nav-handler">Menu</label>
            <nav>
                <?php
                if ($_adminNav->getVoices()) {
                    echo $_adminNav->renderHtml();
                }
                ?>
            </nav>
            <?php endif?>
        </header>
        <main class="template-admin">
            <section>
                <div id="panel-container" class="container">
<?php if($_messages->getList()):?>
    <div id="site-alerts" role="alert">
        <div class="container">
            <?=$_messages?>
        </div>
    </div>
<?php endif?>

<?php
$_template->setStarted(true);

<?php
require_once $_SERVER['APP_ROOT'] . 'views/templates/template-admin-header.php';

if ($filesList):
?>
    <h1><?=_('List of files')?></h1>
    <ul id="filesmanager-list">
        <?php foreach ($filesList as $file):
            if ($file->getId()):
                $mimeType = $file->getMimeType() ? $file->getMimeType() : mime_content_type(Config::$mediaLibraryAbsoluteDir . $file->getFileName());
                ?>
            <li>
                <a href="/admin/filesmanager/edit/<?=$file->getId()?>">
                    <figure>
                        <?php if (strpos('image', $mimeType) !== null):?>
                        <img src="<?=(Config::$mediaLibraryRelativeDir . $file->getFileName())?>" alt="" style="display:none;">
                        <div class="image" style="background-image:url('<?=(Config::$mediaLibraryRelativeDir . $file->getFileName())?>')"></div>
                        <?php else:?>
                        <img src="/img/svg/placeholder.svg" alt="" class="image">
                        <?php endif?>
                        <figcaption>
                            [<?=$file->getId()?>] <?=htmlspecialchars($file->getPropertyMultilang('title'))?>
                        </figcaption>
                    </figure>
                </a>
            </li>
        <?php
            endif;
        endforeach;
        ?>
    </ul>
<?php
else:
?>
<h1><?=_('No data found')?></h1>
<?php
endif;

require_once $_SERVER['APP_ROOT'] . 'views/templates/template-admin-footer.php';

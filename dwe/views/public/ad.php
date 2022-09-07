<div class="aff" data-type="type-<?=$ad->id?>">
    <figure>
        <a href="<?=htmlspecialchars($ad->url)?>" rel="external noopener nofollow" target="_blank" onclick="_paq.push(['trackEvent', '<?=htmlspecialchars($ad->provider)?>', '<?=htmlspecialchars($ad->campaign)?>']);">
            <?php if (empty($ad->pictures)):?>
                <img src="<?=htmlspecialchars($ad->imgFullPath)?>" alt="<?=htmlspecialchars($ad->altText)?>" width="<?=htmlspecialchars($ad->width)?>" height="<?=htmlspecialchars($ad->height)?>">
            <?php else:?>
                <picture>
                    <?php foreach ($ad->pictures as $pictureType => $pictureSrc): ?>
                        <source src="<?=$pictureSrc?>" type="image/<?=($pictureType == 'jpg' ? 'jpeg' : $pictureType)?>">
                    <?php endforeach; ?>
                    <img src="<?=htmlspecialchars($ad->imgFullPath)?>" alt="<?=htmlspecialchars($ad->altText)?>" width="<?=htmlspecialchars($ad->width)?>" height="<?=htmlspecialchars($ad->height)?>">
                </picture>
            <?php endif?>
        </a>
        <figcaption class="sr-only"><?=htmlspecialchars($ad->altText)?></figcaption>
    </figure>
</div>

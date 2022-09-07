<?php

class Page extends PageBase
{
    protected $title_it = '';
    protected $title_en = '';
    protected $html_it = '';
    protected $html_en = '';
    protected $url_it = '';
    protected $url_en = '';
    protected $seoTitle_it = '';
    protected $seoTitle_en = '';
    protected $tags_it = '';
    protected $tags_en = '';
    protected $comments = [];
    protected $seoDescription_it = '';
    protected $seoDescription_en = '';
    protected $tags = [];
    protected $youtubeVideoLink_it = '';
    protected $youtubeVideoLink_en = '';
    protected $youtubeVideoTitle_it = '';
    protected $youtubeVideoTitle_en = '';
    protected $youtubeVideoPreview = '';
    protected $hidden_it = false;
    protected $hidden_en = false;

    public function setDataByObject($object): void
    {
        parent::setDataByObject($object);
        $this->title_it = $object->title_it ?? '';
        $this->title_en = $object->title_en ?? '';
        $this->html_it = $object->html_it ?? '';
        $this->html_en = $object->html_en ?? '';
        $this->url_it = $object->url_it ?? '';
        $this->url_en = $object->url_en ?? '';
        $this->seoTitle_it = $object->seo_title_it ?? '';
        $this->seoTitle_en = $object->seo_title_en ?? '';
        $this->tags_it = $object->tags_it ?? '';
        $this->tags_en = $object->tags_en ?? '';
        $this->seoDescription_it = $object->seo_description_it ?? '';
        $this->seoDescription_en = $object->seo_description_en ?? '';
        $this->youtubeVideoLink_it = $object->youtube_video_link_it ?? '';
        $this->youtubeVideoLink_en = $object->youtube_video_link_en ?? '';
        $this->youtubeVideoTitle_it = $object->youtube_video_title_it ?? '';
        $this->youtubeVideoTitle_en = $object->youtube_video_title_en ?? '';
        $this->youtubeVideoPreview = $object->youtube_video_preview ?? '';

        if (isset($object->hidden_it) and getDbColumnBooleanValue($object->hidden_it) === true) {
            $this->hidden_it = true;
        }

        if (isset($object->hidden_en) and getDbColumnBooleanValue($object->hidden_en) === true) {
            $this->hidden_en = true;
        }

        return;
    }

    final public function isHidden(string $language = 'en')
    {
        return $this->{'hidden_' . $language};
    }

    final public function setCategory(int $categoryId): void
    {
        $this->category = $categoryId;
        return;
    }

    final public function getCategory(int $id = 0): int|PageCategory
    {
        if ($id and isset($this->categories[$id])) {
            return $this->categories[$id];
        } else {
            return $this->category;
        }
    }

    final public function getTags(): array
    {
        return $this->tags;
    }

    final public function getYoutubeVideoPreview(): string
    {
        return $this->youtubeVideoPreview;
    }

    public function convertHtmlShortcodes(&$html): string
    {
        $html = $this->convertPageShortcode($html);
        $html = $this->convertFileShortcode($html);
        $html = $this->convertYoutubevideoShortcode($html);

        return $html;
    }

    protected function convertPageShortcode(&$html): string
    {
        if (preg_match_all('/\{\{p([0-9]+)\}\}/', $html, $pages)) {
            $ids = [];

            foreach ($pages[1] as $k => $v) {
                if ((int)$v == 0) {
                    continue;
                } else {
                    $ids[(int)$v] = (int)$v;
                }
            }

            if (!empty($ids)) {
                $pagesUrls = returnPagesUrlsByIds($ids);
                foreach ($pagesUrls as $id => $url) {
                    $html = preg_replace('/\{\{p' . $id . '\}\}/', Config::$baseURLLanguage . $url, $html);
                }
            }
        }

        return $html;
    }

    protected function convertFileShortcode(&$html): string
    {
        if (preg_match_all('/\{\{f([0-9]+)\}\}/', $html, $file)) {
            $ids = [];

            foreach ($file[1] as $k=>$v) {
                if ((int)$v==0) {
                    continue;
                } else {
                    $ids[(int)$v]=(int)$v;
                }
            }

            if (!empty($ids)) {
                $filesUrls = returnFilesUrlsByIds($ids);
                foreach ($filesUrls as $id => $url) {
                    $html = preg_replace('/\{\{f' . $id . '\}\}/', Config::$uploadsRelativeDir . $url, $html);
                }
            }
        }

        return $html;
    }

    protected function convertYoutubevideoShortcode(&$html): string
    {
        global $_language;

        $imagePath = Config::$uploadsAbsoluteDir . $this->youtubeVideoPreview;

        $imageWidth = $imageHeight = 0;

        if (!is_file($imagePath)) {
            return $html;
        } else {
            $imageSize = getimagesize($imagePath);
        }

        if (empty($imageSize)) {
            return $html;
        } else {
            $imageWidth = intval($imageSize[0]);
            $imageHeight = intval($imageSize[1]);
        }

        if (preg_match_all('/\{\{ytvideo\}\}/', $html, $ytvideo)) {
            $videoLink = $this->{'youtubeVideoLink_' . $_language};
            $videoTitle = $this->{'youtubeVideoTitle_' . $_language};
            $image = Config::$uploadsRelativeDir . $this->youtubeVideoPreview;
            $videoTitleEscaped = addslashes($videoTitle);

            $youtubeVideoHtml = <<<HTML
            <div class="youtube-video" data-video-url="{$videoLink}">
                <figure>
                    <a href="{$videoLink}" rel="external noopener nofollow" target="_blank" onclick="_paq.push(['trackEvent', 'Youtube Video Preview Image', '{$videoTitleEscaped}']);">
                        <span class="play-icon"></span>
                        <img src="{$image}" alt="{$videoTitle}" width="{$imageWidth}" height="{$imageHeight}" loading="lazy">
                    </a>
                    <figcaption>{$videoTitle} (<a href="{$videoLink}" rel="external noopener nofollow" target="_blank" onclick="_paq.push(['trackEvent', 'Youtube Video Preview Link', '{$videoTitleEscaped}']);">Youtube</a>)</figcaption>
                </figure>
            </div>
            HTML;

            $html = preg_replace('/\{\{ytvideo\}\}/', $youtubeVideoHtml, $html);
        }

        return $html;
    }

    final public function loadTags(): void
    {
        global $_language;

        $pageTags = [];

        if ($tags = $this->getPropertyMultilang('tags', $_language)) {
            $pageTags = explode(',', $tags);
            foreach ($pageTags as $k => $tag) {
                $pageTags[$k] = trim($tag);
            }
        }

        $this->tags = $pageTags;
    }

    final public function deleteImages(): bool
    {
        if (!$this->deleteYoutubeVideoPreview()) {
            return false;
        }

        return parent::deleteImages();
    }

    final public function deleteYoutubeVideoPreview(): bool
    {
        if ($this->youtubeVideoPreview) {
            return $this->deleteImage(Config::$uploadsAbsoluteDir . $this->youtubeVideoPreview, 'youtube_video_preview');
        }

        return true;
    }

    final public function deletePreviousImage(string $oldImage): bool
    {
        $oldImagePath = Config::$uploadsAbsoluteDir . $oldImage;
        $oldImageExtension = returnFileExtension($oldImage);

        if (is_file($oldImagePath)) {
            if (!deleteFile($oldImagePath)) {
                return false;
            }
        }

        if ($oldImageExtension != 'webp') {
            $oldImageWebp = str_replace('.' . $oldImageExtension, '.webp', $oldImage);
            $oldImagePathWebp = Config::$uploadsAbsoluteDir . 'webp/' . $oldImageWebp;
            if (is_file($oldImagePathWebp)) {
                if (!deleteFile($oldImagePathWebp)) {
                    return false;
                }
            }
        }

        return true;
    }
}

class PageCategory extends Page
{
    protected $dbTable = 'page_categories';
    protected $parentCategory = 0;
    protected $childrenCategories = [];

    public function setDataByObject($object): void
    {
        parent::setDataByObject($object);

        if (isset($object->sort)) {
            $this->order = (int)$object->sort;
        }
    }

    final public function getParentCategory(): int
    {
        return $this->parentCategory;
    }

    final public function getChildrenCategories(): array
    {
        return $this->childrenCategories;
    }

    final public function addChildCategory(PageCategory $category)
    {
        $this->childrenCategories[$category->getId()] = $category;
    }

    public function getFormattedUrl(): string
    {
        global $_language;

        return $url = $this->{'url_' . $_language} . '-' . $this->id;
    }

    public function getFullUrl(): string
    {
        global $_language;

        $url = '/' . $_language . '/' . $this->getFormattedUrl();

        return $url;
    }

    public function hasChildrenCategories($columnName = 'parent'): bool
    {
        $query = "SELECT count(*) AS number FROM {$this->dbTable} WHERE $columnName = {$this->getId()}";
        if (!$result = Database::query($query)) {
            $_messages->add(ALERT_TYPE_DEBUGQUERY, $query, 'Check this function in the custom class settings');
        } else {
            if ($record = Database::fetch($result)) {
                return ($record->number > 0 ? true : false);
            }
        }
    }

    public function getAssociatedPages(string $where = '')
    {
        global $_language, $_messages;

        $pages = [];

        $query = <<<SQL
        SELECT pages.id AS page_id, pages.title_$_language AS page_title, pages.url_$_language AS page_url, pages.html_$_language AS page_html, image
        FROM pages
            JOIN link_pages_categories ON pages.id = link_pages_categories.id_page
        WHERE link_pages_categories.id_category = $this->id
            AND pages.title_$_language != ''
            AND pages.url_$_language != ''
            AND pages.html_$_language != ''
        ORDER BY pages.id DESC
        SQL;

        if (!$result = Database::query($query)) {
            $_messages->add(ALERT_TYPE_DEBUGQUERY, $query, 'Could not retrieve associated pages');
        } else {
            while ($record = Database::fetch($result)) {
                $pages[$record->page_id] = $record;
            }
        }

        return $pages;
    }

    final public function renderHtml(): string
    {
        $html = '';
        return $html;
    }

    final public function loadDataByUrl(): bool
    {
        global $_language, $_messages;

        if (!$this->url = Router::getUrlPiece(1)) {
            return false;
        }

        if (!$categoryIdPosition = strripos($this->url, '-')) {
            $_messages->add(ALERT_TYPE_DEBUG, 'Missing ID in category url');
            return false;
        }

        if (!$categoryId = (int)substr($this->url, $categoryIdPosition + 1)) {
            $_messages->add(ALERT_TYPE_DEBUG, 'Wrong ID in category url');
            return false;
        }

        $categoryUrl = substr($this->url, 0, $categoryIdPosition);

        $where = 'url_' . $_language . ' = ' . Database::escapeLiteral($categoryUrl);
        $where .= ' AND id = ' . $categoryId;

        if (!$this->record = $this->getRecordByCondition($where)) {
            return false;
        }

        $this->setDataByObject($this->record);

        return true;
    }
}

class PageCategoryIndex extends PageCategory
{
}

class PageImage extends PageImageBase
{
}

<?php

class PagesAndCategories
{
    protected $pages = [];
    protected $categories = [];

    public function setPages(array $pages): void
    {
        $this->pages = $pages;
        return;
    }

    public function setCategories(array $categories): void
    {
        $this->categories = $categories;
        return;
    }

    public function getPages(): array
    {
        return $this->pages;
    }

    public function getCategories(): array
    {
        return $this->categories;
    }
}

class Pages extends DbTable
{
    protected $dbTable = 'pages';
    protected $recordClassToCreate = 'Page';
    protected $columnNameForOptionsList = 'id';

    public function renderHtml(): string
    {
        $html = '';
        return $html;
    }

    final public function renderHtmlMultilanguage(string $language = '')
    {
    }

    final public function getPagesAndCategories(): PagesAndCategories
    {
        // NOTE: This function is a bit of a mess at the moment, needs rewriting

        global $_language, $_messages;

        $query = "SELECT pages.id AS page_id, pages.title_$_language AS page_title, page_categories.id AS category_id, page_categories.title_$_language AS category_title FROM pages LEFT JOIN link_pages_categories ON pages.id = link_pages_categories.id_page LEFT JOIN page_categories ON link_pages_categories.id_category = page_categories.id ORDER BY page_categories.id ASC, pages.id ASC";

        $pagesAndCategories = [];
        $pages = [];
        $categories = [];

        if (!$result = Database::query($query)) {
            $_messages->add(ALERT_TYPE_DEBUGQUERY, $query);
        } else {
            while ($record = Database::fetch($result)) {
                $page = null;
                $category = null;

                if ((int)$record->category_id) {
                    if (isset($categories[$record->category_id])) {
                        $category = $categories[$record->category_id];
                    } else {
                        $category = new PageCategory();
                        if ($categoryRecord = $category->getRecordById($record->category_id)) {
                            $category->setDataByObject($categoryRecord);
                            $categories[$category->getId()] = $category;
                        }
                    }
                }

                if (isset($pages[$record->page_id])) {
                    $page = $pages[$record->page_id];
                } else {
                    $page = new Page();
                    if ($pageRecord = $page->getRecordById($record->page_id)) {
                        $page->setDataByObject($pageRecord);
                        $pages[$page->getId()] = $page;
                    }
                }

                if ($category) {
                    $pages[$record->page_id]->addCategory($category);
                }
            }
        }

        if ($pages and $categories) {
            $pagesAndCategories = new PagesAndCategories();
            $pagesAndCategories->setPages($pages);
            $pagesAndCategories->setCategories($categories);
        }

        return $pagesAndCategories;
    }

    public function getLastArticles(int $numberOfArticles, string $indexColumn = '')
    {
        global $_language, $_messages;

        $articles = [];
        $fields = "pages.id AS page_id, page_categories.id AS category_id, pages.url_$_language AS url, pages.title_$_language AS title, pages.html_$_language AS html, pages.image AS image, page_categories.title_$_language AS category_title, page_categories.url_$_language AS category_url";

        $query = <<<SQL
        SELECT DISTINCT $fields
        FROM pages
            JOIN link_pages_categories ON pages.id = link_pages_categories.id_page
            JOIN page_categories ON link_pages_categories.id_category = page_categories.id
        WHERE page_categories.id NOT IN (1000)
            AND pages.title_$_language != ''
            AND pages.url_$_language != ''
            AND pages.html_$_language != ''
            AND page_categories.url_$_language != ''
            AND NOT pages.hidden_$_language
            AND NOT page_categories.hidden_$_language
            GROUP BY pages.id
            ORDER BY pages.id DESC
            LIMIT $numberOfArticles;
        SQL;

        if (!$result = Database::query($query)) {
            $_messages->add(ALERT_TYPE_DEBUGQUERY, $query);
            $_messages->add(ALERT_TYPE_ERROR, 'Could not retrieve last articles.');
        } else {
            while ($record = Database::fetch($result)) {
                $articles[$record->page_id] = $record;
            }
        }

        return $articles;
    }
}

class PageCategories extends DbTable
{
    protected $dbTable = 'page_categories';
    protected $recordClassToCreate = 'PageCategory';
    protected $indexColumn = 'id';
    protected $columnNameForOptionsList = 'title_en';

    final public function renderHtmlMultilanguage(string $language)
    {
        global $_page;
        $html = '';
        if ($this->list) {
            $html .= '<ul id="categories-list" class="elements-list">'."\n";
            foreach ($this->list as $id=>$category) {
                $html .= '<li>';
                if ($category->getHtml()) {
                    $html .= $category->getHtml();
                } else {
                    $html .= '<div class="text">'."\n";
                    $html .= '<h2><a href="' . $_SERVER['SCRIPT_NAME'] . '?category = ' . $category->getId() . '">' . htmlspecialchars($category->getName()) . '</a></h2>';
                    $html .= '</div>';
                }
                $html .= '</li>'."\n";
            }
            $html .= "</ul>\n";
        }
        return $html;
    }

    final public function getParentCategories(): array
    {
        return $this->generateParentCategoriesList();
    }

    final protected function generateParentCategoriesList(): array
    {
        $parentCategories = [];

        foreach ($this->list as $category) {
            if ($category->getParentCategory()) {
                $parentCategories[$category->getParentCategory()][] = $category;
            }
        }

        return $parentCategories;
    }

    final public function generateCategoriesTreeList(): array
    {
        $allCategories = $this->list;

        foreach ($this->list as $category) {
            if ($category->getParentCategory()) {
                $allCategories[$category->getParentCategory()]->addChildCategory($category);
                unset($allCategories[$category->getId()]);
            }
        }

        return $allCategories;
    }
}

class PageBase extends DbTableRecord
{
    protected $dbTable = 'pages';
    protected $url = '';
    protected $html = '';
    protected $image = null;
    protected $hidden = false;
    protected $adminCreator = null;
    protected $adminLastEditor = null;
    protected $groupAssigned = null;
    protected $category = 0;
    protected $template = 0;
    protected $module = '';
    protected $empty = false;
    protected $noIndex = false;
    protected $noFollow = false;
    protected $notFound = false;
    protected $categories = [];
    protected static $classNameReadable = '';

    public function __construct()
    {
        parent::__construct();

        self::$classNameReadable = _('Page');
    }

    public function setDataByObject($object): void
    {
        parent::setDataByObject($object);
        $this->url = $object->url ?? '';
        $this->html = $object->content ?? '';
        $this->image = $object->image ?? null;
        $this->adminCreator = $object->admin_creator ?? null;
        $this->adminLastEditor = $object->admin_last_editor ?? null;
        $this->groupAssigned = $object->group_assigned ?? null;
        $this->category = $object->category ?? 0;
        $this->template = $object->template ?? 0;
        $this->module = $object->module ?? '';

        if (isset($object->empty) and $object->empty == 'true') {
            $this->empty = true;
        }

        if (isset($object->noIndex) and $object->noIndex == 'true') {
            $this->noIndex = true;
        }

        if (isset($object->noFollow) and $object->noFollow == 'true') {
            $this->noFollow = true;
        }

        if (isset($object->notFound) and $object->notFound == 'true') {
            $this->notFound = true;
        }

        return;
    }

    public function renderHtml(): string
    {
        $html = '';
        return $html;
    }

    public function loadDataByUrl(): bool
    {
        global $_language, $_messages;

        if (!$this->category = Router::getUrlPiece(1)) {
            return false;
        }

        if (!$this->url = Router::getUrlPiece(2)) {
            return false;
        }


        if (!$this->url or !$this->category) {
            return false;
        }

        $pageIdPosition = strripos($this->url, '-');

        if (!$pageIdPosition) {
            $_messages->add(ALERT_TYPE_DEBUG, 'Missing ID in page url');
            return false;
        }

        if (!$pageId = (int)substr($this->url, $pageIdPosition + 1)) {
            $_messages->add(ALERT_TYPE_DEBUG, 'Wrong ID in page url');
            return false;
        }

        $category = new PageCategory();

        if (!$category->loadDataByUrl()) {
            return false;
        }

        $pageUrl = substr($this->url, 0, $pageIdPosition);

        $where = 'url_' . $_language . ' = ' . Database::escapeLiteral($pageUrl);
        $where .= ' AND id = ' . $pageId;

        if (!$this->record = $this->getRecordByCondition($where)) {
            return false;
        }

        $this->setDataByObject($this->record);

        return true;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getFormattedUrl(): string
    {
        global $_language;

        return $url = $this->{'url_' . $_language} . '-' . $this->id;
    }

    public function getFullUrl(): string
    {
        global $_language, $_debugMode;

        $url = '/' . $_language;
        if ($_debugMode) {
            var_dump($this->category);
        }
        if ($this->category) {
            $url .= '/' . $this->category->getFormattedUrl();
        }

        $url .= '/' . $this->getFormattedUrl();

        return $url;
    }

    final public function getImage(): ?string
    {
        return $this->image;
    }

    final public function setAdminCreator(int $adminId): void
    {
        global $_db, $_messages;
        $admin = new Admin();
        $record = $admin->getRecordById($adminId);
        $admin->setDataByObject($record);
        $this->adminCreator=clone $admin;
        unset($admin, $record);
        return;
    }

    final public function getAdminCreator(): Admin
    {
        return $this->adminCreator;
    }

    final public function setAdminLastEditor(int $adminId): void
    {
        global $_db, $_messages;
        $admin = new Admin();
        $record = $admin->getRecordById($adminId);
        $admin->setDataByObject($record);
        $this->adminLastEditor = clone $admin;
        unset($admin, $record);
        return;
    }

    final public function getAdminLastEditor(): Admin
    {
        return $this->adminLastEditor;
    }

    final public function setGroupAssigned(int $groupId): void
    {
        global $_db, $_messages;
        $group = new AdminGroup();
        $record = $group->getRecordById($groupId);
        $group->setDataByObject($record);
        $this->groupAssigned = clone $group;
        unset($group, $record);
        return;
    }

    final public function getGroupAssigned(): AdminGroup
    {
        return $this->groupAssigned;
    }

    final public function getTemplate(): int
    {
        return $this->template;
    }

    final public function getModule(): string
    {
        return $this->module;
    }

    final public function isEmpty(): bool
    {
        return $this->empty;
    }

    final public function hasNoIndex(): bool
    {
        return $this->noIndex;
    }

    final public function hasNoFollow(): bool
    {
        return $this->noFollow;
    }

    final public function isNotFound(): bool
    {
        return $this->notFound;
    }

    public function loadHomepage(): bool
    {
        global $_languages, $_language, $_db, $_messages;
        $query = '';

        $query .= "SELECT * FROM $this->dbTable WHERE url_$_language = " . Database::escapeLiteral(Config::$defaultHomepageUrl);

        if (!$result = Database::query($query)) {
            $_messages->add(ALERT_TYPE_ERROR, _('Problems loading homepage'));
            $_messages->add(ALERT_TYPE_DEBUGQUERY, $query);
            return false;
        } else {
            if (!$record = Database::fetch($result)) {
                $_messages->add(ALERT_TYPE_DEBUG, _('Homepage was not found in the database. Did you check config?'));
                return false;
            } else {
                $this->setDataByObject($record);
            }
        }

        return true;
    }

    final public function addCategory(PageCategory $category): void
    {
        $this->categories[$category->getId()] = clone $category;
        unset($category);
        return;
    }

    final public function getCategories(): array
    {
        return $this->categories;
    }

    final public function hasCategory(int $categoryId): bool
    {
        return isset($this->categories[$categoryId]);
    }

    final public function loadCategories(): void
    {
        global $_messages;

        $query = "SELECT id FROM page_categories JOIN link_pages_categories ON page_categories.id = link_pages_categories.id_category WHERE link_pages_categories.id_page = {$this->getId()}";

        if (!$result = Database::query($query)) {
            $_messages->add(ALERT_TYPE_DEBUGQUERY, $query, 'Could not get categories for page with id ' . $this->getId());
        } else {
            while ($record = Database::fetch($result)) {
                $category = new PageCategory();
                $category->setDataByObject($category->getRecordById($record->id));
                $this->categories[$category->getId()] = $category;
            }
        }

        return;
    }

    final public function getCategoriesAsArrayKeyValue(): array
    {
        $categories = [];

        if (!$this->getCategories()) {
            return [];
        }

        foreach ($this->getCategories() as $category) {
            $categories[$category->getId()] = $category->getPropertyMultilang('title');
        }

        return $categories;
    }

    public function deleteImages(): bool
    {
        if ($this->image) {
            if (!$this->deleteImage(Config::$uploadsAbsoluteDir . $this->image, 'image')) {
                return false;
            }
        }

        return true;
    }

    final public function deleteImage(string $path, string $dbColumn): bool
    {
        global $_messages;

        $query = "UPDATE " . $this->dbTable . " SET {$dbColumn} = null WHERE id = $this->id;";

        if (is_file($path)) {
            if (!unlink($path)) {
                return false;
            } else {
                if (!Database::query($query)) {
                    $_messages->add(ALERT_TYPE_DEBUGQUERY, $query, _("Error updating {$dbColumn} column in database"));
                    return false;
                }
            }
        }

        return true;
    }

    final public function delete(): bool
    {
        global $_messages;

        if (!$this->deleteImages()) {
            $_messages->add(ALERT_TYPE_ERROR, _('Error deleting image'));
            return false;
        }

        return parent::delete();
    }
}

class PagesCategoriesAssociation extends DbTableRecord
{
    protected $dbTable = 'pagine_categorie';
    protected $pageId = 0;
    protected $categoryId = 0;
    protected $page = null;

    final public function setDataByObject($object): void
    {
        parent::setDataByObject($object);
        $this->pageId = $object->id_pagina;
        $this->categoryId = $object->id_categoria;
    }

    final public function setPage()
    {
        if ($this->pageId) {
            $page = new Page();
            $recordPage = $page->getRecordById($this->pageId);
            $page->setDataByObject($recordPage);
            $this->page = clone $page;
            unset($page);
        }
    }

    final public function getPage()
    {
        return $this->page;
    }

    final public function setCategory()
    {
        if ($this->categoryId) {
            $category = new PageCategory();
            $recordCategory = $category->getRecordById($this->categoryId);
            $category->setDataByObject($recordCategory);
            $this->category = clone $category;
            unset($category);
        }
    }

    final public function getCategory()
    {
        return $this->category;
    }

    final public function renderHtml(): string
    {
        $html = '';
        return $html;
    }
}

class PageImageBase extends DbTableRecord
{
    final public function renderHtml(): string
    {
        $html = '';
        return $html;
    }
}

if (is_file($_SERVER['APP_ROOT'].'models/custom/pages-custom.php')) {
    include_once $_SERVER['APP_ROOT'].'models/custom/pages-custom.php';
} else {
    throw new LogicException('Missing custom pages file');
}

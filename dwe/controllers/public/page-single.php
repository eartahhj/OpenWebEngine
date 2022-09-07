<?php

use DifferentWebEngine\EmailSender\Email;

require_once $_SERVER['APP_ROOT'] . 'models/pages.php';
require_once $_SERVER['APP_ROOT'] . 'models/comments.php';
require_once $_SERVER['APP_ROOT'] . 'models/ads.php';
require_once $_SERVER['APP_ROOT'] . 'vendor/forms.php';

$_page = new \Page();

$pageAdTop = $pageAdBottom = null;

if (!empty($siteAds) and !empty($siteAds[$_language])) {
    $randomAdTopNumber = rand(1, 3);
    $randomAdBottomNumber = rand(4, 6);
    $pageAdTop = $siteAds[$_language][$randomAdTopNumber];
    $pageAdBottom = $siteAds[$_language][$randomAdBottomNumber];
}

if (!$_page->loadDataByUrl()) {
    require_once $_SERVER['APP_ROOT'] . 'views/public/404-notfound.php';
}

if (!$_page->returnDefaultTitle() or $_page->isHidden($_language)) {
    require_once $_SERVER['APP_ROOT'] . 'views/public/404-notfound.php';
}

$matomoTracker->doTrackPageView($_page->returnDefaultTitle());

$_page->loadTags();
$_page->loadCategories();

$pageCategories = $_page->getCategories();
$categoryId = key($_page->getCategories());
$category = $_page->getCategory($categoryId);

$breadcrumb = ['/' . $_language => _('Homepage')];

if (is_a($category, 'PageCategory')) {
    $breadcrumb[$category->getFullUrl()] = $category->returnDefaultTitle();
}

$breadcrumb[$category->getFullUrl() . '/' . $_page->getFormattedUrl()] = $_page->returnDefaultTitle();

$pageTags = $_page->getTags();

require_once $_SERVER['APP_ROOT'] . 'app/form-templates/comment.php';

$pageComments = new \Comments();
$pageComments->setOrderBy('date_created DESC');
$where = "id_page = {$_page->getId()} AND language = '{$_language}' AND approved";
$pageComments->setRecords($pageComments->getAllRecords($where));
$pageComments->prepareList();


if (isset($_POST['save']) and $_POST['formID'] == $form->getId()) {
    if (!$form->validateFields()) {
        $_messages->add(ALERT_TYPE_ERROR, _('There was an error with your comment, please check the form at the bottom of the page.'));
    } else {
        $newComment = new \Comment();
        $author = $form->getFieldByName('author_name');
        $text = $form->getFieldByName('text');
        $senderEmail = $form->getFieldByName('email');
        $fields = [
            'author_name' => \Database::escapeLiteral($author->getValue()),
            'text' => \Database::escapeLiteral($text->getValue()),
            'email' => \Database::escapeLiteral($senderEmail->getValue()),
            'id_page' => $_page->getId(),
            'language' => \Database::escapeLiteral($_language)
        ];

        if (!$pageComments->insertComment($fields)) {
            $_messages->add(ALERT_TYPE_ERROR, _('Error saving comment'));
        } else {
            $_messages->add(ALERT_TYPE_CONFIRM, _('Comment succesfully added! Your comment will be reviewed and published if approved.'));

            require_once $_SERVER['APP_ROOT'] . 'app/email-sender.php';

            $email = new Email();
            $email->setSubject(_('New comment posted on www.yourwebsite.com'));
            $email->setFrom('yourmail@domain.com');
            $email->addAddressTo('yourmail@domain.com');
            $emailBody = '<p>' . _('Comment posted on the page:') . ' ' . $_page->getTitle() . '</p>';
            if ($author->getValue()) {
                $emailBody .= '<p>' . _('Author:') . ' ' . htmlspecialchars($author->getValue()) . '</p>';
            }
            if ($senderEmail->getValue()) {
                $email->addReplyTo($senderEmail->getValue());
                $emailBody .= '<p>' . _('Email:') . ' ' . htmlspecialchars($senderEmail->getValue()) . '</p>';
            }
            $emailBody .= '<p>' . htmlspecialchars($text->getValue()) . '</p>';
            $email->setBody($emailBody);
            $email->send();
        }
    }
}

$html = '';

if ($_page->getPropertyMultilang('html')) {
    $html = $_page->getPropertyMultilang('html');
} elseif ($_page->getHtml()) {
    $html = $_page->getHtml();
}

if ($html) {
    $html = $_page->convertHtmlShortcodes($html);
} else {
    require_once $_SERVER['APP_ROOT'] . 'views/public/404-notfound.php';
}

require_once $_SERVER['APP_ROOT'] . 'models/templates.php';
$_template = new \TemplateStandard();

if ($_admin->isLogged() and $_admin->isEnabled()) {
    $_template->addCss('/css/style-adminbar.css');
}

$_template->addCss('/js/prism/prism.css');
$_template->addJavascript('/js/prism/prism.js');

if ($_language == 'it') {
    $dateCreation = getItalianDateTimeFromISOTimestamp($_page->getDateCreation());
    $dateModified = getItalianDateTimeFromISOTimestamp($_page->getDateModified());
} else {
    $dateCreation = getEuropeanDateFromISOTimestamp($_page->getDateCreation());
    $dateModified = getEuropeanDateFromISOTimestamp($_page->getDateModified());
}

$pageTimestampString = _('Published') . ': ' . $dateCreation;
if ($dateModified) {
    $pageTimestampString .= '<span class="only-desktop">,</span><br class="only-mobile" /> ' . _('Last updated') . ': ' . $dateModified;
}

$comments = $pageComments->getList();

<?php

$action = Router::getAction();

if (!$action or $action == 'list') {
    Router::redirectTo('/admin/filesmanager/list');
}

if ($action != 'edit' and $action != 'create') {
    exit();
}

require_once $_SERVER['APP_ROOT'] . 'vendor/forms.php';
require_once $_SERVER['APP_ROOT'] . 'models/files.php';
require_once $_SERVER['APP_ROOT'] . 'models/templates.php';

$_template = new TemplateAdmin();

$fileId = null;
$file = new File();
$fileRecord = null;

if (!$action != 'create') {
    $fileId = (int)Router::getParams();
}

if ($action == 'edit') {
    if (!$fileId) {
        Router::redirectTo('/admin/filesmanager/');
    }

    $fileRecord = $file->getRecordById($fileId);

    if (!$fileRecord) {
        $_messages->add(ALERT_TYPE_ERROR, _('No data found'));
        $file = null;
        $_template->exit();
    } else {
        $file->setDataByObject($fileRecord);
    }
} elseif ($action == 'create') {
    if (isset($_REQUEST['save'])) {
        $fileId = $file->getCurrentSequenceId(); # Update the file ID because it still does not exist
        $file->setId($fileId);
    }
}

require_once $_SERVER['APP_ROOT'] . 'app/form-templates/admin/filesmanager.php';

if (isset($_REQUEST['save'])) {
    if (!$form->validateFields()) {
        echo $form->renderErrors();
    } else {
        if ($action == 'edit') {
            $fields = $form->getFieldsAsArrayForQuery();

            if ($mimeType = mime_content_type(Config::$mediaLibraryAbsoluteDir . $file->getFileName())) {
                $fields['mimetype'] = Database::escapeLiteral($mimeType);
            }

            if (Database::queryUpdate('files', $fields, "id = {$file->getId()}")) {
                $_messages->add(ALERT_TYPE_CONFIRM, _('File updated:') . ' ' . Config::$mediaLibraryRelativeDir . $file->getFileName());
            }

            $query = '';
            $queryError = false;
        } elseif ($action == 'create') {
            if ($fields = $form->getFieldsAsArrayForQuery()) {
                if (Database::queryInsert('files', $fields)) {
                    $fileRecord = $file->getRecordById($fileId);
                    $file->setDataByObject($fileRecord);
                    $_messages->add(ALERT_TYPE_CONFIRM, _('File uploaded:') . ' <a href="' . Config::$mediaLibraryRelativeDir . $file->getFileName() . '">' . Config::$mediaLibraryRelativeDir . $file->getFileName() . '</a>');

                    if ($mimeType = mime_content_type(Config::$mediaLibraryAbsoluteDir . $file->getFileName())) {
                        if (!Database::queryUpdate('files', ['mimetype' => Database::escapeLiteral($mimeType)], "id = {$file->getId()}")) {
                            $_messages->add(ALERT_TYPE_DEBUG, _('Error updating file mimetype'));
                        }
                    }
                } else {
                    $_messages->add(ALERT_TYPE_ERROR, _('Errors inserting the file record in the database'));
                }
            }
        }
    }
} elseif (isset($_REQUEST['delete']) and $action == 'edit') {
    $file->delete();
}

$title = '';

if ($action == 'edit') {
    $title = _('Edit file');
} elseif ($action == 'create') {
    $title = _('Upload file');
}

if ($file->returnDefaultTitle()) {
    $title .= ': ' . $file->returnDefaultTitle();
}

<?php

namespace View\Admin\Pages;

use Trait\View\Admin\AdminPagesTemplate;
use Controller\Admin\Pages\PagesHandler;
use Model\Page;
use Model\Database;

class AdminPagesPagesEdit extends AdminPagesTemplate
{
    private ?Page $page = null;
    protected function __construct()
    {
        $this->settingsPath = 'Pages';
        $this->settingsName = 'Page edit';
    }

    protected function renderSettingsDashboard(): string
    {

        // if we don't have a page
        // user gets redirects to Pages Overview
        if (is_null($this->page)) {

            // end buffering, making it possible to send out the headers
            ob_end_clean();

            // redirect user to the the view pages site
            header('Location: ' . $_SERVER['SERVER_NAME'] . '/admin/pages/view', true, 404);  # should add an errorcodes
            exit;
        }
        return '<form method="POST"><input name="id" value="' . $this->page->pageId . '"><textarea name="content">' . $this->page->pageContent . '</textarea><input type="submit"></form>';
    }

    public function handleGetRequest(array &$GETRequest): bool
    {
        // check if the user tries to edit a page
        // if not, return False
        $pageId = $this->getPageId($GETRequest);
        if (is_null($pageId)) {
            return false;
        }

        $handler = new PagesHandler();

        // getting the content of the page
        $page = $handler->getPage($pageId);
        if (!is_null($page)) {

            $this->page = $page;
            return true;
        }

        return false;
    }

    public function handlePostRequest(array &$POSTRequest): bool
    {

        if (!$this->isValidPost($POSTRequest)) {
            return false;
        }

        $pageId = $POSTRequest['id'];

        // saving changes
        $handler = new PagesHandler();
        $handler->saveChanges($pageId, $POSTRequest['content']);

        // getting the content of the page
        $page = $handler->getPage($pageId);
        if (!is_null($page)) {

            $this->page = $page;
            return true;
        }

        # should throw error to display to users
        return false;
    }

    /**
     * Checks if a Post request has all the necessary fields.
     * 
     * @return bool Returns `true` if the Post request is valid, otherwise `false`.
     */
    private function isValidPost(&$POSTRequest): bool
    {
        if (!isset($POSTRequest['id']) or !isset($POSTRequest['content'])) {
            return false;
        }

        return true;
    }

    /**
     * Function to get the article if from a Get request
     * 
     * @return int|null Returns the page id if one exists, otherwise null.
     */
    private function getPageId(array &$GETRequest): int|null
    {
        if (isset($GETRequest['id'])) {
            return (int) $GETRequest['id'];
        }

        return null;
    }
}
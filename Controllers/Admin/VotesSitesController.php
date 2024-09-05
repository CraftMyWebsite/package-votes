<?php

namespace CMW\Controller\Votes\Admin;

use CMW\Controller\Users\UsersController;
use CMW\Manager\Env\EnvManager;
use CMW\Manager\Flash\Alert;
use CMW\Manager\Flash\Flash;
use CMW\Manager\Lang\LangManager;
use CMW\Manager\Package\AbstractController;

use CMW\Manager\Router\Link;
use CMW\Manager\Views\View;
use CMW\Model\Votes\CheckVotesModel;
use CMW\Model\Votes\VotesRewardsModel;
use CMW\Model\Votes\VotesSitesModel;
use CMW\Utils\Redirect;
use CMW\Utils\Utils;
use JetBrains\PhpStorm\NoReturn;
use function file_exists;
use function is_array;

/**
 * Class: @VotesSitesController
 * @package Votes
 * @author Teyir & Zomb
 * @version 1.0
 */
class VotesSitesController extends AbstractController
{
    #[Link("/site/list", Link::GET, [], "/cmw-admin/votes")]
    private function listSites(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "votes.site.list");

        $sites = VotesSitesModel::getInstance()->getSites();
        $rewards = VotesRewardsModel::getInstance()->getRewards();
        $compatiblesSites = $this->getCompatiblesSites();

        View::createAdminView('Votes', 'list_sites')
            ->addVariableList(["sites" => $sites, "rewards" => $rewards, "compatiblesSites" => $compatiblesSites])
            ->addStyle("Admin/Resources/Assets/Css/simple-datatables.css")
            ->addScriptAfter("Admin/Resources/Vendors/Simple-datatables/simple-datatables.js",
                "Admin/Resources/Vendors/Simple-datatables/config-datatables.js", "App/Package/Votes/Views/Resources/Js/testSitesId.js")
            ->view();
    }

    #[NoReturn] #[Link("/site/list", Link::POST, [], "/cmw-admin/votes")]
    private function addSiteAdminPost(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "votes.site.add");

        [$title, $time, $idUnique, $url, $rewardsId] = Utils::filterInput("title", "time", "idUnique",
            "url", "reward");

        if ($rewardsId === '0') {
            $rewardsId = null;
        }

        VotesSitesModel::getInstance()->addSite($title, $time, $idUnique, $url, $rewardsId);

        Flash::send(Alert::SUCCESS, LangManager::translate("core.toaster.success"),
            LangManager::translate("votes.toaster.site.add.success", ["name" => $title]));

        Redirect::redirectPreviousRoute();
    }

    #[Link("/site/list", Link::GET, [], "/cmw-admin/votes")]
    private function votesSitesEdit(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "votes.site.edit");

        $votes = VotesSitesModel::getInstance()->getSiteById(filter_input(INPUT_POST, 'siteId'));

        View::createAdminView('Votes', 'list_sites')
            ->addVariableList(["votes" => $votes])
            ->view();
    }

    #[NoReturn] #[Link("/site/edit", Link::POST, [], "/cmw-admin/votes")]
    private function votesSitesEditPost(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "votes.site.edit");

        [$siteId, $title, $time, $idUnique, $url, $rewardsId] = Utils::filterInput("siteId", "title", "time",
            "idUnique", "url", "reward");

        if ($rewardsId === '0') {
            $rewardsId = null;
        }

        VotesSitesModel::getInstance()->updateSite($siteId, $title, $time, $idUnique, $url, $rewardsId);

        Flash::send(Alert::SUCCESS, LangManager::translate("core.toaster.success"),
            LangManager::translate("votes.toaster.site.edit.success", ["name" => $title]));

        Redirect::redirectPreviousRoute();
    }

    #[NoReturn] #[Link("/site/delete/:id", Link::GET, ['id' => '[0-9]+'], "/cmw-admin/votes")]
    private function deleteSitePostAdmin(int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "votes.site.delete");

        $title = VotesSitesModel::getInstance()->getSiteById($id)?->getTitle();

        VotesSitesModel::getInstance()->deleteSite($id);

        Flash::send(Alert::SUCCESS, LangManager::translate("core.toaster.success"),
            LangManager::translate("votes.toaster.site.delete.success", ["name" => $title]));

        Redirect::redirectPreviousRoute();
    }

    /**
     * @throws \JsonException
     */
    #[Link("/site/test/id", Link::POST, [], "/cmw-admin/votes", secure: false)]
    private function checkSiteId(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "votes.site.add");

        [$url, $siteId] = Utils::filterInput('url', 'site_id');

        if (Utils::containsNullValue($url, $siteId)) {
            print (json_encode(["status" => "0", "toaster" =>
                ["type" => "error",
                    "title" => LangManager::translate("core.toaster.error"),
                    "content" => LangManager::translate("votes.toaster.site.test_id.empty_input"),
                ],
            ], JSON_THROW_ON_ERROR));
            return;
        }

        if (CheckVotesModel::getInstance()->testSiteId($url, $siteId)) {
            print (json_encode(["status" => "1", "toaster" =>
                ["type" => "success",
                    "title" => LangManager::translate("core.toaster.success"),
                    "content" => LangManager::translate("votes.toaster.site.test_id.success"),
                ],
            ], JSON_THROW_ON_ERROR));
        } else {
            print (json_encode(["status" => "0", "toaster" =>
                ["type" => "error",
                    "title" => LangManager::translate("core.toaster.error"),
                    "content" => LangManager::translate("votes.toaster.site.test_id.error"),
                ],
            ], JSON_THROW_ON_ERROR));
        }
    }


    /***
     *
     * PUBLIC METHODS
     *
     */

    public function getCompatiblesSites(): array
    {
        $file = EnvManager::getInstance()->getValue("DIR") . "App/Package/Votes/SitesCompatibles.php";

        if (!file_exists($file)) {
            return [];
        }

        $content = include $file;

        if (!is_array($content)) {
            return [];
        }

        return $content;
    }
}
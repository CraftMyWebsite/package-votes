<?php

namespace CMW\Controller\Votes;

use CMW\Controller\Core\CoreController;
use CMW\Manager\Api\APIManager;
use CMW\Model\Users\UsersModel;
use CMW\Router\Link;
use CMW\Utils\Utils;


/**
 * Class: @VotesController
 * @package Votes
 * @author Teyir
 * @version 1.0
 */
class VotesApiController extends CoreController
{

    #[Link("/test", Link::GET, scope: "/api/votes")]
    public function testAPI(): void
    {
        header("Content-Type: application/json; charset=UTF-8");
        $post = APIManager::getRequest("http://xxx.xxx.xxx.xxx:24102/votes/send/validate/". UsersModel::getCurrentUser()?->getUsername() ."/" . base64_encode("Site 1 de test") . "/" . base64_encode("3 Votepoints") );
        echo($post);

    }

    #[Link("/send", Link::GET, scope: "/api/votes")]
    public function sendAPI(): void
    {
        header("Content-Type: application/json; charset=UTF-8");

        $post = APIManager::createResponse("ezez", secure: false);
        echo($post);

    }


}

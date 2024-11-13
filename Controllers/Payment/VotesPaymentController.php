<?php

namespace CMW\Controller\Votes\Payment;

use CMW\Controller\Shop\Admin\Item\ShopItemsController;
use CMW\Controller\Users\UsersSessionsController;
use CMW\Event\Shop\ShopPaymentCancelEvent;
use CMW\Event\Shop\ShopPaymentCompleteEvent;
use CMW\Manager\Events\Emitter;
use CMW\Manager\Flash\Alert;
use CMW\Manager\Flash\Flash;
use CMW\Manager\Lang\LangManager;
use CMW\Manager\Package\AbstractController;
use CMW\Model\Votes\VotesRewardsModel;
use CMW\Model\Votes\VotesStatsModel;
use CMW\Utils\Redirect;

/**
 * Class: @VotesPaymentController
 * @package Votes
 * @author Zomblard
 * @version 0.0.1
 */
class VotesPaymentController extends AbstractController
{
    /**
     * @param \CMW\Entity\Shop\Carts\ShopCartItemEntity[] $cartItems
     * @throws \ReflectionException
     */
    public function payByVotePoints(array $cartItems): void
    {
        $user = UsersSessionsController::getInstance()->getCurrentUser();

        if (!$user) {
            Flash::send(Alert::ERROR, 'Erreur', 'Utilisateur introuvable');
            Redirect::redirectToHome();
        }

        $priceTypeMethod = ShopItemsController::getInstance()->getPriceTypeMethodsByVarName('votePoints');

        if (!$priceTypeMethod) {
            Flash::send(
                Alert::ERROR,
                LangManager::translate('core.toaster.error'),
                LangManager::translate('core.toaster.internalError'),
            );
        }

        $votePointsStock = VotesStatsModel::getInstance()->getVotePointByUserId($user->getId());

        $totalAmount = 0;
        foreach ($cartItems as $item) {
            $totalAmount = $item->getTotalPriceComplete();
        }

        if ($totalAmount > $votePointsStock) {
            $message = "Vous n'avez pas assez de " . $priceTypeMethod->name();
            Emitter::send(ShopPaymentCancelEvent::class, $message);
        } else {
            if (VotesRewardsModel::getInstance()->removeRewardVotePoints($user->getId(), $totalAmount)) {
                Emitter::send(ShopPaymentCompleteEvent::class, []);
            } else {
                $message = "Une erreur s'est produite merci de réessayer";
                Emitter::send(ShopPaymentCancelEvent::class, $message);
            }
        }
    }
}

<?php
/**
 * Okaeli_ComingSoon  Extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE Version 3
 * that is bundled with this package in the file LICENSE
 *
 * @category Okaeli
 * @package Okaeli_ComingSoon
 * @copyright  Copyright (c)  2017 Julien Loizelet
 * @author     Julien Loizelet <julienloizelet@okaeli.com>
 * @license    GNU GENERAL PUBLIC LICENSE Version 3
 *
 */

/**
 *
 * @category Okaeli
 * @package  Okaeli_ComingSoon
 * @module   ComingSoon
 * @author   Julien Loizelet <julienloizelet@okaeli.com>
 *
 */
class Okaeli_ComingSoon_Model_Observer
{

    /**
     *  Redirect all pages (even 404, etc) to comingsoon or another specific url
     * @param Varien_Event_Observer $observer
     */
    public function redirectToComingSoon(Varien_Event_Observer $observer)
    {
        /** @var Okaeli_ComingSoon_Helper_Data $helperComingSoon */
        $helperComingSoon = Mage::helper('okaeli_comingsoon');

        if (!$helperComingSoon->getEnabled()) {
            return;
        }

        if ($helperComingSoon->isUnredirectIp()) {
            return;
        }

        $action = $observer->getEvent()
            ->getFront()
            ->getAction();
        $uri = $action->getRequest()->getRequestUri();

        $regexp = ($helperComingSoon->getSpecificRedirection()) ?
            '^\/' . $helperComingSoon->getSpecificRedirection() . '\/?$' : '^\/comingsoon\/?$';
        $allowedUrls = $helperComingSoon->getUnredirectedUrls();

        foreach ($allowedUrls as $allowed) {
            if (!empty($allowed)) {
                $regexp .= '|^' . $allowed . '\/?$';
            }
        }

        if (preg_match('#(' . $regexp . ')#', $uri)) {
            $helperComingSoon->debugLog($uri . ' is an allowed URI.');
            return;
        }

        $helperComingSoon->debugLog($uri . ' is not an allowed URI.');

        $targetUrl =
            ($helperComingSoon->getSpecificRedirection()) ? $helperComingSoon->getSpecificRedirection() : 'comingsoon';
        $redirectUrl = Mage::getUrl($targetUrl);
        $message = $uri . ' has been redirected to ' . $redirectUrl . '.';
        $helperComingSoon->debugLog($message);
        $action->getResponse()->setRedirect($redirectUrl);
        $action->getRequest()->setDispatched(true);
    }
}
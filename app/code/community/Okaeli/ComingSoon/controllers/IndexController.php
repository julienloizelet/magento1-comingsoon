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
class Okaeli_ComingSoon_IndexController extends Mage_Core_Controller_Front_Action
{

    public function indexAction()
    {
        if (!Mage::helper('okaeli_comingsoon')->getEnabled()) return $this->_redirect('/');
        $this->loadLayout();
        $this->renderLayout();
    }

}

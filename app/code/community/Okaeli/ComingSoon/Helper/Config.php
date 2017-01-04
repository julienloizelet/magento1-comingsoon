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
class Okaeli_ComingSoon_Helper_Config extends Mage_Core_Helper_Abstract
{

    protected $_enabled;
    protected $_isUnredirectedIp;
    protected $_unredirectedUrls;
    protected $_isDebugLogEnabled;
    protected $_specificRedirect;

    const OKAELI_COMINGSOON_XML_PATH_ENABLED = 'okaeli_comingsoon/settings/enabled';
    const OKAELI_COMINGSOON_XML_PATH_UNREDIRECTED_IPS = 'okaeli_comingsoon/settings/unredirected_ips';
    const OKAELI_COMINGSOON_XML_PATH_UNREDIRECTED_URLS = 'okaeli_comingsoon/settings/unredirected_urls';
    const OKAELI_COMINGSOON_XML_PATH_DEBUG = 'okaeli_comingsoon/settings/debug';
    const OKAELI_COMINGSOON_XML_PATH_SPECIFIC_REDIRECTION = 'okaeli_comingsoon/settings/specific_redirection';
    const OKAELI_COMINGSOON_EXPLODE_SEPARATOR = ";";

    /**
     * Get activation status of feature
     * @param null $store
     * @return bool|mixed
     */
    public function getEnabled($store = null)
    {
        if ($this->_enabled === null) {
            $enabled = Mage::getStoreConfig(self::OKAELI_COMINGSOON_XML_PATH_ENABLED, $store);
            $this->_enabled = ($enabled) ? $enabled : false;
        }

        return $this->_enabled;
    }

    /**
     * Get whitelist IPs
     * @param null $store
     * @return bool
     */
    public function isUnredirectIp($store = null)
    {
        if ($this->_isUnredirectedIp === null) {
            $ipAddress = Mage::helper('core/http')->getRemoteAddr();
            $this->debugLog($ipAddress . ' is trying to access.');
            $isUnredirected = in_array(
                $ipAddress, explode(
                    self::OKAELI_COMINGSOON_EXPLODE_SEPARATOR,
                    Mage::getStoreConfig(self::OKAELI_COMINGSOON_XML_PATH_UNREDIRECTED_IPS, $store)
                )
            );
            $message = ($isUnredirected) ? $ipAddress . ' is an allowed ip.' : $ipAddress . ' is not an allowed ip.';
            $this->debugLog($message);
            $this->_isUnredirectedIp = $isUnredirected;
        }

        return $this->_isUnredirectedIp;
    }

    /**
     * Get specific redirection
     * @param null $store
     * @return bool|mixed
     */
    public function getSpecificRedirection($store = null)
    {
        if ($this->_specificRedirect === null) {
            $specificRedirect =
                trim(Mage::getStoreConfig(self::OKAELI_COMINGSOON_XML_PATH_SPECIFIC_REDIRECTION, $store));
            $this->_specificRedirect = (!empty($specificRedirect)) ? $specificRedirect : false;
        }

        return $this->_specificRedirect;
    }

    /**
     * Get whitelist URIs
     * @param null $store
     * @return array
     */
    public function getUnredirectedUrls($store = null)
    {
        if ($this->_unredirectedUrls === null) {
            $unredirectedUrls = explode(
                self::OKAELI_COMINGSOON_EXPLODE_SEPARATOR,
                Mage::getStoreConfig(self::OKAELI_COMINGSOON_XML_PATH_UNREDIRECTED_URLS, $store)
            );
            $this->_unredirectedUrls = $unredirectedUrls;
        }

        return $this->_unredirectedUrls;
    }

    /**
     * Get debug log status
     * @param null $store
     * @return bool|mixed
     */
    public function isDebugLogEnabled($store = null)
    {
        if ($this->_isDebugLogEnabled === null) {
            $isDebugLogEnabled = Mage::getStoreConfig(self::OKAELI_COMINGSOON_XML_PATH_DEBUG, $store);
            $this->_isDebugLogEnabled = ($isDebugLogEnabled) ? $isDebugLogEnabled : false;
        }

        return $this->_isDebugLogEnabled;
    }

    /**
     * Write Log if debug log is enabled
     * @param $message
     * @param null $store
     */
    public function debugLog($message, $store = null)
    {
        if ($this->isDebugLogEnabled($store)) {
            Mage::log(print_r($message, true) . "\r\n", Zend_Log::DEBUG, 'okaeli_comingsoon_debug.log', true);
        }
    }
}
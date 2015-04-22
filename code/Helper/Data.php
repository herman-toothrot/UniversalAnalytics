<?php

class BlueAcorn_UniversalAnalytics_Helper_Data extends Mage_Core_Helper_Abstract
{
    const BAUA_SESSION_STOREDHTML_NAME = 'baua_session_data';
    protected $_translationArray = array();


    public function generateProductImpressions($name) {
        $monitor = Mage::getSingleton('baua/monitor');

        return $monitor->generateProductImpressions($name);
    }

    public function generatePromoImpressions() {
        $monitor = Mage::getSingleton('baua/monitor');

        return $monitor->generatePromoImpressions();
    }

    public function generateProductClickEvents($accounts) {
        $monitor = Mage::getSingleton('baua/monitor');

        return $monitor->generateProductClickEvents($accounts);
    }

    public function generatePromoClickEvents() {
        $monitor = Mage::getSingleton('baua/monitor');

        return $monitor->generatePromoClickEvents();
    }

    public function getAction($name) {
        $monitor = Mage::getSingleton('baua/monitor');

        return $monitor->getAction($name);
    }


    /**
     * Takes any number of arguments for translations and builds a
     * list of all attributes for the supplied translations
     *
     * @name getTranslationAttributes
     * @param string $translation
     * @return Array
     */
    public function getTranslationAttributes($translation) {
        $params = func_get_args();
        $attributeList = Array();

        foreach ($params as $translation) {
            $translationList = $this->getTranslation($translation);
            $translationList = $this->flattenArray($translationList);
            $attributeList = array_merge($attributeList, $translationList);
        }

        return array_unique($attributeList);
    }

    /**
     * Merges nested array elements into the parent array
     *
     * @name flattenArray
     * @param Array $inputArray
     * @return Array
     */
    public function flattenArray($inputArray) {
        $outputArray = Array();

        foreach ($inputArray as $element) {
            if (is_array($element)) {
                $outputArray = array_merge($outputArray, array_keys($element));
            } else {
                $outputArray[] = $element;
            }
        }

        return $outputArray;
    }

    public function getCollectionListName($collectionObject) {
        $listName = null;

        preg_match('/Resource_(.*)_Collection/', get_class($collectionObject), $listName);
        if (is_array($listName) && count($listName) >= 2) {
            $listName = str_replace('_', ' ', $listName[1]);
        }

        // This is a fallback/default listname for the cases where we
        // are unable to derive a useful listname from the class
        if ($listName == null || $listName == '') {
            $listName = 'default';
        }

        return $listName;
    }

    public function generateCurrencyInit($name) {
        $monitor = Mage::getSingleton('baua/monitor');

        return $monitor->generateCurrencyInit($name);
    }

    /**
     * Get translation values from Config Defaults
     * @param $part
     * @return mixed
     */
    public function getTranslation($part)
    {
        return Mage::getStoreConfig('baua/translation/'.$part);
    }

    public function getAllowedCurrencyCodes() {
        return Mage::getStoreConfig('baua/allowed_currency_codes');
    }

    /**
     * Sets session varable for rending on the frontend in the next pageview
     * @param $data
     */
    public function setSessionData($data)
    {
        /*
         * STORE $data IN SESSION
         * */
        Mage::getSingleton('core/session')->setData(self::BAUA_SESSION_STOREDHTML_NAME, $data);
    }

    public function isActive()
    {
        return (bool) Mage::getStoreConfig('google/baua/active');
    }

    /**
     * Returns the GA accounts
     * @return array
     */
    public function getAccounts()
    {
        $accounts = array();
        $trackingIDs = explode(',', Mage::getStoreConfig("google/analytics/account"));
        $i = 0;
        foreach ($trackingIDs as $trackingID) {
            $name = 'trackingID'.$i;
            $accounts[] = array(
                'name' => $name,
                'trackingID' => trim($trackingID)
            );
            $i++;
        }

        return $accounts;
    }
}

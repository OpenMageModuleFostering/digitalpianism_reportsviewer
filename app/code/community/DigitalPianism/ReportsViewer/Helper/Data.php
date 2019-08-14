<?php

/**
 * Class DigitalPianism_ReportsViewer_Helper_Data
 */
class DigitalPianism_ReportsViewer_Helper_Data extends Mage_Core_Helper_Abstract
{
    // Protected log file name
    protected $_logFileName = 'digitalpianism_reportsviewer.log';

    /**
     * Log data to a custom file
     * @param string|object|array data to log
     */
    public function log($data)
    {
        Mage::log($data, null, $this->_logFileName);
    }

}
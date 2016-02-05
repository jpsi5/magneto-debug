<?php

/**
 * Class Sheep_Debug_Block_Logging
 *
 * @category Sheep
 * @package  Sheep_Subscription
 * @license  Copyright: Pirate Sheep, 2016, All Rights reserved.
 * @link     https://piratesheep.com
 */
class Sheep_Debug_Block_Logging extends Sheep_Debug_Block_Panel
{
    protected $logLineCount = null;

    public function getSubTitle()
    {
        $logging = $this->getLogging();
        $requestInfo = $this->getRequestInfo();

        return $this->__('%d logs, %d exceptions',
            $logging->getLineCount($this->helper->getLogFilename($requestInfo->getStoreId())),
            $logging->getLineCount($this->helper->getExceptionLogFilename($requestInfo->getStoreId()))
        );
    }


    public function isVisible()
    {
        return $this->helper->isPanelVisible('logging') && $this->getLogLineCount() > 0;
    }


    /**
     * @return Sheep_Debug_Model_Logging
     */
    public function getLogging()
    {
        return $this->getRequestInfo()->getLogging();
    }


    public function getLogFiles()
    {
        return $this->getLogging()->getFiles();
    }


    /**
     * Returns number of log lines added in all of registered logs
     * @return int
     */
    public function getLogLineCount()
    {
        if ($this->logLineCount === null) {
            $this->logLineCount = 0;
            foreach ($this->getLogFiles() as $log) {
                $this->logLineCount += $this->getLogging()->getLineCount($log);
            }
        }

        return $this->logLineCount;
    }


    /**
     * @param $logFile
     * @return string
     */
    public function getViewLogUrl($logFile)
    {
        // TODO: add request info id to use log file ranges on from that record
        // end log position is saved after layout is rendered

        $range = $this->getLogging()->getRange($logFile);

        return Mage::helper('sheep_debug/url')->getViewLogUrl($logFile, $range['start']);
    }

}

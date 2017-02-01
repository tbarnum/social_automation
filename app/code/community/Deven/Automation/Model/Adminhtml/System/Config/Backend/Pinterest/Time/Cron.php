<?php
/**
 * Created by PhpStorm.
 * User: jimmyhien
 * Date: 10/28/2015
 * Time: 1:25 PM
 */

class Deven_Automation_Model_Adminhtml_System_Config_Backend_Pinterest_Time_Cron extends Mage_Core_Model_Config_Data
{
    const CRON_STRING_PATH = 'crontab/jobs/autopin/schedule/cron_expr';

    protected function _afterSave()
    {
        $time = $this->getData('groups/pinterest/fields/every_time/value');

        $isPinFrequency = $this->getData('groups/pinterest/fields/pin_at_time_frequency/value');

        if(!$isPinFrequency) {

            try {
                Mage::getModel('core/config_data')
                    ->load(self::CRON_STRING_PATH, 'path')
                    ->setValue($time)
                    ->setPath(self::CRON_STRING_PATH)
                    ->save();
            }
            catch (Exception $e) {
                throw new Exception(Mage::helper('cron')->__('Unable to save the cron expression.'));

            }
        }
    }
}
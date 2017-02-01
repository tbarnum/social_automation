<?php
/**
 * Created by PhpStorm.
 * User: jimmyhien
 * Date: 10/28/2015
 * Time: 11:40 AM
 */

class Deven_Automation_Model_Adminhtml_System_Config_Backend_Twitter_Frequency_Cron extends Mage_Core_Model_Config_Data
{
    const CRON_STRING_PATH = 'crontab/jobs/autotweet/schedule/cron_expr';

    protected function _afterSave()
    {
        $time = $this->getData('groups/twitter/fields/tweet_start_time/value');
        $isPostFrequency = $this->getData('groups/twitter/fields/update_tweet_at_time_frequency/value');

        if($isPostFrequency) {
            $frequencyDaily = Mage_Adminhtml_Model_System_Config_Source_Cron_Frequency::CRON_DAILY;
            $frequencyWeekly = Mage_Adminhtml_Model_System_Config_Source_Cron_Frequency::CRON_WEEKLY;
            $frequencyMonthly = Mage_Adminhtml_Model_System_Config_Source_Cron_Frequency::CRON_MONTHLY;

            $cronDayOfWeek = date('N');

            $cronExprArray = array(
                intval($time[1]),                                   # Minute
                intval($time[0]),                                   # Hour
                (frequency == $frequencyMonthly) ? '1' : '*',       # Day of the Month
                '*',                                                # Month of the Year
                (frequency == $frequencyWeekly) ? '1' : '*',        # Day of the Week
            );
            $cronExprString = join(' ', $cronExprArray);

            try {
                Mage::getModel('core/config_data')
                    ->load(self::CRON_STRING_PATH, 'path')
                    ->setValue($cronExprString)
                    ->setPath(self::CRON_STRING_PATH)
                    ->save();
            }
            catch (Exception $e) {
                throw new Exception(Mage::helper('cron')->__('Unable to save the cron expression.'));

            }
        }
    }
}
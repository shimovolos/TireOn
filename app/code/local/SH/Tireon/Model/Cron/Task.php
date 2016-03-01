<?php

/**
 * Class SH_Tireon_Model_Cron_Task
 */
class SH_Tireon_Model_Cron_Task
{
    /**
     * Call cron job
     */
    public function call()
    {
        $csvClass = new SH_Tireon_Model_CSV();
        $files = array(
            SH_Tireon_Model_CSV::CSV_FILE_NAME_TYRES,
            SH_Tireon_Model_CSV::CSV_FILE_NAME_WHEELS,
//            SH_Tireon_Model_CSV::CSV_FILE_NAME_INDUSTRIAL_TYRES,
//            SH_Tireon_Model_CSV::CSV_FILE_NAME_TRUCK_TYRES,
//            SH_Tireon_Model_CSV::CSV_FILE_NAME_OTHER,
        );

        foreach($files as $file) {
            $csvClass->setEntities($file);
        }
    }
}
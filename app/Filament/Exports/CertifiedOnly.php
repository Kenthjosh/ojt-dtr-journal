<?php
use App\Models\WeeklyReports;
use App\Models\DailyTimeRecords;

class CertifiedOnly {
    public function isCertified($object) {
        return $object->where('status','certified');
    }
}
<?php

namespace Chewyou\SilverstripeAssetsS3Backup;

use SilverStripe\ORM\DataObject;
use Symbiote\QueuedJobs\Services\AbstractQueuedJob;
use Symbiote\QueuedJobs\Services\QueuedJob;

class RunS3Backup extends AbstractQueuedJob implements QueuedJob {

    public function getTitle() {
        return _t(
            "Assets S3 Backup Job",
            "Backup Assets to AWS S3 Backup"
        );
    }

    public function getJobType() {
        return QueuedJob::QUEUED;
    }

    public function process() {


        $this->addMessage("Completed. Backed up x assets");
        $this->reenqueue();
        $this->isComplete = true;
        return;
    }

    private function reenqueue() {
        $this->addMessage("Queueing the next Unpublish News Job.");
        $assetsS3BackupJob = new RunS3Backup();
        singleton('QueuedJobService')->queueJob($assetsS3BackupJob, date('Y-m-d H:i:s', time() + 86400));
    }
}

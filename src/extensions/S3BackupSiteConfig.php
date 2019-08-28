<?php

namespace Chewyou\SilverstripeAssetsS3Backup;

use SilverStripe\Forms\TextField;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataExtension;

class S3BackupSiteConfig extends DataExtension {

    private static $db = [
        's3Key' => 'Varchar(255)',
        's3Secret' => 'Varchar(255)',
        's3Region' => 'Varchar(255)',
    ];

    public function updateCMSFields(FieldList $fields) {
        $fields->addFieldsToTab('Root.S3AssetsBackupConfig', [
            TextField::create('s3Key', 'AWS S3 Key'),
            TextField::create('s3Secret', 'AWS S3 Secret'),
            TextField::create('s3Region', 'AWS Region')
        ]);
    }
}
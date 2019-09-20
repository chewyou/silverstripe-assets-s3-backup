<?php

namespace Chewyou\SilverstripeAssetsS3Backup;

use SilverStripe\Dev\BuildTask;
use Aws\S3;
use Aws\S3\S3Client;
use Aws\Credentials\Credentials;
use SilverStripe\Dev\Debug;
use SilverStripe\SiteConfig\SiteConfig;

class RunS3BackupTask extends BuildTask {
    private static $segment = 'RunS3BackupTask';

    protected $title = "S3 Backup";

    protected $dir = ASSETS_PATH;
    private $s3Key;
    private $s3Secret;
    private $s3Region;
    private $s3BucketName;

    public function run($request) {
        $this->s3Key = SiteConfig::current_site_config()->s3Key;
        $this->s3Secret = SiteConfig::current_site_config()->s3Secret;
        $this->s3Region = SiteConfig::current_site_config()->s3Region;
        $this->s3BucketName = SiteConfig::current_site_config()->s3BucketName;

        // For testing connection was correct
        $this->listBuckets();

        $files = $this->scanFiles($this->dir);
        foreach ($files as $file) {
            $extension = pathinfo(ASSETS_PATH . DIRECTORY_SEPARATOR . $file, PATHINFO_EXTENSION);
            if ($extension == 'txt') {
                $this->S3copy($file);
            }
        }
    }

    private function getS3Client(){
        $credentials = new Credentials($this->s3Key, $this->s3Secret);
        $s3Client = new S3Client([
            'version'     => 'latest',
            'region'      => $this->s3Region,
            'credentials' => $credentials
        ]);
        return $s3Client;
    }

    private function scanFiles($dir) {
        $ignored = ['.', '..', '.svn', '.htaccess'];
        $files = [];

        foreach (scandir($dir) as $file) {
            if (in_array($file, $ignored)) continue;
            $files[$file] = filemtime($dir . '/' . $file);
        }

        arsort($files);
        $files = array_keys($files);

        return ($files) ? $files : false;
    }

    private function listBuckets(){
        $s3Client = $this->getS3Client();
        $buckets = $s3Client->listBuckets();
        foreach ($buckets['Buckets'] as $bucket) {
            Debug::dump($bucket['Name']);
        }
    }

    private function S3copy($file) {
        $s3Client = $this->getS3Client();

        try {
            $result = $s3Client->putObject([
                'Bucket' => $this->s3BucketName,
                'Key' => basename($file),
                'SourceFile' => $file,
            ]);
        } catch (S3Exception $e) {
            echo $e->getMessage() . "\n";
        }

    }
}
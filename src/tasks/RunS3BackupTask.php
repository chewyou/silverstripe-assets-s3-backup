<?php

namespace Chewyou\SilverstripeAssetsS3Backup;

use SilverStripe\Dev\BuildTask;
use Aws\S3;
use Aws\S3\S3Client;
use Aws\Credentials\Credentials;

class RunS3BackupTask extends BuildTask {
    private static $segment = 'RunS3BackupTask';

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

        $files = $this->scanFiles($this->dir);
        foreach ($files as $file) {
            $extension = pathinfo(ASSETS_PATH . DIRECTORY_SEPARATOR . $file, PATHINFO_EXTENSION);
            if ($extension == 'gz') {
                $this->S3copy($file);
            }
        }
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

    private function S3copy($file) {
        $fileok = true;
        $credentials = new Credentials($this->s3Key, $this->s3Secret);

        $s3 = S3Client([
            'version'     => 'latest',
            'region'      => $this->s3Region,
            'credentials' => $credentials
        ]);

        $bucket = $s3->getBucket($this->s3BucketName);

        foreach ($bucket as $existing) {
            if ($existing['name'] === $file) {
                $fileok = false;
            }
        }

        if ($fileok) {
            $put = $s3->putObject($s3->inputFile(ASSETS_PATH . DIRECTORY_SEPARATOR . $file), $this->s3BucketName, $file, S3::ACL_PRIVATE);
            if ($put) {
                echo $file . " transferred to S3<br>" . "\r\n";
            } else {
                echo $file . " unable to be transferred to S3<br>" . "\r\n";
            }
        } else {
            echo $file . " already in S3<br>" . "\r\n";
        }
    }
}
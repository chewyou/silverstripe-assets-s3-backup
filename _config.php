<?php

use SilverStripe\Control\Director;

define('S3_BACKUP_DIR', ltrim(Director::makeRelative(realpath(__DIR__)), DIRECTORY_SEPARATOR));
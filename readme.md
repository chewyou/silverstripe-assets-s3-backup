# Silverstripe Assets S3 Backup

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/2a1bf0db203f497cb2e8742546a91713)](https://www.codacy.com/manual/benspickett/silverstripe-assets-s3-backup?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=chewyou/silverstripe-assets-s3-backup&amp;utm_campaign=Badge_Grade)

## Synopsis
A small task to backup all Uploads to a designated S3 Bucket

## Requirements 
* SilverStripe 4+
* PHP 7+

## Installation
### Composer
`composer require chewyou/silverstripe-assets-s3-backup`

_Note_  
You may need to add the repository to the `repositories` list in composer.json
and add the following manually

`"chewyou/silverstripe-assets-s3-backup": "dev-master"` 

```json
"repositories": 
    [
        {
            "type": "vcs",
            "url": "https://github.com/chewyou/silverstripe-assets-s3-backup.git"
        }
    ],
```

Then run `composer update` and `dev/build`

## Configuration
Set up a IAM user with S3BucketFullAccess permissions, and take note of the Secret and Key.  
Set up a S3 Bucket. Take note of the Bucket Name and Region.  

Use the noted variables to fill in the added fields in the CMS Settings.  
Under Settings > S3 Assets Backup Config

## Usage
Navigate to `/dev/tasks` and click on `Run S3 Backup Task`

## Future work
A button somewhere (either Settings or in Files) to run the task
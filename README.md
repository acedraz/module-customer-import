# CUSTOMER IMPORT - M2 #

Magento 2 module for customer import using file and profile

### Instalation ###

* Composer

Add in 'repositories' of composer.json (magento 2 project):

     "repositories": {
        "acedraz-customer-import": {
            "url": "https://github.com/acedraz/module-customer-import.git",
            "type": "git"
        }
     }

Make a require:

    composer require acedraz/module-customer-import:^1.0

* Manually

  Copy files to root/app/code/ACedraz/CustomerImport/

### IMPORTANT ###

Run this command in magento cli terminal (if necessary)

    php bin/magento module:enable ACedraz_CustomerImport
    php bin/magento setup:upgrade

### CONFIGURATIONS ###

#### PROFILES MAP ####

Profiles can be added, deleted or edited in magento admin painel:

SYSTEM -> DATA TRANSFER -> PROFILE MAP

The module will have two pre-configured profiles

* sample-csv
* sample-json

#### SYSTEM CONFIG ####

In magento admin painel:

STORES -> SETTINGS -> CONFIGURATIONS -> ACEDRAZ EXTENSIONS (will be the last tab) -> CUSTOMER IMPORT

You can send the csv or json file to the server and choose the behavior for each type code imported

#### IMPORTANT ####

The upload file of the data to be migrated can be uploaded by the magento admin (see CONFIGURATIONS -> SYSTEM CONFIG in this document). Manually the same should be update inside pub/media/customerimport

### HOW TO TESTE ###

After upload file to the server run this command in root project

    bin/magento customer:import <profile-name> <source>

Imported customers will be available in magento admin painel: CUSTOMERS -> ALL CUSTOMERS

### Upcoming updates ###
* Validation content type
* Update behavior in magento/module-import-export

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


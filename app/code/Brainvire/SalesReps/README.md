# Mage2 Module Brainvire SalesReps

    ``brainvire/module-salesreps``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Specifications](#markdown-header-specifications)
 - [Attributes](#markdown-header-attributes)


## Main Functionalities
Sales Representative Module

## Installation
\* = in production please use the `--keep-generated` option

### Type 1: Zip file

 - Unzip the zip file in `app/code/Brainvire`
 - Enable the module by running `php bin/magento module:enable Brainvire_SalesReps`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

### Type 2: Composer

 - Make the module available in a composer repository for example:
    - private repository `repo.magento.com`
    - public repository `packagist.org`
    - public github repository as vcs
 - Add the composer repository to the configuration by running `composer config repositories.repo.magento.com composer https://repo.magento.com/`
 - Install the module composer by running `composer require brainvire/module-salesreps`
 - enable the module by running `php bin/magento module:enable Brainvire_SalesReps`
 - apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`


## Configuration




## Specifications

 - Model
	- SalesReps

 - API Endpoint
	- GET - Brainvire\SalesReps\Api\GetSalesRepsManagementInterface > Brainvire\SalesReps\Model\GetSalesRepsManagement

 - API Endpoint
	- POST - Brainvire\SalesReps\Api\AddSalesRepsManagementInterface > Brainvire\SalesReps\Model\AddSalesRepsManagement

 - API Endpoint
	- DELETE - Brainvire\SalesReps\Api\DeleteSalesPersManagementInterface > Brainvire\SalesReps\Model\DeleteSalesPersManagement


## Attributes




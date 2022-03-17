<?php
/**
 * ACedraz
 *
 * @category  ACedraz
 * @package   CustomerImport
 * @version   1.0.1
 * @author    Aislan Cedraz <aislan.cedraz@gmail.com.br>
 */

declare(strict_types=1);

namespace ACedraz\CustomerImport\Api;

/**
 * Interface CustomerImportInterface
 * @package ACedraz\CustomerImport\Api
 */
interface CustomerImportInterface
{
    /** @var string */
    const NEED_UPDATE = 'NEED UPDATE';

    /** @var string */
    const DIR = 'customerimport';

    /** @var string */
    const DEFAULT_GROUP_ID = 1;

    /** @var string */
    const DEFAULT_ADDRESS_COUNTRY_ID = 'US';

    /** @var int */
    const DEFUALT_DISABLE_AUTO_GROUP_CHANGE = 0;

    /**
     * @return \ACedraz\CustomerImport\Api\Data\ProfileInterface[]
     */
    public function getAllProfiles(): array;

    /**
     * @param string $name
     * @return bool
     */
    public function validateProfile(string $name): bool;

    /**
     * @param string $name
     * @return bool
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function validateFile(string $name): bool;

    /**
     * @param string $name
     * @return string
     */
    public function getFilePath(string $name): string;

    /**
     * @param string $profileName
     * @param string $fileName
     * @return void
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function import(string $profileName, string $fileName);

    /**
     * @return void
     */
    public function reindex();
}

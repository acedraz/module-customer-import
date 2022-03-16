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
    const JSON_TYPE = 'json';

    /** @var string */
    const CSV_TYPE = 'csv';

    /** @var string */
    const DIR = 'customerimport';

    /** @var string */
    const DEFAULT_GROUP_ID = 1;

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
     * @param string $name
     * @return array|string|string[]
     */
    public function getFileType(string $name);

    public function getFileData(string $profileName, string $fileName);
}

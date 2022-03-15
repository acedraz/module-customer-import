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
}

<?php
/**
 * ACedraz
 *
 * @category  ACedraz
 * @package   CustomerImport
 * @version   1.0.6
 * @author    Aislan Cedraz <aislan.cedraz@gmail.com.br>
 */

declare(strict_types=1);

namespace ACedraz\CustomerImport\Api;

/**
 * Interface JsonCsvConverterInterface
 * @package ACedraz\CustomerImport\Api
 */
interface JsonCsvConverterInterface
{
    /** @var string */
    const JSON_TYPE = 'json';

    /** @var string */
    const CSV_TYPE = 'csv';

    /**
     * @param string $filePath
     * @return array|string|string[]
     */
    public function getFileType(string $filePath);

    /**
     * @param string $filePath
     * @return array|bool|float|int|mixed|string|null
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function getFileContent(string $filePath);

    /**
     * @param string $filePath
     * @return array|bool|float|int|mixed|string|null
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function convert(string $filePath);

    /**
     * @param string $content
     * @return array
     */
    public function jsonToCsv(string $content);

    /**
     * @param array $content
     * @return bool|string
     */
    public function csvToJson(array $content);

    /**
     * @param string $filePath
     * @return array|bool|float|int|mixed|string|null
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function convertToJson(string $filePath);

    /**
     * @param string $filePath
     * @return array|bool|float|int|mixed|string|null
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function convertToCsv(string $filePath);

    /**
     * @param $content
     * @param string $type
     * @return array|void
     */
    public function getHeader($content, string $type = self::JSON_TYPE);
}

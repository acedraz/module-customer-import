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

namespace ACedraz\CustomerImport\Model;

use ACedraz\CustomerImport\Api\JsonCsvConverterInterface;
use Magento\Framework\File\Csv;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Class JsonCsvConverter
 * @package ACedraz\CustomerImport\Model
 */
class JsonCsvConverter implements JsonCsvConverterInterface
{
    /** @var Json */
    private Json $_json;

    /** @var Csv */
    private Csv $_csv;

    /** @var File */
    private File $filesystem;

    /**
     * @param Json $j_son
     * @param Csv $_csv
     * @param File $filesystem
     */
    public function __construct(
        Json $j_son,
        Csv $_csv,
        File $filesystem
    ) {
        $this->_json = $j_son;
        $this->_csv = $_csv;
        $this->filesystem = $filesystem;
    }

    /**
     * @param string $filePath
     * @return array|string|string[]
     */
    public function getFileType(string $filePath)
    {
        return pathinfo($filePath)['extension'];
    }

    /**
     * @param string $filePath
     * @return array|bool|float|int|mixed|string|null
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function getFileContent(string $filePath)
    {
        $content = null;
        switch ($this->getFileType($filePath)) {
            case self::JSON_TYPE;
                $content = $this->_json->unserialize($this->filesystem->fileGetContents($filePath));
                break;
            case self::CSV_TYPE;;
                $content = $data = $this->_csv->getData($filePath);;
                break;
            default;
                $this->filesystem->fileGetContents($filePath);
                break;
        }
        return $content;
    }

    /**
     * @param string $filePath
     * @return array|bool|float|int|mixed|string|null
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function convert(string $filePath)
    {
        $content = $this->getFileContent($filePath);
        switch ($this->getFileType($filePath)) {
            case self::JSON_TYPE;
                return $this->jsonToCsv($content);
            case self::CSV_TYPE;
                return $this->csvToJson($content);
            default;
                return $content;
        }
    }

    /**
     * @param string $filePath
     * @return array|bool|float|int|mixed|string|null
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function convertToJson(string $filePath)
    {
        $content = $this->getFileContent($filePath);
        switch ($this->getFileType($filePath)) {
            case self::CSV_TYPE;
                return $this->csvToJson($content);
            default;
                return $content;
        }
    }

    /**
     * @param string $filePath
     * @return array|bool|float|int|mixed|string|null
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function convertToCsv(string $filePath)
    {
        $content = $this->getFileContent($filePath);
        switch ($this->getFileType($filePath)) {
            case self::JSON_TYPE;
                return $this->jsonToCsv($content);
            default;
                return $content;
        }
    }

    /**
     * @param string $content
     * @return array
     */
    public function jsonToCsv(string $content)
    {
        $csv = [];
        $content = $this->_json->unserialize($content);
        foreach ($content as $row => $data) {
            $header = [];
            foreach ($data as $key => $value) {
                $header[] = $key;
            }
            $csv[] = $header;
            break;
        }
        foreach ($content as $data) {
            $row = [];
            foreach ($data as $value) {
                $row[] = $value;
            }
            $csv[] = $row;
        }
        return $csv;
    }

    /**
     * @param $content
     * @param string $type
     * @return array|void
     */
    public function getHeader($content, string $type = self::JSON_TYPE)
    {
        switch ($type) {
            case JsonCsvConverterInterface::JSON_TYPE;
                foreach ($content as $row => $data) {
                    $header = [];
                    foreach ($data as $key => $value) {
                        $header[] = $key;
                    }
                    return $header;
                }
            case JsonCsvConverterInterface::CSV_TYPE;
                foreach ($content as $row => $data) {
                    $header = [];
                    foreach ($data as $value) {
                        $header[] = $value;
                    }
                    return $header;
                }
        }
    }

    /**
     * @param array $content
     * @return bool|string
     */
    public function csvToJson(array $content)
    {
        $json = [];
        $entries = [];
        foreach ($content as $row => $data) {
            if (!$row) {
                foreach ($data as $value) {
                    $entries[] = $value;
                }
            }
            if ($row) {
                foreach ($data as $column => $value) {
                    $json[$row][$entries[$column]] = $value;
                }
            }
        }
        return $this->_json->serialize($json);
    }
}

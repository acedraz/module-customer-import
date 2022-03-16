<?php
/**
 * ACedraz
 *
 * @category  ACedraz
 * @package   CustomerImport
 * @version   1.0.5
 * @author    Aislan Cedraz <aislan.cedraz@gmail.com.br>
 */

declare(strict_types=1);

namespace ACedraz\CustomerImport\Model;

use ACedraz\CustomerImport\Api\CustomerImportInterface;
use ACedraz\CustomerImport\Api\Data\ProfileInterface;
use ACedraz\CustomerImport\Api\Data\ProfileMapInterface;
use ACedraz\CustomerImport\Api\ProfileMapRepositoryInterface;
use ACedraz\CustomerImport\Api\ProfileRepositoryInterface;
use ACedraz\CustomerImport\Model\Config\Source\CustomerImportColumns;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\File\Csv;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Driver\File;
use Magento\ImportExport\Model\Import\Source\CsvFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class CustomerImport
 * @package ACedraz\CustomerImport\Model
 */
class CustomerImport implements CustomerImportInterface
{
    /** @var SearchCriteriaBuilder */
    private SearchCriteriaBuilder $_searchCriteriaBuilder;

    /** @var ProfileRepositoryInterface */
    private ProfileRepositoryInterface $profileRepository;

    /** @var Filesystem */
    private Filesystem $_filesystem;

    /** @var File */
    private File $fileDriver;

    /** @var CsvFactory */
    private CsvFactory $_sourceCsvFactory;

    /** @var Csv */
    private Csv $_csv;

    /** @var ProfileMapRepositoryInterface */
    private ProfileMapRepositoryInterface $profileMapRepository;

    /** @var StoreManagerInterface */
    private StoreManagerInterface $_storeManager;

    /**
     * @param StoreManagerInterface $_storeManager
     * @param Csv $_csv
     * @param CsvFactory $sourceCsvFactory
     * @param SearchCriteriaBuilder $_searchCriteriaBuilder
     * @param ProfileRepositoryInterface $profileRepository
     * @param ProfileMapRepositoryInterface $profileMapRepository
     * @param Filesystem $_filesystem
     * @param File $fileDriver
     */
    public function __construct(
        StoreManagerInterface $_storeManager,
        Csv $_csv,
        CsvFactory $sourceCsvFactory,
        SearchCriteriaBuilder $_searchCriteriaBuilder,
        ProfileRepositoryInterface $profileRepository,
        ProfileMapRepositoryInterface $profileMapRepository,
        Filesystem $_filesystem,
        File $fileDriver
    ) {
        $this->_searchCriteriaBuilder = $_searchCriteriaBuilder;
        $this->profileRepository = $profileRepository;
        $this->_filesystem = $_filesystem;
        $this->fileDriver = $fileDriver;
        $this->_sourceCsvFactory = $sourceCsvFactory;
        $this->_csv = $_csv;
        $this->profileMapRepository = $profileMapRepository;
        $this->_storeManager = $_storeManager;
    }

    /**
     * @return \ACedraz\CustomerImport\Api\Data\ProfileInterface[]
     */
    public function getAllProfiles(): array
    {
        return $this->profileRepository->getList(
            $this->_searchCriteriaBuilder->addFilter(ProfileInterface::ENABLE, 1, 'eq')
                ->create())
            ->getItems();
    }

    /**
     * @param string $name
     * @return bool
     */
    public function validateProfile(string $name): bool
    {
        $profiles = $this->profileRepository
            ->getList($this->_searchCriteriaBuilder
                ->addFilter(ProfileInterface::NAME, $name, 'eq')
                ->setCurrentPage(1)
                ->create()
            )->getItems();
        if ($profiles) {
            return true;
        }
        return false;
    }

    /**
     * @param string $name
     * @return bool
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function validateFile(string $name): bool
    {
        if ($this->fileDriver->isExists($this->getFilePath($name))) {
            return true;
        }
        return false;
    }

    /**
     * @param string $name
     * @return string
     */
    public function getFilePath(string $name): string
    {
        return $this->getDirectoryFiles() . $name;
    }

    /**
     * @return string
     */
    private function getDirectoryFiles(): string
    {
        return $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath() . self::DIR . DIRECTORY_SEPARATOR;
    }

    /**
     * @param string $name
     * @return array|string|string[]
     */
    public function getFileType(string $name)
    {
        return pathinfo($this->getFilePath($name))['extension'];
    }

    public function import(string $profileName, string $fileName)
    {

    }

    public function getFileData(string $profileName, string $fileName)
    {
        $type = $this->getFileType($fileName);
        switch ($type) {
            case self::JSON_TYPE;
                break;
            case self::CSV_TYPE;
                $this->createTemporaryCsv($profileName, $fileName);
                $sourceCsv = $this->createSourceCsvModel($fileName);
                break;
            default;
                break;
        }
    }

    /**
     * Create source CSV model
     *
     * @param string $sourceFile
     * @return \Magento\ImportExport\Model\Import\Source\Csv
     */
    protected function createSourceCsvModel(string $sourceFile)
    {
        $sourceFile = self::DIR . DIRECTORY_SEPARATOR . $sourceFile;
        return $this->_sourceCsvFactory->create(
            [
                'file' => $sourceFile,
                'directory' => $this->_filesystem->getDirectoryWrite(DirectoryList::MEDIA)
            ]
        );
    }

    /**
     * @param string $profileName
     * @param string $fileName
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException|\Magento\Framework\Exception\LocalizedException
     */
    private function createTemporaryCsv(string $profileName, string $fileName): string
    {
        $data = $this->_csv->getData($this->getFilePath($fileName));
        $data = $this->updateCsvHeader($data, $profileName);
        $data = $this->checkAttribute($data, CustomerImportColumns::WEBSITE_VALUE, $this->_storeManager->getWebsite()->getCode());
        $data = $this->checkAttribute($data, CustomerImportColumns::WEBSITE_ID_VALUE, $this->_storeManager->getWebsite()->getId());
        $data = $this->checkAttribute($data, CustomerImportColumns::STORE_VALUE, $this->_storeManager->getStore()->getCode());
        $data = $this->checkAttribute($data, CustomerImportColumns::STORE_ID_VALUE, $this->_storeManager->getStore()->getId());
        $data = $this->checkAttribute($data, CustomerImportColumns::GROUP_ID_VALUE, self::DEFAULT_GROUP_ID);
        $data = $this->checkAttribute($data, CustomerImportColumns::ADDRESS_CITY_VALUE, '');
        $data = $this->checkAttribute($data, CustomerImportColumns::ADDRESS_COMPANY_VALUE, '');
        $data = $this->checkAttribute($data, CustomerImportColumns::ADDRESS_COUNTRY_ID_VALUE, '');
        $data = $this->checkAttribute($data, CustomerImportColumns::ADDRESS_STREET_VALUE, '');
        $data = $this->checkAttribute($data, CustomerImportColumns::ADDRESS_TELEPHONE_VALUE, '');
        $directory = $this->_filesystem->getDirectoryWrite(
            DirectoryList::TMP
        );
        $tmpFilePath = $directory->getAbsolutePath() . $fileName;
        $this->_csv->saveData($tmpFilePath, $data);
        return $tmpFilePath;
    }

    /**
     * @param array $data
     * @param string $profileName
     * @return array
     */
    private function updateCsvHeader(array $data, string $profileName): array
    {
        foreach ($data as $row => &$datum) {
            foreach ($datum as &$value) {
                $value = $this->getAttribute($value, $profileName);
            }
            break;
        }
        return $data;
    }

    /**
     * @param array $data
     * @param string $attribute
     * @param $default
     * @return array
     */
    private function checkAttribute(array $data, string $attribute, $default): array
    {
        foreach ($data as $row => &$datum) {
            if (!$row && in_array($attribute, $datum)) {
                break;
            }
            if (!$row) {
                $datum[] = $attribute;
            }
            if ($row) {
                $datum[] = $default;
            }
        }
        return $data;
    }

    /**
     * @param string $column
     * @param string $profileName
     * @return string
     */
    public function getAttribute(string $column, string $profileName): string
    {
        $profile = $this->profileRepository->getList(
            $this->_searchCriteriaBuilder
                ->addFilter(ProfileInterface::ENABLE, 1, 'eq')
                ->addFilter(ProfileInterface::NAME, $profileName, 'eq')
                ->create())
            ->getItems();
        $profileId = current($profile)->getEntityId();
        $profileMap = $this->profileMapRepository->getList(
                $this->_searchCriteriaBuilder
                    ->addFilter(ProfileMapInterface::COLUMN, $column, 'eq')
                    ->addFilter(ProfileMapInterface::PROFILE_ID, $profileId, 'eq')
                    ->create())
                ->getItems();
        return current($profileMap)->getAttribute();
    }
}

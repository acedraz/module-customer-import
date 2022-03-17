<?php
/**
 * ACedraz
 *
 * @category  ACedraz
 * @package   CustomerImport
 * @version   1.0.8
 * @author    Aislan Cedraz <aislan.cedraz@gmail.com.br>
 */

declare(strict_types=1);

namespace ACedraz\CustomerImport\Model;

use ACedraz\CustomerImport\Api\CustomerImportInterface;
use ACedraz\CustomerImport\Api\Data\ProfileInterface;
use ACedraz\CustomerImport\Api\Data\ProfileMapInterface;
use ACedraz\CustomerImport\Api\IndexerInterface;
use ACedraz\CustomerImport\Api\JsonCsvConverterInterface;
use ACedraz\CustomerImport\Api\ProfileMapRepositoryInterface;
use ACedraz\CustomerImport\Api\ProfileRepositoryInterface;
use ACedraz\CustomerImport\Api\SystemInterface;
use ACedraz\CustomerImport\Model\Config\Source\CustomerImportColumns;
use Magento\CustomerImportExport\Model\Import\Address;
use Magento\CustomerImportExport\Model\Import\Customer;
use Magento\CustomerImportExport\Model\Import\CustomerComposite;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\File\Csv;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\ImportExport\Model\Import\Source\CsvFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class CustomerImport
 * @package ACedraz\CustomerImport\Model
 */
class CustomerImport implements CustomerImportInterface
{
    /** @var null|string  */
    private $entityTypeCode = null;

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

    /** @var JsonCsvConverterInterface */
    private JsonCsvConverterInterface $jsonCsvConverter;

    /** @var Json */
    private Json $_json;

    /** @var CustomerImportColumns */
    private CustomerImportColumns $customerImportColumns;

    /** @var Customer */
    private Customer $_customerImport;

    /** @var Address */
    private Address $_addressImport;

    /** @var CustomerComposite */
    private CustomerComposite $_customerCompositeImport;

    /** @var SystemInterface */
    private SystemInterface $system;

    /** @var IndexerInterface */
    private IndexerInterface $indexer;

    /**
     * @param IndexerInterface $indexer
     * @param SystemInterface $system
     * @param Customer $_customerImport
     * @param Address $_addressImport
     * @param CustomerComposite $_customerCompositeImport
     * @param CustomerImportColumns $customerImportColumns
     * @param JsonCsvConverterInterface $jsonCsvConverter
     * @param StoreManagerInterface $_storeManager
     * @param Csv $_csv
     * @param Json $_json
     * @param CsvFactory $sourceCsvFactory
     * @param SearchCriteriaBuilder $_searchCriteriaBuilder
     * @param ProfileRepositoryInterface $profileRepository
     * @param ProfileMapRepositoryInterface $profileMapRepository
     * @param Filesystem $_filesystem
     * @param File $fileDriver
     */
    public function __construct(
        IndexerInterface $indexer,
        SystemInterface $system,
        Customer $_customerImport,
        Address $_addressImport,
        CustomerComposite $_customerCompositeImport,
        CustomerImportColumns $customerImportColumns,
        JsonCsvConverterInterface $jsonCsvConverter,
        StoreManagerInterface $_storeManager,
        Csv $_csv,
        Json $_json,
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
        $this->jsonCsvConverter = $jsonCsvConverter;
        $this->_json = $_json;
        $this->customerImportColumns = $customerImportColumns;
        $this->_customerImport = $_customerImport;
        $this->_addressImport = $_addressImport;
        $this->_customerCompositeImport = $_customerCompositeImport;
        $this->system = $system;
        $this->indexer = $indexer;
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
     * @param string $profileName
     * @param string $fileName
     * @return void
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function import(string $profileName, string $fileName)
    {
        $filePath = $this->createTemporaryCsv($profileName, $fileName);
        $sourceCsv = $this->createSourceCsvModel($filePath);
        $import = $this->getModelImport();
        $import->setSource($sourceCsv);
        $import->importData();
        $this->deleteFile($filePath);
    }

    /**
     * @param string $filePath
     * @return void
     */
    public function deleteFile(string $filePath)
    {
        unlink($filePath);
    }

    /**
     * @return void
     */
    public function reindex()
    {
        $this->indexer->reindex('customer_grid');
    }

    /**
     * @return array
     */
    public function createDataImport(): array
    {
        return [
            'entity' => $this->entityTypeCode,
            'behavior' => $this->getBehavior(),
            'validation-stop-on-errors' => 'validation-stop-on-errors',
            'allowed_error_count' => '10',
            '_import_field_separator' => ',',
            '_import_multiple_value_separator' => ',',
            '_import_empty_attribute_value_constant' =>  '__EMPTY__VALUE__',
            'import_images_file_dir' => ''
        ];
    }

    /**
     * @return string
     */
    public function getBehavior(): string
    {
        switch ($this->entityTypeCode) {
            case $this->_customerImport->getEntityTypeCode();
                return $this->system->getCustomerBehavior();
            case $this->_addressImport->getEntityTypeCode();
                return $this->system->getAddressBehavior();
            case $this->_customerCompositeImport->getEntityTypeCode();
                return $this->system->getCompositeBehavior();
            default;
                return '';
        }
    }

    /**
     * @return Address|Customer|CustomerComposite|null
     */
    public function getModelImport()
    {
        switch ($this->entityTypeCode) {
            case $this->_customerImport->getEntityTypeCode();
                return $this->_customerImport;
            case $this->_addressImport->getEntityTypeCode();
                return $this->_addressImport;
            case $this->_customerCompositeImport->getEntityTypeCode();
                return $this->_customerCompositeImport;
            default;
                return null;
        }
    }

    /**
     * Create source CSV model
     *
     * @param string $sourceFile
     * @param string|null $directory
     * @return \Magento\ImportExport\Model\Import\Source\Csv
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    protected function createSourceCsvModel(string $sourceFile, string $directory = null): \Magento\ImportExport\Model\Import\Source\Csv
    {
        if (!$directory) {
            $directory = $this->_filesystem->getDirectoryWrite(DirectoryList::TMP);
        }
        return $this->_sourceCsvFactory->create(
            [
                'file' => $sourceFile,
                'directory' => $directory
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
        $jsonTmp = [];
        $data = $this->_json->unserialize($this->jsonCsvConverter->convertToJson($this->getFilePath($fileName)));
        foreach ($data as $row => $values) {
            foreach ($this->getOptionsByTypeCode($this->getEntityTypeCode($data, $profileName)) as $attribute => $label) {
                $column = $this->getColumnByAttribute($attribute, $profileName);
                if ($column) {
                    $jsonTmp[$row][$attribute] = array_key_exists($column, $values) ? $values[$column] : $this->checkAttribute($attribute);
                    continue;
                }
                $jsonTmp[$row][$attribute] = $this->checkAttribute($attribute);
            }
        }
        $csvTmpData = $this->jsonCsvConverter->jsonToCsv($this->_json->serialize($jsonTmp));
        $directory = $this->_filesystem->getDirectoryWrite(
            DirectoryList::TMP
        );
        $tmpFilePath = $directory->getAbsolutePath() . $fileName;
        $this->_csv->saveData($tmpFilePath, $csvTmpData);
        return $tmpFilePath;
    }

    /**
     * @param string $attribute
     * @return int|string
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function checkAttribute(string $attribute)
    {
        switch ($attribute) {
            case CustomerImportColumns::WEBSITE_VALUE;
                return $this->_storeManager->getWebsite()->getCode();
            case CustomerImportColumns::WEBSITE_ID_VALUE;
                return $this->_storeManager->getWebsite()->getId();
            case CustomerImportColumns::STORE_VALUE;
                return $this->_storeManager->getStore()->getCode();
            case CustomerImportColumns::STORE_ID_VALUE;
                return $this->_storeManager->getStore()->getId();
            case CustomerImportColumns::GROUP_ID_VALUE;
                return self::DEFAULT_GROUP_ID;
            case CustomerImportColumns::ADDRESS_COUNTRY_ID_VALUE:
                return self::DEFAULT_ADDRESS_COUNTRY_ID;
            case CustomerImportColumns::CREATED_IN_VALUE:
                return $this->_storeManager->getDefaultStoreView()->getName();
            case CustomerImportColumns::DISABLE_AUTO_GROUP_CHANGE_VALUE:
                return self::DEFUALT_DISABLE_AUTO_GROUP_CHANGE;
            case CustomerImportColumns::FIRSTNAME_VALUE:
            case CustomerImportColumns::LASTNAME_VALUE:
            case CustomerImportColumns::ADDRESS_STREET_VALUE:
            case CustomerImportColumns::ADDRESS_TELEPHONE_VALUE:
            case CustomerImportColumns::ADDRESS_CITY_VALUE;
                return self::NEED_UPDATE;
            default;
                return '';
        }
    }

    /**
     * @param string $column
     * @param string $profileName
     * @return string|null
     */
    public function getAttributeByColumn(string $column, string $profileName): ?string
    {
        $profile = $this->profileRepository->getList(
            $this->_searchCriteriaBuilder
                ->addFilter(ProfileInterface::ENABLE, 1, 'eq')
                ->addFilter(ProfileInterface::NAME, $profileName, 'eq')
                ->create())
            ->getItems();
        if ($profile) {
            $profileId = current($profile)->getEntityId();
            $profileMap = $this->profileMapRepository->getList(
                $this->_searchCriteriaBuilder
                    ->addFilter(ProfileMapInterface::COLUMN, $column, 'eq')
                    ->addFilter(ProfileMapInterface::PROFILE_ID, $profileId, 'eq')
                    ->create())
                ->getItems();
            if ($profileMap) {
                return current($profileMap)->getAttribute();
            }
        }
        return null;
    }

    /**
     * @param array $jsonContent
     * @param string $profileName
     * @return string|null
     */
    public function getEntityTypeCode(array $jsonContent, string $profileName): ?string
    {
        $typeCode = null;
        $header = $this->jsonCsvConverter->getHeader($jsonContent);
        foreach ($header as &$column) {
            $column = $this->getAttributeByColumn($column, $profileName);
        }
        $customer = false;
        $address = false;
        foreach ($header as $attribute) {
            if (in_array($attribute,array_keys($this->customerImportColumns->getCustomerOptions()))) {
                $customer = true;
            }
            if (in_array($header,array_keys($this->customerImportColumns->getAddressOptions()))) {
                $address = true;
            }
        }
        if ($customer) {
            $typeCode = $this->_customerImport->getEntityTypeCode();
        }
        if ($address) {
            $typeCode = $this->_addressImport->getEntityTypeCode();
        }
        if ($address && $customer) {
            $typeCode = $this->_customerCompositeImport->getEntityTypeCode();
        }
        $this->entityTypeCode = $typeCode;
        return $typeCode;
    }

    /**
     * @param string $typeCode
     * @return array
     */
    public function getOptionsByTypeCode(string $typeCode): array
    {
        switch ($typeCode) {
            case $this->_customerImport->getEntityTypeCode();
                return $this->customerImportColumns->getCustomerOptions();
            case $this->_addressImport->getEntityTypeCode();
                return $this->customerImportColumns->getAddressOptions();
            case $this->_customerCompositeImport->getEntityTypeCode();
                return $this->customerImportColumns->getCustomerComposite();
            default;
                return [];
        }
    }

    /**
     * @param string $attribute
     * @param string $profileName
     * @return string|null
     */
    public function getColumnByAttribute(string $attribute, string $profileName): ?string
    {
        $profile = $this->profileRepository->getList(
            $this->_searchCriteriaBuilder
                ->addFilter(ProfileInterface::ENABLE, 1, 'eq')
                ->addFilter(ProfileInterface::NAME, $profileName, 'eq')
                ->create())
            ->getItems();
        if ($profile) {
            $profileId = current($profile)->getEntityId();
            $profileMap = $this->profileMapRepository->getList(
                $this->_searchCriteriaBuilder
                    ->addFilter(ProfileMapInterface::ATTRIBUTE, $attribute, 'eq')
                    ->addFilter(ProfileMapInterface::PROFILE_ID, $profileId, 'eq')
                    ->create())
                ->getItems();
            if ($profileMap) {
                return current($profileMap)->getColumn();
            }
        }
        return null;
    }
}

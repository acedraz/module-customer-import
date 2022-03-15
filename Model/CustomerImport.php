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
use ACedraz\CustomerImport\Api\ProfileRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Driver\File;

/**
 * Class CustomerImport
 * @package ACedraz\CustomerImport\Model
 */
class CustomerImport implements CustomerImportInterface
{
    /** @var string */
    const DIR = 'customerimport';

    /** @var SearchCriteriaBuilder */
    private SearchCriteriaBuilder $_searchCriteriaBuilder;

    /** @var ProfileRepositoryInterface */
    private ProfileRepositoryInterface $profileRepository;

    /** @var Filesystem */
    private Filesystem $_filesystem;

    /** @var File */
    private File $fileDriver;

    /**
     * @param SearchCriteriaBuilder $_searchCriteriaBuilder
     * @param ProfileRepositoryInterface $profileRepository
     * @param Filesystem $_filesystem
     * @param File $fileDriver
     */
    public function __construct(
        SearchCriteriaBuilder $_searchCriteriaBuilder,
        ProfileRepositoryInterface $profileRepository,
        Filesystem $_filesystem,
        File $fileDriver
    ) {
        $this->_searchCriteriaBuilder = $_searchCriteriaBuilder;
        $this->profileRepository = $profileRepository;
        $this->_filesystem = $_filesystem;
        $this->fileDriver = $fileDriver;
    }

    /**
     * @return \ACedraz\CustomerImport\Api\Data\ProfileInterface[]
     */
    public function getAllProfiles(): array
    {
        return $this->profileRepository->getList($this->_searchCriteriaBuilder->create())->getItems();
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
        return $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath() . DIRECTORY_SEPARATOR . self::DIR . DIRECTORY_SEPARATOR;
    }
}

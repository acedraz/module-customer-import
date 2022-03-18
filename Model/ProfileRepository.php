<?php
/**
 * ACedraz
 *
 * @category  ACedraz
 * @package   CustomerImport
 * @version   1.0.9
 * @author    Aislan Cedraz <aislan.cedraz@gmail.com.br>
 */

declare(strict_types=1);

namespace ACedraz\CustomerImport\Model;

use ACedraz\CustomerImport\Api\Data\ProfileMapInterfaceFactory;
use ACedraz\CustomerImport\Api\Data\ProfileMapInterface;
use ACedraz\CustomerImport\Api\ProfileMapRepositoryInterface;
use ACedraz\CustomerImport\Api\ProfileRepositoryInterface;
use ACedraz\CustomerImport\Api\Data\ProfileInterface;
use ACedraz\CustomerImport\Api\Data\ProfileInterfaceFactory;
use ACedraz\CustomerImport\Api\Data\ProfileSearchResultsInterfaceFactory;
use ACedraz\CustomerImport\Model\ResourceModel\Profile as Resource;
use ACedraz\CustomerImport\Model\ResourceModel\Profile\CollectionFactory  as CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Stdlib\DateTime\DateTime;

/**
 * Class ProfileRepository
 * @package ACedraz\CustomerImport\Model
 */
class ProfileRepository implements ProfileRepositoryInterface
{
    /**
     * @var Resource
     */
    private Resource $resource;

    /**
     * @var ProfileInterfaceFactory
     */
    private ProfileInterfaceFactory $profileFactory;

    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    /**
     * @var ProfileSearchResultsInterfaceFactory
     */
    private ProfileSearchResultsInterfaceFactory $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private CollectionProcessorInterface $collectionProcessor;

    /** @var DateTime */
    private DateTime $date;

    /** @var SearchCriteriaBuilder */
    private SearchCriteriaBuilder $_searchCriteriaBuilder;

    /** @var ProfileMapRepositoryInterface */
    private ProfileMapRepositoryInterface $profileMapRepository;

    /** @var ProfileMapInterfaceFactory */
    private ProfileMapInterfaceFactory $profileMapFactory;

    /**
     * @param DateTime $date
     * @param Resource $resource
     * @param ProfileInterfaceFactory $profileFactory
     * @param CollectionFactory $collectionFactory
     * @param ProfileSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param SearchCriteriaBuilder $_searchCriteriaBuilder
     * @param ProfileMapRepositoryInterface $profileMapRepository
     * @param ProfileMapInterfaceFactory $profileMapFactory
     */
    public function __construct(
        DateTime $date,
        Resource $resource,
        ProfileInterfaceFactory $profileFactory,
        CollectionFactory $collectionFactory,
        ProfileSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor,
        SearchCriteriaBuilder $_searchCriteriaBuilder,
        ProfileMapRepositoryInterface $profileMapRepository,
        ProfileMapInterfaceFactory $profileMapFactory
    ) {
        $this->resource = $resource;
        $this->profileFactory = $profileFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->date = $date;
        $this->_searchCriteriaBuilder = $_searchCriteriaBuilder;
        $this->profileMapRepository = $profileMapRepository;
        $this->profileMapFactory = $profileMapFactory;
    }

    /**
     * @param ProfileInterface $profile
     * @return ProfileInterface
     * @throws CouldNotSaveException
     */
    public function save(
        ProfileInterface $profile
    ): ProfileInterface
    {
        try {
            $profile->setUpdatedAt($this->date->gmtDate());
            $this->resource->save($profile);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the entity: %1',
                $exception->getMessage()
            ));
        }
        $maps = $profile->getMap();
        if ($maps) {
            foreach ($maps as $map) {
                $map->setProfileId($profile->getEntityId());
            }
        }
        return $profile->setMap($this->saveMap($maps));
    }

    /**
     * @param \ACedraz\CustomerImport\Api\Data\ProfileMapInterface[] $maps
     * @return \ACedraz\CustomerImport\Api\Data\ProfileMapInterface[]
     * @throws CouldNotSaveException
     */
    public function saveMap(array $maps) :array
    {
        if ($maps) {
            try {
                foreach ($this->getMap(current($maps)->getProfileId()) as $map) {
                    if ($map->getEntityId()) {
                        $this->profileMapRepository->deleteById($map->getEntityId());
                    }
                }
                foreach ($maps as $map) {
                    $this->profileMapRepository->save($map);
                }
            } catch (LocalizedException $exception) {
                throw new CouldNotSaveException(__(
                    'Could not save the map entity: %1',
                    $exception->getMessage()
                ));
            }
        }
        return $maps;
    }

    /**
     * @param $profileId
     * @return false|mixed|\ACedraz\CustomerImport\Api\Data\ProfileMapInterface[]
     * @throws NoSuchEntityException
     */
    public function getMap($profileId): array
    {
        try {
            $searchCriteria = $this->_searchCriteriaBuilder
                ->addFilter(ProfileMapInterface::PROFILE_ID, $profileId, 'eq')
                ->setCurrentPage(1)
                ->create();
            if (!$maps = $this->profileMapRepository->getList($searchCriteria)->getItems()) {
                $maps[] = $this->profileMapFactory->create();
            }
            return $maps;
        } catch (LocalizedException $exception) {
            throw new NoSuchEntityException(__('Map with profile id "%1" does not exist.', $profileId));
        }
    }

    /**
     * @param $profileId
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function get($profileId)
    {
        $profile = $this->profileFactory->create();
        $this->resource->load($profile, $profileId);
        if (!$profile->getId()) {
            throw new NoSuchEntityException(__('Entity with id "%1" does not exist.', $profileId));
        }
        $profile->setMap($this->getMap($profile->getEntityId()));
        return $profile;
    }

    /**
     * @param SearchCriteriaInterface $criteria
     * @return \ACedraz\CustomerImport\Api\Data\ProfileSearchResultsInterface
     */
    public function getList(
        SearchCriteriaInterface $criteria
    ) {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($criteria, $collection);
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $items = [];
        foreach ($collection as $model) {
            /** @var $model \ACedraz\CustomerImport\Api\Data\ProfileInterface */
            $items[] = $model->setMap($this->getMap($model->getEntityId()));
        }
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @param ProfileInterface $profile
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(
        ProfileInterface $profile
    ): bool
    {
        try {
            $profileModel = $this->profileFactory->create();
            $this->resource->load($profileModel, $profile->getEntityId());
            $profileModel->setMap($this->getMap($profileModel->getEntityId()));
            $this->resource->delete($profileModel);
            foreach ($profileModel->getMap() as $map) {
                $this->profileMapRepository->delete($map);
            }
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the entity: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @throws NoSuchEntityException
     * @throws CouldNotDeleteException
     */
    public function deleteById($profileId): bool
    {
        return $this->delete($this->get($profileId));
    }
}

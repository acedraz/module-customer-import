<?php
/**
 * ACedraz
 *
 * @category  ACedraz
 * @package   CustomerImport
 * @version   1.0.2
 * @author    Aislan Cedraz <aislan.cedraz@gmail.com.br>
 */

declare(strict_types=1);

namespace ACedraz\CustomerImport\Model;

use ACedraz\CustomerImport\Api\ProfileMapRepositoryInterface;
use ACedraz\CustomerImport\Api\Data\ProfileMapInterface;
use ACedraz\CustomerImport\Api\Data\ProfileMapInterfaceFactory;
use ACedraz\CustomerImport\Api\Data\ProfileMapSearchResultsInterfaceFactory;
use ACedraz\CustomerImport\Model\ResourceModel\ProfileMap as Resource;
use ACedraz\CustomerImport\Model\ResourceModel\ProfileMap\CollectionFactory  as CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class ProfileRepository
 * @package ACedraz\CustomerImport\Model
 */
class ProfileMapRepository implements ProfileMapRepositoryInterface
{
    /**
     * @var Resource
     */
    private Resource $resource;

    /**
     * @var ProfileMapInterfaceFactory
     */
    private ProfileMapInterfaceFactory $profileMapFactory;

    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    /**
     * @var ProfileMapSearchResultsInterfaceFactory
     */
    private ProfileMapSearchResultsInterfaceFactory $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private CollectionProcessorInterface $collectionProcessor;

    /**
     * @param Resource $resource
     * @param ProfileMapInterfaceFactory $profileMapFactory
     * @param CollectionFactory $collectionFactory
     * @param ProfileMapSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        Resource $resource,
        ProfileMapInterfaceFactory $profileMapFactory,
        CollectionFactory $collectionFactory,
        ProfileMapSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {

        $this->resource = $resource;
        $this->profileMapFactory = $profileMapFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @param ProfileMapInterface $profile
     * @return ProfileMapInterface
     * @throws CouldNotSaveException
     */
    public function save(
        ProfileMapInterface $profile
    ): ProfileMapInterface
    {
        try {
            $this->resource->save($profile);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the entity: %1',
                $exception->getMessage()
            ));
        }
        return $profile;
    }

    /**
     * @param $profileMapId
     * @return ProfileMapInterface
     * @throws NoSuchEntityException
     */
    public function get($profileMapId)
    {
        $profile = $this->profileMapFactory->create();
        $this->resource->load($profile, $profileMapId);
        if (!$profile->getId()) {
            throw new NoSuchEntityException(__('Entity with id "%1" does not exist.', $profileMapId));
        }
        return $profile;
    }

    /**
     * @param SearchCriteriaInterface $criteria
     * @return \ACedraz\CustomerImport\Api\Data\ProfileMapSearchResultsInterface
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
            $items[] = $model;
        }
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @param ProfileMapInterface $profile
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(
        ProfileMapInterface $profile
    ): bool
    {
        try {
            $profileModel = $this->profileMapFactory->create();
            $this->resource->load($profileModel, $profile->getEntityId());
            $this->resource->delete($profileModel);
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

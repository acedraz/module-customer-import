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

namespace ACedraz\CustomerImport\Model;

use ACedraz\CustomerImport\Api\ProfileRepositoryInterface;
use ACedraz\CustomerImport\Api\Data\ProfileInterface;
use ACedraz\CustomerImport\Api\Data\ProfileInterfaceFactory;
use ACedraz\CustomerImport\Api\Data\ProfileSearchResultsInterfaceFactory;
use ACedraz\CustomerImport\Model\ResourceModel\Profile as Resource;
use ACedraz\CustomerImport\Model\ResourceModel\Profile\CollectionFactory  as CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

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

    /**
     * @param Resource $resource
     * @param ProfileInterfaceFactory $profileFactory
     * @param CollectionFactory $collectionFactory
     * @param ProfileSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        Resource $resource,
        ProfileInterfaceFactory $profileFactory,
        CollectionFactory $collectionFactory,
        ProfileSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {

        $this->resource = $resource;
        $this->profileFactory = $profileFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
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
            $items[] = $model;
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

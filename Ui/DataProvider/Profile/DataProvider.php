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

namespace ACedraz\CustomerImport\Ui\DataProvider\Profile;

use ACedraz\CustomerImport\Api\Data\ProfileMapInterface;
use ACedraz\CustomerImport\Api\Data\ProfileMapInterfaceFactory;
use ACedraz\CustomerImport\Model\ProfileMapRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Ui\DataProvider\AbstractDataProvider;
use ACedraz\CustomerImport\Model\ResourceModel\Profile\CollectionFactory;
use ACedraz\CustomerImport\Model\ResourceModel\Profile\Collection;

/**
 * Class DataProvider
 */
class DataProvider extends AbstractDataProvider
{
    /** @var Collection */
    protected $collection;

    /** @var */
    protected $loadedData;

    /** @var ProfileMapRepository */
    private ProfileMapRepository $profileMapRepository;

    /** @var SearchCriteriaBuilder */
    private SearchCriteriaBuilder $_searchCriteriaBuilder;

    /** @var ProfileMapInterfaceFactory */
    private ProfileMapInterfaceFactory $profileMapFactory;

    /**
     * @param string $name
     * @param $primaryFieldName
     * @param $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param ProfileMapRepository $profileMapRepository
     * @param SearchCriteriaBuilder $_searchCriteriaBuilder
     * @param ProfileMapInterfaceFactory $profileMapFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        ProfileMapRepository $profileMapRepository,
        SearchCriteriaBuilder $_searchCriteriaBuilder,
        ProfileMapInterfaceFactory $profileMapFactory,
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->profileMapRepository = $profileMapRepository;
        $this->_searchCriteriaBuilder = $_searchCriteriaBuilder;
        $this->profileMapFactory = $profileMapFactory;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data.
     *
     * @return array
     */
    public function getData()
    {
        $items = $this->collection->getItems();
        foreach ($items as $model) {
            /** @var $model \ACedraz\CustomerImport\Api\Data\ProfileInterface */
            $this->loadedData[$model->getId()] = $model->getData();
            $this->loadedData[$model->getId()]['maps_container'] = $this->getMap($model->getEntityId());
        }
        return $this->loadedData;
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
            $mapResult = [];
            foreach ($maps as $map) {
                $mapResult[] = $map->getData();
            }
            return $mapResult;
        } catch (LocalizedException $exception) {
            throw new NoSuchEntityException(__('Map with profile id "%1" does not exist.', $profileId));
        }
    }
}

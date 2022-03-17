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

use ACedraz\CustomerImport\Api\IndexerInterface;
use Magento\Indexer\Model\Indexer\CollectionFactory;
use Magento\Indexer\Model\IndexerFactory;

/**
 * Class Indexer
 * @package ACedraz\CustomerImport\Model
 */
class Indexer implements IndexerInterface
{
    /** @var IndexerFactory */
    private IndexerFactory $_indexFactory;

    /** @var CollectionFactory */
    private CollectionFactory $_indexCollection;

    /**
     * @param IndexerFactory $_indexFactory
     * @param CollectionFactory $_indexCollection
     */
    public function __construct(
        IndexerFactory $_indexFactory,
        CollectionFactory $_indexCollection
    ) {
        $this->_indexFactory = $_indexFactory;
        $this->_indexCollection = $_indexCollection;
    }

    /**
     * @return \Magento\Indexer\Model\Indexer\CollectionFactory
     */
    private function getCollectionFactory(): CollectionFactory
    {
        return $this->_indexCollection;
    }

    /**
     * @return false|\Magento\Framework\Indexer\IndexerInterface[]
     */
    protected function getAllIndexers()
    {
        $indexers = $this->getCollectionFactory()->create()->getItems();
        return array_combine(
            array_map(
                function ($item) {
                    /** @var \Magento\Framework\Indexer\IndexerInterface $item */
                    return $item->getId();
                },
                $indexers
            ),
            $indexers
        );
    }

    /**
     * @return void
     */
    public function reindexAll()
    {
        foreach ($this->getAllIndexers() as $index) {
            $this->reindex($index->getId());
        }
    }

    /**
     * @param string $indexId
     * @return void
     */
    public function reindex(string $indexId)
    {
        /** @var Indexer $index */
        $index = $this->_indexFactory->create()->load($indexId);
        $index->reindexAll();
    }
}

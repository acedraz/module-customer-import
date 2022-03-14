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

namespace ACedraz\CustomerImport\Model\ResourceModel\ProfileMap;

use ACedraz\CustomerImport\Api\Data\ProfileMapInterface;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package ACedraz\CustomerImport\Model\ResourceModel\ProfileMap
 */
class Collection extends AbstractCollection
{
    /** @var string */
    protected $_idFieldName = ProfileMapInterface::ENTITY_ID;

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            \ACedraz\CustomerImport\Model\Data\ProfileMap::class,
            \ACedraz\CustomerImport\Model\ResourceModel\ProfileMap::class
        );
    }
}

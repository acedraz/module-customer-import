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

namespace ACedraz\CustomerImport\Model\ResourceModel\Profile;

use ACedraz\CustomerImport\Api\Data\ProfileInterface;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package ACedraz\CustomerImport\Model\ResourceModel\Profile
 */
class Collection extends AbstractCollection
{
    /** @var string */
    protected $_idFieldName = ProfileInterface::ENTITY_ID;

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            \ACedraz\CustomerImport\Model\Data\Profile::class,
            \ACedraz\CustomerImport\Model\ResourceModel\Profile::class
        );
    }
}

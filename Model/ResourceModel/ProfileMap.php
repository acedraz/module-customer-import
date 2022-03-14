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

namespace ACedraz\CustomerImport\Model\ResourceModel;

use ACedraz\CustomerImport\Api\Data\ProfileMapInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class ProfileMap
 * @package ACedraz\CustomerImport\Model\ResourceModel
 */
class ProfileMap extends AbstractDb
{
    /** @var string */
    const TABLE = 'customer_import_profile_map';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(self::TABLE, ProfileMapInterface::ENTITY_ID);
    }
}

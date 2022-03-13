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

namespace ACedraz\CustomerImport\Model\ResourceModel;

use ACedraz\CustomerImport\Api\Data\ProfileInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Profile
 * @package ACedraz\CustomerImport\Model\ResourceModel
 */
class Profile extends AbstractDb
{
    /** @var string */
    const TABLE = 'customer_import_profile';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(self::TABLE, ProfileInterface::ENTITY_ID);
    }
}

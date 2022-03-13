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

namespace ACedraz\CustomerImport\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Status
 * @package ACedraz\CustomerImport\Model\Config\Source
 */
class Status implements OptionSourceInterface
{
    /** @var string */
    const STATUS_ENABLE_VALUE = 1;

    /** @var string */
    const STATUS_ENABLE = 'enable';

    /** @var string */
    const STATUS_DISABLE_VALUE = 2;

    /** @var string */
    const STATUS_DISABLE = 'disable';

    /**
     * @var string[]
     */
    protected array $_stateStatus = [
        self::STATUS_ENABLE_VALUE => self::STATUS_ENABLE,
        self::STATUS_DISABLE_VALUE => self::STATUS_DISABLE
    ];

    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        $statuses = $this->_stateStatus;
        $options = [];
        foreach ($statuses as $code => $label) {
            $options[] = ['value' => $code, 'label' => $label];
        }
        return $options;
    }

}

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

namespace ACedraz\CustomerImport\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\ImportExport\Model\Import;
use Magento\ImportExport\Model\Source\Import\Behavior\Factory;

/**
 * Class ImportBehavior
 * @package ACedraz\CustomerImport\Model\Config\Source
 */
class ImportBehavior implements OptionSourceInterface
{
    /** @var string */
    const BEHAVIOR_CODE = 'custom_behavior';

    /** @var Import */
    private Import $_importModel;

    /** @var Factory */
    private Factory $_behaviorFactory;

    /**
     * @param Factory $_behaviorFactory
     * @param Import $_importModel
     */
    public function __construct(
        Factory $_behaviorFactory,
        Import $_importModel
    ) {
        $this->_importModel = $_importModel;
        $this->_behaviorFactory = $_behaviorFactory;
    }

    /**
     * @return array|array[]|void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function toOptionArray()
    {
        $uniqueBehaviors = $this->_importModel->getUniqueEntityBehaviors();
        foreach ($uniqueBehaviors as $behaviorCode => $behaviorClass) {
            if ($behaviorCode === self::BEHAVIOR_CODE) {
                return $this->_behaviorFactory->create($behaviorClass)->toOptionArray();
            }
        }
    }
}

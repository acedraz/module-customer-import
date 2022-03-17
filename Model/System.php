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

use ACedraz\CustomerImport\Api\SystemInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;

/**
 * Class System
 * @package ACedraz\CustomerImport\Model
 */
class System implements SystemInterface
{
    /** @var WriterInterface */
    private WriterInterface $_configWriter;

    /** @var ScopeConfigInterface */
    private ScopeConfigInterface $_scopeConfig;

    /**
     * SystemAbstract constructor.
     * @param WriterInterface $_configWriter
     * @param ScopeConfigInterface $_scopeConfig
     */
    public function __construct(
        WriterInterface $_configWriter,
        ScopeConfigInterface $_scopeConfig
    ) {
        $this->_configWriter = $_configWriter;
        $this->_scopeConfig = $_scopeConfig;
    }

    /**
     * @param string $path
     * @param string $scopeType
     * @param null $scopeCode
     * @return mixed
     */
    private function getValue(string $path, string $scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeCode = null)
    {
        return $this->_scopeConfig->getValue(
            $path,
            $scopeType,
            $scopeCode
        );
    }

    /**
     * @param $path
     * @param $value
     * @param string $scope
     * @param int $scopeId
     */
    private function setValue($path, $value, string $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, int $scopeId = 0)
    {
        $this->_configWriter->save( $path, $value, $scope, $scopeId);
    }

    /**
     * @return string
     */
    public function getCustomerBehavior(): string
    {
        return (string) $this->getValue(Config::SECTION_CUSTOMERIMPORT_GROUP_CUSTOMER_FIELD_IMPORT_BEHAVIOR);
    }

    /**
     * @return string
     */
    public function getAddressBehavior(): string
    {
        return (string) $this->getValue(Config::SECTION_CUSTOMERIMPORT_GROUP_ADDRESS_FIELD_IMPORT_BEHAVIOR);
    }

    /**
     * @return string
     */
    public function getCompositeBehavior(): string
    {
        return (string) $this->getValue(Config::SECTION_CUSTOMERIMPORT_GROUP_COMPOSITE_FIELD_IMPORT_BEHAVIOR);
    }
}

<?php
/**
 * ACedraz
 *
 * @category  ACedraz
 * @package   CustomerImport
 * @version   1.0.3
 * @author    Aislan Cedraz <aislan.cedraz@gmail.com.br>
 */

declare(strict_types=1);

namespace ACedraz\CustomerImport\Setup\Patch\Data;

use ACedraz\CustomerImport\Api\Data\ProfileInterface;
use ACedraz\CustomerImport\Api\Data\ProfileInterfaceFactory;
use ACedraz\CustomerImport\Api\Data\ProfileMapInterface;
use ACedraz\CustomerImport\Api\Data\ProfileMapInterfaceFactory;
use ACedraz\CustomerImport\Api\ProfileRepositoryInterface;
use ACedraz\CustomerImport\Model\Config\Source\CustomerImportColumns;
use ACedraz\CustomerImport\Model\Config\Source\Status;
use Magento\Framework\Setup\Patch\DataPatchInterface;

/**
 * Class SampleProfile
 * @package ACedraz\CustomerImport\Setup\Patch\Data
 */
class SampleProfile implements DataPatchInterface
{
    /**
     * @var array|array[]
     */
    protected array $sampleData = [
        'sample-csv' => [
            'fname' => CustomerImportColumns::FIRSTNAME_VALUE,
            'lname' => CustomerImportColumns::LASTNAME_VALUE,
            'emailaddress' => CustomerImportColumns::EMAIL_VALUE,
        ],
        'sample-json' => [
            'fname' => CustomerImportColumns::FIRSTNAME_VALUE,
            'lname' => CustomerImportColumns::LASTNAME_VALUE,
            'emailaddress' => CustomerImportColumns::EMAIL_VALUE,
        ]
    ];

    /** @var ProfileRepositoryInterface  */
    private ProfileRepositoryInterface $profileRepository;

    /** @var ProfileInterfaceFactory */
    private ProfileInterfaceFactory $profileFactory;

    /** @var ProfileMapInterfaceFactory */
    private ProfileMapInterfaceFactory $profileMapFactory;

    /**
     * @param ProfileRepositoryInterface $profileRepository
     * @param ProfileInterfaceFactory $profileFactory
     * @param ProfileMapInterfaceFactory $profileMapFactory
     */
    public function __construct(
        ProfileRepositoryInterface $profileRepository,
        ProfileInterfaceFactory $profileFactory,
        ProfileMapInterfaceFactory $profileMapFactory
    ) {
        $this->profileRepository = $profileRepository;
        $this->profileFactory = $profileFactory;
        $this->profileMapFactory = $profileMapFactory;
    }

    /**
     * @return ProfileInterface
     */
    public function getProfileEntity(): ProfileInterface
    {
        return $this->profileFactory->create();
    }

    /**
     * @return ProfileMapInterface
     */
    public function getProfileMapEntity(): ProfileMapInterface
    {
        return $this->profileMapFactory->create();
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function apply(): void
    {
        foreach ($this->sampleData as $sampleName => $mapData) {
            $profile = $this->getProfileEntity()
                ->setEnable(Status::STATUS_ENABLE_VALUE)
                ->setName($sampleName);
            $maps = [];
            foreach ($mapData as $column => $attribute) {
                $maps[] = $this->getProfileMapEntity()
                    ->setColumn($column)
                    ->setAttribute($attribute);
            }
            $profile->setMap($maps);
            $this->profileRepository->save($profile);
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases(): array
    {
        return [];
    }
}

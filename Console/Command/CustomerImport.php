<?php
/**
 * ACedraz
 *
 * @category  ACedraz
 * @package   CustomerImport
 * @version   1.0.5
 * @author    Aislan Cedraz <aislan.cedraz@gmail.com.br>
 */

declare(strict_types=1);

namespace ACedraz\CustomerImport\Console\Command;

use ACedraz\CustomerImport\Api\CustomerImportInterface;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\Console\Cli;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBarFactory;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Class CustomerImport
 * @package ACedraz\CustomerImport\Console\Command
 */
class CustomerImport extends Command
{
    /** @var string */
    const PROFILE_NAME = 'profile-name';

    /** @var string */
    const FILE_NAME = 'file-name';

    /** @var ProgressBarFactory */
    private ProgressBarFactory $progressBarFactory;

    /** @var State */
    private State $_state;

    /** @var CustomerImportInterface */
    private CustomerImportInterface $customerImport;

    /**
     * @param CustomerImportInterface $customerImport
     * @param ProgressBarFactory $progressBarFactory
     * @param State $_state
     * @param string|null $name
     */
    public function __construct(
        CustomerImportInterface $customerImport,
        ProgressBarFactory $progressBarFactory,
        State $_state,
        string $name = null
    ) {
        parent::__construct($name);
        $this->progressBarFactory = $progressBarFactory;
        $this->_state = $_state;
        $this->customerImport = $customerImport;
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName('customer:import')
            ->setDescription('Provide customer import through a pre-registered profile and a file.');
        $this->addArgument(self::PROFILE_NAME,InputArgument::REQUIRED,'Provide profile. See System -> Data Transfer -> Profile Map');
        $this->addArgument(self::FILE_NAME,InputArgument::REQUIRED,'Provide file name. Can be uploaded in Stores -> Configuration -> ACedraz Extensions -> Customer Import -> File Upload');
        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        /** @var \Symfony\Component\Console\Helper\QuestionHelper $questionHelper */
        $questionHelper = $this->getHelper('question');
        $profiles = $this->customerImport->getAllProfiles();
        if (!count($profiles)) {
            $output->writeln('<error>' . __('No profiles registered.') . '</error>');
            $output->writeln('<error>' . __('Register in System -> Data Transfer -> Profile Map.') . '</error>');
            return Cli::RETURN_FAILURE;
        }
        if (!$input->getArgument(self::PROFILE_NAME)) {
            $output->writeln('<info>' . __('Available profiles:') . '</info>');
            $output->write(PHP_EOL);
            foreach ($profiles as $profile) {
                $output->writeln('<info>' . $profile->getName() . '</info>');
            }
            $output->write(PHP_EOL);
            $question = new Question('<question>' . __('Please enter the profile name:') . '</question> ');
            $input->setArgument(
                self::PROFILE_NAME,
                $questionHelper->ask($input, $output, $question)
            );
        }
        if (!$input->getArgument(self::FILE_NAME)) {
            $question = new Question('<question>' . __('Please enter the file name:') . '</question> ');
            $input->setArgument(
                self::PROFILE_NAME,
                $questionHelper->ask($input, $output, $question)
            );
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->_state->setAreaCode(Area::AREA_ADMINHTML);
        } catch (LocalizedException $e) {}
        try {
            $this->validate($input, $output);
        } catch (FileSystemException $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
            return Cli::RETURN_FAILURE;
        }
        $output->writeln('<info>' . __('Import in progress') . '</info>');
        try {
            $this->customerImport->import($input->getArgument(self::PROFILE_NAME), $input->getArgument(self::FILE_NAME));
        } catch (FileSystemException|LocalizedException $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
            return Cli::RETURN_FAILURE;
        }
        $output->writeln('<info>' . __('Import successfully done') . '</info>');
        $output->writeln('<info>' . __('Indexing customer grid in progress') . '</info>');
        $this->customerImport->reindex();
        $output->writeln('<info>' . __('Process finished') . '</info>');
        return Cli::RETURN_SUCCESS;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param string|null $argument
     * @return int|void
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    protected function validate(InputInterface $input, OutputInterface $output, string $argument = null)
    {
        switch ($argument) {
            case self::PROFILE_NAME;
                $this->validateProfile($input,$output);
                break;
            case self::FILE_NAME;
                $this->validateFile($input,$output);
                break;
            default;
                $this->validateProfile($input,$output);
                $this->validateFile($input,$output);
                break;
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     */
    public function validateProfile(InputInterface $input, OutputInterface $output)
    {
        if (!$this->customerImport->validateProfile($input->getArgument(self::PROFILE_NAME))) {
            $output->writeln('<error>' . __('Invalid profile') . '</error>');
            return Cli::RETURN_FAILURE;
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     * @throws FileSystemException
     */
    public function validateFile(InputInterface $input, OutputInterface $output)
    {
        if (!$this->customerImport->validateFile($input->getArgument(self::FILE_NAME))) {
            $output->writeln('<error>' . __('Invalid file') . '</error>');
            return Cli::RETURN_FAILURE;
        }
    }
}

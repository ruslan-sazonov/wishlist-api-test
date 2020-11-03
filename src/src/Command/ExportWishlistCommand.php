<?php

namespace App\Command;

use App\Entity\User;
use App\Entity\Wishlist;
use App\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\SerializerInterface;

class ExportWishlistCommand extends Command
{
    private const DEFAULT_REPORT_DIRNAME = 'csv_reports';
    private const DEFAULT_REPORT_BASE_NAME = 'users_whislist_';

    protected static $defaultName = 'export:wishlist:csv';
    /** @var UserRepository $userRepository */
    protected $userRepository;
    /** @var ParameterBagInterface $parameterBag */
    protected $parameterBag;
    /** @var Filesystem $filesystem */
    protected $filesystem;
    /** @var SerializerInterface $serializer */
    protected $serializer;

    public function __construct(
        UserRepository $userRepository,
        ParameterBagInterface $parameterBag,
        Filesystem $filesystem,
        SerializerInterface $serializer
    ) {
        $this->userRepository = $userRepository;
        $this->parameterBag = $parameterBag;
        $this->filesystem = $filesystem;
        $this->serializer = $serializer;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Exports wishlists to CSV')
            ->setHelp('This command exports wishlists for all users to CSV format');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("<comment>I'm going to export all wislists for all users who have them to CSV format...</comment>");

        try {
            $users = $this->userRepository->findAll();

            if (!$users) {
                $output->writeln("<comment>No users found. Nothing to process.</comment>");

                return Command::SUCCESS;
            }

            $this->filesystem->mkdir($this->getReportDirPath());
            $csvReportFileName = $this->getReportFileName();
            $csvReportPath = $this->getReportFilePath($csvReportFileName);

            foreach ($this->getCsvData($users) as $line) {
                $serialized = $this->serializer->serialize($line, 'csv', ['delimiter' => ';', 'no_headers' => true]);
                $this->filesystem->appendToFile($csvReportPath, $serialized);
            }
        } catch (\Exception | IOExceptionInterface $exception) {
            $output->writeln("<error>".$exception->getMessage()."</error>");

            return Command::FAILURE;
        }

        $output->writeln("");
        $output->writeln("<info>Huge success! Your CSV report created. You can find it here:</info>");
        $output->writeln("<info>".$csvReportPath."</info>");

        return Command::SUCCESS;
    }

    /**
     * @param $users
     * @return \Generator
     * @throws \Exception
     */
    protected function getCsvData($users): \Generator
    {
        /** @var User $user */
        foreach ($users as $user) {
            if ($user->getWishlists()->count()) {
                $iterator = $user->getWishlists()->getIterator();

                /** @var Wishlist $wishlist */
                foreach ($iterator as $wishlist) {
                    yield [
                        $user->getUsername(),
                        $wishlist->getName(),
                        $wishlist->getProducts()->count()
                    ];
                }
            }
        }
    }

    /**
     * @return string
     */
    protected function getReportDirPath(): string
    {
        $projectDirPath = $this->parameterBag->get('kernel.project_dir');
        return sprintf("%s/%s", $projectDirPath, self::DEFAULT_REPORT_DIRNAME);
    }

    /**
     * @return string
     */
    protected function getReportFileName(): string
    {
        return sprintf("%s%s.csv", self::DEFAULT_REPORT_BASE_NAME, date('Y_m_d-H:i'));
    }

    /**
     * @param string $reportFileName
     * @return string
     */
    protected function getReportFilePath(string $reportFileName): string
    {
        return sprintf("%s/%s", $this->getReportDirPath(), $reportFileName);
    }
}

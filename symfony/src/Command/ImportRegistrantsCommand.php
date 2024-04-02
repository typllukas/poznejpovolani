<?php

namespace App\Command;

use App\Entity\Module;
use App\Entity\Registrant;
use App\Entity\Webinar;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class ImportRegistrantsCommand extends Command
{
    protected static $defaultName = 'app:import-registrants';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Imports registrants from a CSV file.')
            ->addArgument('filePath', InputArgument::REQUIRED, 'The path to the CSV file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filePath = $input->getArgument('filePath');
        $handle = fopen($filePath, 'r');
        $processedEmails = []; // To handle duplicate registrants within a single CSV file

        if (!$handle) {
            $output->writeln('Error opening file');
            return Command::FAILURE;
        }

        while (($row = fgetcsv($handle, 1000, ",")) !== false) {
            [$email, $firstName, $lastName, $moduleId] = $row;

            if (!isset($processedEmails[$email])) {
                $registrant = $this->entityManager->getRepository(Registrant::class)->findOneBy(['email' => $email]);

                if (!$registrant) {
                    $registrant = new Registrant();
                    $registrant->setEmail($email)->setFirstName($firstName)->setLastName($lastName);
                    $this->entityManager->persist($registrant);
                }

                $processedEmails[$email] = $registrant;
            } else {
                $registrant = $processedEmails[$email];
            }

            $module = $this->entityManager->getRepository(Module::class)->find($moduleId);
            if ($module && !$registrant->getModules()->contains($module)) {
                $registrant->addModule($module);
                
                foreach ($module->getWebinars() as $webinar) {
                    $webinar->addRegistrant($registrant);
                }
            } else if (!$module) {
                $output->writeln("Module not found for ID: $moduleId");
            }
        }

        fclose($handle);
        $this->entityManager->flush();
        $output->writeln('Import successful.');

        return Command::SUCCESS;
    }
}

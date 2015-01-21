<?php
namespace LifeLab\RestBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use LifeLab\RestBundle\Entity\Illness;

class IllnessImporterCommand extends ContainerAwareCommand {
	protected function configure() {
		$this->setName('illness:import')
			->setDescription('Import illnesses data in the database')
			->addArgument('path/to/LIBELLE.xml', InputArgument::REQUIRED, 'Qui voulez vous saluer??');
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$dataFile = $input->getArgument('path/to/LIBELLE.xml');
		if (file_exists($dataFile)) {
			$output->writeln($dataFile . ' exists!');
		} else {
			$output->writeln($dataFile . ' does not exist!');
			return 0;
		}
		$entityManager = $this->getContainer()->get('doctrine')->getManager();
		$illnesses = simplexml_load_file($dataFile);
		if ($illnesses == NULL) {
			$output->writeln('Could not load ' . $dataFile . ' properly!');
			return 1;
		}
		$output->writeln('Loaded ' . $dataFile . ' ...');
		foreach ($illnesses as $line) {
			if ($line->LID != NULL && $line->SID && $line->valid != NULL && $line->libelle != NULL) {
				$illness = new Illness();
				$illness->setName($line->libelle);
				$entityManager->persist($illness);
			}
		}
		$entityManager->flush();
		$output->writeln('Import completed!');
		return 0;
	}
}

?>
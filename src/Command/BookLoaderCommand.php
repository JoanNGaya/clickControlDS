<?php

namespace App\Command;

use App\Entity\Book;
use Carbon\Carbon;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;
class BookLoaderCommand extends Command
{
    protected static $defaultName = 'BookLoader';
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;

        parent::__construct();
    }


    protected function configure()
    {
        $this
            ->setDescription('Get the books information from a file and store them in the db')
            ->addArgument('filePath', InputArgument::REQUIRED, 'The file path with the books information')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $filePath = $input->getArgument('filePath');

        $booksJson = file_get_contents(__DIR__ . '/' . $filePath);
        $books = json_decode($booksJson, true);

        foreach ($books['books'] as $book) {
            $this->saveBook($book);
        }

        return 0;
    }

    protected function saveBook($book)
    {
        $bookObject = new Book();

        $bookObject->setIsbn($book['isbn']);
        $bookObject->settitle($book['title']);
        $bookObject->setSubtitle($book['subtitle']);
        $bookObject->setAuthor($book['author']);
        $bookObject->setPublished(new Carbon($book['published']));
        $bookObject->setPublisher($book['publisher']);
        $bookObject->setPages($book['pages']);
        $bookObject->setDescription($book['description']);
        $bookObject->setWebsite($book['website']);
        $bookObject->setCategory($book['category']);

        $this->em->persist($bookObject);
        $this->em->flush();
    }
}

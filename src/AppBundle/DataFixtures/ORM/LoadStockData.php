<?php


namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Stock;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadStockData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $stock1 = new Stock();
        $stock1->setSymbol("AAPL");
        $stock1->setDescription("Apple Inc.");

        $stock2 = new Stock();
        $stock2->setSymbol("FB");
        $stock2->setDescription("Facebook, Inc.");

        $stock3 = new Stock();
        $stock3->setSymbol("MSFT");
        $stock3->setDescription("Microsoft Corporation");

        $stock3 = new Stock();
        $stock3->setSymbol("C");
        $stock3->setDescription("Citigroup Inc.");

        $stock4 = new Stock();
        $stock4->setSymbol("GOOG");
        $stock4->setDescription("Alphabet Inc.");

        $stock5 = new Stock();
        $stock5->setSymbol("UBS");
        $stock5->setDescription("UBS Group AG");

        $stock6 = new Stock();
        $stock6->setSymbol("PYPL");
        $stock6->setDescription("PayPal Holdings, Inc.");

        $stock7 = new Stock();
        $stock7->setSymbol("LNKD");
        $stock7->setDescription("LinkedIn Corporation");

        $manager->persist($stock1);
        $manager->persist($stock2);
        $manager->persist($stock3);
        $manager->persist($stock4);
        $manager->persist($stock5);
        $manager->persist($stock6);
        $manager->persist($stock7);

        $manager->flush();
    }
}
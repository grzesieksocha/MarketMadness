<?php


namespace AppBundle\Command;

use AppBundle\Entity\HistoricalData;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetStockDataCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('mm:getData')
            ->setDescription('Download recent data from Yahoo Finance')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $stocks = $doctrine->getRepository('AppBundle:Stock')->findAll();
        $stockSymbols = $doctrine->getRepository('AppBundle:Stock')
            ->getAllSymbols();
        
        $data = $this->getContainer()->get('data_getter')->getDataWithSymbolAsKey($stockSymbols);

        $stocksBySymbolArray = [];
        foreach ($stocks as $stock) {
            $stocksBySymbolArray[$stock->getSymbol()] = $stock;
        }

        foreach ($stocksBySymbolArray as $symbol => $stock) {
            $historicalData = new HistoricalData();
            $stockData = $data[$symbol];
            $updatedStock = $stock;
            
            $em = $doctrine->getManager();
            $historicalData->setDownloadDate(new \DateTime('now'));
            $historicalData->setDataTime(new \DateTime($stockData['utctime']));
            $historicalData->setPrice((float)$stockData['price']);
            $historicalData->setPriceChange((float)$stockData['change']);
            $historicalData->setChangePercent((float)$stockData['chg_percent']);
            $historicalData->setDayHigh((float)$stockData['day_high']);
            $historicalData->setDayLow((float)$stockData['day_low']);
            $historicalData->setYearHigh((float)$stockData['year_high']);
            $historicalData->setYearLow((float)$stockData['year_low']);
            $historicalData->setStock($updatedStock);

            $em->persist($historicalData);
            $em->flush();
            $output->writeln('Saved transaction for: '. $updatedStock->getDescription());
        }
    }
}
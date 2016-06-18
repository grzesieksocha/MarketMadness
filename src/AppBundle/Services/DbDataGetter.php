<?php


namespace AppBundle\Services;

use AppBundle\Entity\HistoricalData;
use Doctrine\DBAL\Exception\InvalidArgumentException;
use Doctrine\ORM\EntityManagerInterface;

class DbDataGetter
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getDataArrayWithSymbolAsKey(array $stockSymbols, array $fields = [])
    {
        $stocksToCheck = [];
        foreach ($stockSymbols as $symbol) {
            $stocksToCheck[$symbol] = $this->em->getRepository('AppBundle:Stock')->findOneBy(['symbol' => $symbol]);
        }

        $result = [];
        if (!count($fields)) {
            foreach ($stocksToCheck as $stock) {
                $historicalData = $this->em
                    ->getRepository('AppBundle:HistoricalData')
                    ->getLatestStockData($stock);
                $result[$stock->getSymbol()]['symbol'] = $stock->getSymbol();
                $result[$stock->getSymbol()]['name'] = $stock->getDescription();
                $result[$stock->getSymbol()]['utctime'] = $historicalData->getDataTime();
                $result[$stock->getSymbol()]['price'] = $historicalData->getPrice();
                $result[$stock->getSymbol()]['change'] = $historicalData->getPriceChange();
                $result[$stock->getSymbol()]['chg_percent'] = $historicalData->getChangePercent();
                $result[$stock->getSymbol()]['day_high'] = $historicalData->getDayHigh();
                $result[$stock->getSymbol()]['day_low'] = $historicalData->getDayLow();
                $result[$stock->getSymbol()]['year_high'] = $historicalData->getYearHigh();
                $result[$stock->getSymbol()]['year_low'] = $historicalData->getYearLow();

            }
        } else {
            foreach ($stocksToCheck as $stock) {
                $historicalData = $this->em
                    ->getRepository('AppBundle:HistoricalData')
                    ->getLatestStockData($stock);

                foreach ($fields as $field) {
                    switch ($field) {
                        case 'symbol':
                            $insert = $stock->getSymbol();
                            break;
                        case 'name':
                            $insert = $stock->getDescription();
                            break;
                        case 'utctime':
                            $insert = $historicalData->getDataTime();
                            break;
                        case 'price':
                            $insert = $historicalData->getPrice();
                            break;
                        case 'change':
                            $insert = $historicalData->getPriceChange();
                            break;
                        case 'chg_percent':
                            $insert = $historicalData->getChangePercent();
                            break;
                        case 'day_high':
                            $insert = $historicalData->getDayHigh();
                            break;
                        case 'day_low':
                            $insert = $historicalData->getDayLow();
                            break;
                        case 'year_high':
                            $insert = $historicalData->getYearHigh();
                            break;
                        case 'year_low':
                            $insert = $historicalData->getYearLow();
                            break;
                        default:
                            throw new InvalidArgumentException("Invalid data field!!!");
                    }
                    $result[$stock->getSymbol()][$field] = $insert;
                }
            }
        }
        return $result;
    }
}
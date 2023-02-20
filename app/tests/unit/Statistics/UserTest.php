<?php

namespace Tests\unit\Statistics\Calculator;

use PHPUnit\Framework\TestCase;
use Statistics\Builder\ParamsBuilder;
use Statistics\Calculator\AveragePostLength;
use SocialPost\Dto\SocialPostTo;
use DateTime;

class UserTest extends TestCase
{
    public function testDoesCalculate()
    {
        $start = $end = new DateTime();
        $startDate = (clone $start)->modify('first day of this month')->setTime(0, 0, 0);
        $endDate   = (clone $end)->modify('last day of this month')->setTime(23, 59, 59);
        $outDate   = (clone $end)->modify('first day of may 2030');
        $params = ParamsBuilder::reportStatsParams($startDate, $endDate);

        $calculator = (new AveragePostLength())
            ->setParameters($params[0]);

        $socialPost1 = (new SocialPostTo())
            ->setId("1")
            ->setText("Text 1")
            ->setDate($startDate);
        $calculator->accumulateData($socialPost1);

        $socialPost2 = (new SocialPostTo())
            ->setId("2")
            ->setText("Text 222")
            ->setDate($startDate);
        $calculator->accumulateData($socialPost2);

        $socialPost3 = (new SocialPostTo())
            ->setId("3")
            ->setText("Text 33333")
            ->setDate($outDate);
        $calculator->accumulateData($socialPost3);

        // average
        $averagePostLength = $calculator->calculate();
        $this->assertEquals(7, $averagePostLength->getValue());
    }
}

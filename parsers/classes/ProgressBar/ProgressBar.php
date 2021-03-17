<?php

namespace ProgressBar;

class ProgressBar
{
    private const PROGRESS_SIZE = 50;

    private $total;
    private $parserName;
    private $step = 0;
    private $startTime;
    private $avgTime = null;

    public function __construct(int $total, string $parserName)
    {
        $this->total = $total;
        $this->parserName = $parserName;
        $this->startTime = time();
        print_r("Parser '".$parserName."' start...\n");
    }

    public function makeStep()
    {
        ++$this->step;
        $this->draw();
    }

    private function countLeftTime()
    {
        $now = time();
        $passTime = $now - $this->startTime;
        $endTime = round($passTime * ($this->total / $this->step));
        return $endTime - $passTime;
    }

    private function setAvgTime()
    {
        $leftTime = $this->countLeftTime();
        if ($this->avgTime) {
            $leftTime = ($this->avgTime + $leftTime) / 2;
        }

        $this->avgTime = $leftTime;
    }

    private function countTime()
    {
        $this->setAvgTime();

        $string = "Time left: ~ "
            .date('i', $this->avgTime)
            ." minutes "
            .date('s', $this->avgTime)
            ." seconds";
        return $string;
    }

    private function getPercents()
    {
        $rawPercents = ($this->step / $this->total) * 100;
        return round($rawPercents, 2);
    }

    private function getCompleteCount()
    {
        $rawComplete = ($this->step / $this->total) * self::PROGRESS_SIZE;
        return round($rawComplete);
    }

    private function getNotCompleteCount()
    {
        return self::PROGRESS_SIZE - $this->getCompleteCount();
    }

    private function genSymbolsString(string $symbol, int $count)
    {
        $string = '';
        while ($count > 0) {
            --$count;
            $string .= $symbol;
        }
        return $string;
    }

    private function draw()
    {
        $percents = $this->getPercents();
        $completeString = $this->genSymbolsString('█', $this->getCompleteCount());
        $notCompleteString = $this->genSymbolsString(' ', $this->getNotCompleteCount());

        print_r("\r["
            .$completeString.$notCompleteString
            ."] "
            .$percents
            ."% "
            .$this->countTime()
            ."          ");
    }

    private function getFinalTime()
    {
        $diffTime = time() - $this->startTime;
        $diffTimeString = date('H:i:s', $diffTime);
        return $diffTimeString.' have passed';
    }

    public function close(array $errors = [])
    {
        $str = $this->genSymbolsString('█', self::PROGRESS_SIZE);

        $finalTime = $this->getFinalTime();

        if ($errors) {
            print_r("\r[".$str.'] ERR '.$finalTime.' total: '.$this->total."     \n");
            print_r($errors);
        } else {
            print_r("\r[".$str.'] OK '.$finalTime.' total: '.$this->total."     \n");
        }
    }
}

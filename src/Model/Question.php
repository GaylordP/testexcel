<?php

namespace App\Model;

class Question extends AbstractQuestion
{
    /**
     * @var float
     */
    private $min;

    /**
     * @var float
     */
    private $max;

    /**
     * @var float
     */
    private $mean;

    public function __construct(string $label)
    {
        parent::__construct($label);
    }

    public function getStatistics(): array
    {
        return [
            'min' => $this->getMin(),
            'max' => $this->getMax(),
            'mean' => $this->getMean(),
        ];
    }

    public function getMin(): float
    {
        return $this->min;
    }

    public function setMin(float $min): void
    {
        $this->min = $min;
    }

    public function getMax(): float
    {
        return $this->max;
    }

    public function setMax(float $max): void
    {
        $this->max = $max;
    }

    public function getMean(): float
    {
        return $this->mean;
    }

    public function setMean(float $mean): void
    {
        $this->mean = $mean;
    }

    public function setValues(array $listNote): void
    {
        if (!empty($listNote)) {
            $this->setMin(min($listNote));
            $this->setMax(max($listNote));
            $this->setMean(round(array_sum($listNote) / count($listNote), 2));
        }
    }
}

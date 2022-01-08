<?php

namespace App\Normalizer;

use App\Model\Question;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class QuestionNormalizer
{
    private $normalizer;

    public function __construct(NormalizerInterface $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function normalize(Question $question): array
    {
        $normalized = $this
            ->normalizer
            ->normalize($question)
        ;

        unset($normalized['min'], $normalized['max'], $normalized['mean']);

        return [
            'question' => $normalized,
        ];
    }
}

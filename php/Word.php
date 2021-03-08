<?php

namespace PhrasesGenerator;

class Word
{
    protected string $word;
    protected WordStatistic $stats;

    protected array $followedWords = [];

    public function __construct (string $word, WordStatistic $stats = null)
    {
        $this->word  = mb_strtolower ($word);
        $this->stats = $stats ?: WordStatistic::get ($this->word);
    }

    public function word (): string
    {
        return $this->word;
    }

    public function stats (): WordStatistic
    {
        return $this->stats;
    }

    public function follow (Word $word, bool $updateStats = true): self
    {
        $this->followedWords[] = $word;

        if ($updateStats)
            $this->stats->follow($word);

        return $this;
    }

    public function followed (): array
    {
        return $this->followedWords;
    }

    public function getRandomFollowed (): ?Word
    {
        $follow = rand (1, $this->stats->usedTimes ());
        $shift  = 0;

        foreach ($this->stats->follows () as $word => $uses)
            if (($shift += $uses) >= $follow)
                return new Word ($word);
        
        return null;
    }

    public function __toString (): string
    {
        return $this->word;
    }
}

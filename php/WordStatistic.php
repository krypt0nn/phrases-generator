<?php

namespace PhrasesGenerator;

class WordStatistic
{
    protected int $usedTimes      = 0;
    protected int $usedAsEntrance = 0;
    protected array $usedInPlace  = [];

    public function usedTimes (int $times = null): int|self
    {
        if ($times !== null)
        {
            $this->usedTimes = $times;

            return $this;
        }

        else return $this->usedTimes;
    }

    public function incUsedTimes (): self
    {
        ++$this->usedTimes;

        return $this;
    }

    public function followedUsages (Word $word, int $times = null): int|self
    {
        if ($times !== null)
        {
            $this->usedInPlace[$word->word()] = $times;

            return $this;
        }

        else return $this->usedInPlace[$word->word()] ?? 0;
    }

    public function incFollowedUsages (Word $word): self
    {
        $this->usedInPlace[$word->word()] = ($this->usedInPlace[$word->word()] ?? 0) + 1;

        return $this;
    }

    public function usageFrequency (Word $word): float
    {
        return ($this->usedInPlace[$word->word()] ?? 0) / $this->usedTimes;
    }

    public function entranceFrequency (): float
    {
        return $this->usedAsEntrance / $this->usedTimes;
    }

    public function follow (Word $word): self
    {
        $this->incUsedTimes ();
        $this->incFollowedUsages ($word);

        return $this;
    }

    public function follows (): array
    {
        return $this->usedInPlace;
    }

    public function entrances (): int
    {
        return $this->usedAsEntrance;
    }

    public function incEntranceUsage (): self
    {
        ++$this->usedAsEntrance;

        return $this;
    }

    public static function get (string $word): self
    {
        return file_exists ($file = __DIR__ .'/../stats.json') ?
            (isset (json_decode (file_get_contents ($file))->$word) ?
                (new self)->import (json_decode (file_get_contents ($file), true)[$word]) : new self) : new self;
    }

    public function import (array $info): self
    {
        $this->usedTimes      = $info['usages']   ?? 0;
        $this->usedAsEntrance = $info['entrance'] ?? 0;
        $this->usedInPlace    = $info['follows']  ?? [];

        return $this;
    }

    public function export (): array
    {
        return [
            'usages'   => $this->usedTimes,
            'entrance' => $this->usedAsEntrance,
            'follows'  => $this->usedInPlace
        ];
    }

    public function save (string $word): self
    {
        $stats = file_exists ($file = __DIR__ .'/../stats.json') ?
            json_decode (file_get_contents ($file), true) : [];
        
        $stats[$word] = $this->export ();

        file_put_contents ($file, json_encode ($stats, JSON_PRETTY_PRINT));

        return $this;
    }
}

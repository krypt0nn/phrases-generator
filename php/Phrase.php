<?php

namespace PhrasesGenerator;

class Phrase
{
    protected array $words = [];

    public function __construct (string $phrase = null, bool $onlyWords = false)
    {
        if ($phrase !== null)
            $this->words = array_values (array_map (
                fn ($word) => new Word ($onlyWords ? trim ($word, '.,;?!:-') : $word),
                array_filter (preg_split ('/\s/', $phrase),
                    fn ($s) => trim ($s) != '')));
    }

    public function words (): array
    {
        return $this->words;
    }

    public function append (Word $word): self
    {
        $this->words[] = $word;

        return $this;
    }

    public function phrase (): string
    {
        return join (' ', $this->words);
    }

    public function learn (): self
    {
        if (sizeof ($this->words) > 0)
            for ($i = 0; $i < sizeof ($this->words) - 1; ++$i)
            {
                $stats = WordStatistic::get ($this->words[$i]->word ())
                    ->follow ($this->words[$i + 1]);
                
                if ($i == 0)
                    $stats = $stats->incEntranceUsage ();

                $stats->save ($this->words[$i]->word ());
            }

                /*$this->words[$i]
                    ->stats ()
                    ->follow ($this->words[$i + 1])
                    ->save ($this->words[$i]->word ());*/

        return $this;
    }
}

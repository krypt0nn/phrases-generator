<?php

namespace PhrasesGenerator;

class Dictionary
{
    protected array $words = [];
    protected array $entranceWords = [];

    public function __construct ()
    {
        if (file_exists ($file = __DIR__ .'/../stats.json'))
            foreach (json_decode (file_get_contents ($file), true) as $word => $stats)
            {
                $this->words[] = new Word ($word, (new WordStatistic)->import ($stats));

                if (end ($this->words)->stats ()->entrances () > 0)
                    $this->entranceWords[] = end ($this->words);
            }
    }

    public function words (): array
    {
        return $this->words;
    }

    public function getRandomEntrance (): Word
    {
        $totalEntrances = 0;
        $entrancesInWords = [];

        foreach ($this->entranceWords as $word)
            $totalEntrances += ($entrancesInWords[$word->word ()] = $word->stats ()->entrances ());

        $entrance = rand (1, $totalEntrances);
        $shift = 0;

        foreach ($this->entranceWords as $word)
            if (($shift += $entrancesInWords[$word->word ()]) >= $entrance)
                return $word;
    }

    public function getRandomPhrase (): Phrase
    {
        $phrase = new Phrase;
        $word = $this->getRandomEntrance ();

        do
        {
            // print_r ($word);

            $phrase->append ($word);

            $word = $word->getRandomFollowed ();
        }

        while ($word !== null);

        return $phrase;
    }
}

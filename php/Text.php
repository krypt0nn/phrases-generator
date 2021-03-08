<?php

namespace PhrasesGenerator;

class Text
{
    protected array $phrases = [];

    public function __construct (string $text)
    {
        $this->phrases = array_map (fn ($phrase) => new Phrase ($phrase), array_filter (preg_split ('/[.;!?]/', $text)));
    }

    public function phrases (): array
    {
        return $this->phrases;
    }

    /*public function text (): string
    {
        return join ('. ', $this->phrases);
    }*/

    public function learn (): self
    {
        foreach ($this->phrases as $phrase)
            $phrase->learn ();

        return $this;
    }
}

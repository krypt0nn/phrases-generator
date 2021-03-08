<?php

namespace PhrasesGenerator;

require 'PhrasesGenerator.php';

$dictionary = new Dictionary;

echo $dictionary->getRandomPhrase ()->phrase ();

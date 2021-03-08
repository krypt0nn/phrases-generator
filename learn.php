<?php

namespace PhrasesGenerator;

require 'PhrasesGenerator.php';

$text = new Text (file_get_contents ('text.txt'));

$begin = microtime (true);
$text->learn ();
echo PHP_EOL .' Learned by '. round (microtime (true) - $begin, 6) .' sec'. PHP_EOL;

file_put_contents ('text.graph', print_r ($text->phrases (), true));

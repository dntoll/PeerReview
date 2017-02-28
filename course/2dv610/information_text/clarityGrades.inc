<?php

return array(
\model\Grading::NOT_GRADED => "Not graded yet",	
\model\Grading::NOT_AN_ATTEMPT => "0. Not even an attempt - Something else than the expected document, or not uploaded a .md file",
\model\Grading::FAILED  => "1. Failed - Text without headings or paragraphs, or NOT readable English",
\model\Grading::SUFFICIENT  => "2. Sufficient - Written in english, divided into chapters, subchapters with proper headings. It may have defects but it is readable.",
\model\Grading::GOOD => "3. Good - Written in good English with a few minor defects related to clarity. Easy to navigate, good titles and subtitles. Uses tables.",
\model\Grading::EXCELLENT => "4. Excellent - Very easy to read, very easy to navigate and find your way through, tables look great and have comments.");
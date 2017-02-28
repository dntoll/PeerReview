<?php

return array(
\model\Grading::NOT_GRADED => "Not graded yet",	
\model\Grading::NOT_AN_ATTEMPT => "0. Not even an attempt - None of the above questions are adressed.",
\model\Grading::FAILED  => "1. Failed - The group has not done the complete assignment. For example very little testing was done.",
\model\Grading::SUFFICIENT => "2. Sufficient - The test effort was ok. Testing is done and reported but not in the best way, or not so throrough.",
\model\Grading::GOOD => "3. Good - Ambitious test effort, nicely reported, covers multiple scenarios for each requirement.",
\model\Grading::EXCELLENT => "4. Excellent - Very good requirement coverage, several types of testing are conducted and reported in an Excellent way");
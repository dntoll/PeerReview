<?php

return array(
\model\Grading::NOT_GRADED => "Not graded yet",	
\model\Grading::NOT_AN_ATTEMPT => "0. Not even an attempt - This does not contain the four parts.",
\model\Grading::FAILED => "1. Failed - The document does completely miss one or more of the four chapters, or one of the chapters does not at all contain what it should.",
\model\Grading::SUFFICIENT => "2. Sufficient - All chapters are there and contains what it should but may have defects or is missing one or two of the questions mentioned.",
\model\Grading::GOOD => "3. Good - All chapters contain what they should, and answers the above questions with only some minor defects.",
\model\Grading::EXCELLENT => "4. Excellent - All chapters are very nicely represented and answer the questions in a good way.");
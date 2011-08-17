<?php

$t1 = microtime(true);
$m1 = memory_get_usage();

echo __METHOD__, "<br/>\n";

// Rendering time
$renderTime = microtime(true) - $t1;
echo 'Rendered in: ~', round($renderTime*1000,3), " ms <br/>\n";

// Used memory
$m2 = memory_get_usage();
echo 'Mem (start): ~', round($m1 / 1024, 1), " ko  <br/>\n";
echo 'Mem (end): ~', round($m2 / 1024, 1), " ko  <br/>\n";
echo 'Mem (used): ~', round(($m2 - $m1) / 1024, 1), " ko  <br/>\n";

?>
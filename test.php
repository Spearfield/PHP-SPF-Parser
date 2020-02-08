<pre>
<?php

require_once('./SPFParser.php');

$spf = new SPFParser();

$data = $spf->getAllRecords("stockholm.se");

print_r($data);

?>
</pre>

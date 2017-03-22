```php
<?php

//
// git clone https://github.com/breedhub/bhdir-lib-php5 bhdir
//

require_once('bhdir/directory.php');

$dir = new \bhdir\Directory();

$val = $dir->get('/test/foo');
if ($val === null)
    print("null\n");
else
    print($val . "\n");

$dir->set('/test/foo', 'new value');

$val = $dir->get('/test/foo');
if ($val === null)
    print("null\n");
else
    print($val . "\n");

$dir->delete('/test/foo');
```

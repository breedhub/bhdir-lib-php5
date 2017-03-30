```php
<?php

//
// git clone https://github.com/breedhub/bhdir-lib-php5 bhdir
//

require_once('bhdir/directory.php');

$dir = new \bhdir\Directory();

print("Set: " . $dir->set('/foo/bar', 'test') . "\n");
print("Get: " . $dir->get('/foo/bar') . "\n");

print("Set attr: " . $dir->set_attr('/foo/bar', 'custom', 123) . "\n");
print("Get attr: " . $dir->get_attr('/foo/bar', 'custom') . "\n");

// get all: $dir->get_attr('/foo/bar');
// delete: $dir->delete_attr('/foo/bar', 'custom');

print("LS: ");
print_r($dir->ls('/foo'));

$fd = fopen('/etc/shells', 'r');
if (!$fd)
    throw new Exception('Could not open file');
print("Upload: " . $dir->upload('/foo/bar', $fd) . "\n");
fclose($fd);

$fd = fopen('/tmp/test', 'w');
if (!$fd)
    throw new Exception('Could not open file');
$dir->download('/foo/bar', $fd);
fclose($fd);

// $dir->wait('/foo/bar');
// $dir->touch('/foo/bar');

// $dir->del_attr('/foo/bar', 'custom');
// $dir->delete('/foo/bar');
// $dir->rm('/foo/bar');
```

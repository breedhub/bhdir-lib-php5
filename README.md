```php
<?php

//
// git clone https://github.com/breedhub/bhdir-lib-php5 bhdir
//

require_once('bhdir/directory.php');

$dir = new \bhdir\Directory();

$dir->use_folder('sync');
$dir->cd('/');

print("Set: " . $dir->set('/foo/bar', 'test') . "\n");
print("Get: " . $dir->get('/foo/bar') . "\n");

print("Set attr: " . $dir->set_attr('/foo/bar', 'custom', 123) . "\n");
print("Get attr: " . $dir->get_attr('/foo/bar', 'custom') . "\n");

// get all: $dir->get_attr('/foo/bar');
// delete: $dir->delete_attr('/foo/bar', 'custom');

print("LS: ");
print_r($dir->ls('/foo'));
print("Exists: " . $dir->exists('/foo') . "\n");

$fd = fopen('/etc/shells', 'r');
if (!$fd)
    throw new Exception('Could not open file');
print("Upload by fd: " . $dir->put_fd($fd, '/foo/bar') . "\n");
fclose($fd);

print("Upload by path: " . $dir->put_file('/etc/shells', '/foo/bar') . "\n");

// get descriptor
$fd = $dir->get_fd('/foo/bar');
fclose($fd);

// get file
$dir->get_file('/foo/bar', '/tmp/test');

/// $dir->wait('/foo/bar');
// $dir->touch('/foo/bar');

// $dir->del_attr('/foo/bar', 'custom');
// $dir->delete('/foo/bar');
// $dir->rm('/foo/bar');
```

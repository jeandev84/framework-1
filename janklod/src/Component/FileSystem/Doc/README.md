# FILESYSTEM 

```
$file = new \Jan\Component\FileSystem\File(
    __DIR__.'/../database/migrations/m20210605_1816_users_table.php'
);

dump($file);
dump($file->getDirname());
dump($file->getBasename());
dump($file->getFilename());
dump($file->getExtension());

=======================================================================

$fs = new \Jan\Component\FileSystem\FileSystem(__DIR__.'/../');
$fs->make('temp/error.log');
$fs->mkdir('runtime');

$fileLocator = new \Jan\Component\FileSystem\FileLocator(__DIR__.'/../../');
echo $fileLocator->locate('test.php');
/* echo $fileLocator->realPath('test.php'); */

=========================================================================

$fileLoader  = new \Jan\Component\FileSystem\FileLoader(__DIR__.'/../');
$data = $fileLoader->load('test.php');
dump($data);

=========================================================================

$fs = new \Jan\Component\FileSystem\FileSystem(__DIR__.'/../');
$fs->remove('error.log');
```
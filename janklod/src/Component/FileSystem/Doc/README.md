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
```
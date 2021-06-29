<?php
namespace Jan\Component\Http\Bag;


use Jan\Component\Http\File\UploadedFile;

/**
 * Class FileBag
 * @package Jan\Component\Http\Bag
 */
class FileBag extends ParameterBag
{

    /**
     * FileBag constructor.
     * @param array $params
     */
    public function __construct(array $params = [])
    {
         $this->replace($params);
    }



    /**
     * @param array $files
     */
    public function replace(array $files = [])
    {
        $this->params = [];
        $this->add($files);
    }



    /**
     * @param array $files
    */
    public function add(array $files = [])
    {
        $files = $this->convertFiles($files);

        foreach ($files as $key => $file) {
            $this->set($key, $file);
        }
    }



    /**
     * @param string $key
     * @param $value
     * @return ParameterBag
    */
    public function set(string $key, $value): ParameterBag
    {
        if (!\is_array($value) && ! $value instanceof UploadedFile) {
            throw new \InvalidArgumentException(
                'An uploaded file must be an array or an instance of UploadedFile.'
            );
        }

        return parent::set($key, $value);
    }



    /**
     * @param array $files
     * @return array
    */
    protected function convertFiles(array $files): array
    {
        $resolvedFiles = $this->transformInformationFiles($files);

        $uploadedFiles = [];

        foreach ($resolvedFiles as $name => $items) {
            foreach ($items as $file) {
                if ($file['error'] === UPLOAD_ERR_NO_FILE) {
                    return [];
                }

                $uploadedFiles[$name][] = new UploadedFile(
                    $file['name'],
                    $file['type'],
                    $file['tmp_name'],
                    $file['error'],
                    $file['size']
                );
            }
        }

        return $uploadedFiles;
    }


    /**
     * @param array $files
     * @return array
     */
    protected function transformInformationFiles(array $files): array
    {
        $fileItems = [];

        foreach ($files as $name => $fileArray) {
            if (is_array($fileArray['name'])) {
                foreach ($fileArray as $attribute => $list) {
                    foreach ($list as $index => $value) {
                        $fileItems[$name][$index][$attribute] = $value;
                    }
                }
            }else{
                $fileItems[$name][] = $fileArray;
            }
        }

        return $fileItems;
    }
}

/*
https://www.php.net/manual/fr/reserved.variables.files.php
function multiple(array $_files, $top = TRUE)
{
    $files = array();
    foreach($_files as $name=>$file){
        if($top) $sub_name = $file['name'];
        else    $sub_name = $name;
        if(is_array($sub_name)){
            foreach(array_keys($sub_name) as $key){
                $files[$name][$key] = array(
                    'name'     => $file['name'][$key],
                    'type'     => $file['type'][$key],
                    'tmp_name' => $file['tmp_name'][$key],
                    'error'    => $file['error'][$key],
                    'size'     => $file['size'][$key],
                );
                $files[$name] = multiple($files[$name], FALSE);
            }
        }else{
            $files[$name] = $file;
        }
    }
    return $files;
}
print_r($_FILES);
Array
(
    [image] => Array
        (
            [name] => Array
                (
                    [0] => 400.png
                )
            [type] => Array
                (
                    [0] => image/png
                )
            [tmp_name] => Array
                (
                    [0] => /tmp/php5Wx0aJ
                )
            [error] => Array
                (
                    [0] => 0
                )
            [size] => Array
                (
                    [0] => 15726
                )
        )
)
$files = multiple($_FILES);
print_r($files);
Array
(
    [image] => Array
        (
            [0] => Array
                (
                    [name] => 400.png
                    [type] => image/png
                    [tmp_name] => /tmp/php5Wx0aJ
                    [error] => 0
                    [size] => 15726
                )
        )
)
*/
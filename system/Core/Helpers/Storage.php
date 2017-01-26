<?php
namespace MicroPos\Core\Helpers;


use MicroPos\Core\Exception\FileNotFoundException;

class Storage
{

    protected static $uploadedFile = "";
    /**
     * Gets a file from $_FILES superglobal
     * @param $file
     * @return null
     */
    protected static function get($file)
    {
        if (array_key_exists($file, $_FILES)) {
            return $_FILES[$file];
        }

        return null;
    }

    public static function getFileUrl($filename)
    {
        if(file_exists(avatarDir() . "/" . $filename)) {
            return baseUrl() . "/assets/images/uploads/avatars/" . $filename;
        } elseif (file_exists(uploadDir() . "/" . $filename)) {
            return baseUrl() . "/assets/uploads/" . $filename;
        }
        throw new FileNotFoundException("File was not found");
    }

    public static function getUploadedFilename()
    {
        return static::$uploadedFile;
    }

    public static function put($filename, $name = null, $dir = null)
    {
        if (is_null($filename) || empty($filename)) {
            throw new \InvalidArgumentException("The name of the uploaded file is required");
        }

        $file = static::get($filename);

        if (is_null($name) || empty($name)) {
            $name = "upload_".date('d-m-Y').uniqid("_").".".static::getExtension($file['name']);
        }

        if (is_null($dir) || empty($dir)) {
            $dir = uploadDir()."/";
        }

        if (!static::exists($file)) {
            throw new FileNotFoundException("File was not found");
        }

        if (move_uploaded_file($file['tmp_name'], $dir.$name)) {
            static::$uploadedFile = $name;
            return true;
        } else {
            return false;
        }
    }

    /**
     * Checks if a file has been uploaded
     * @param $file
     * @return bool
     * @throws \InvalidArgumentException
     */
    protected static function exists($file)
    {
        if (is_null($file)) {
            throw new \InvalidArgumentException("The given file is invalid");
        }

        return is_uploaded_file($file['tmp_name']);
    }


    public static function valid($file, array $extensions, $size = null)
    {
        $file = static::get($file);

        if (is_null($file)) {
            throw new FileNotFoundException("The given file doesn't exist.");
        }

        if (is_null($size)) {
            $size = static::convertFromMegabytes(1024);
        } else {
            $size = (int)$size;

            $size = static::convertFromMegabytes($size);
        }

        if (in_array(end(explode(".", $file['name'])), $extensions)) {
            if ($file['size'] <= $size) {
                return true;
            } else {
                return false;
            }
        } else {
            return null;
        }
    }

    protected static function getExtension($filename)
    {
        $arr = explode('.', $filename);

        return end($arr);
    }

    /**
     * @param $size
     * @return mixed
     */
    protected static function convertFromMegabytes($size)
    {
        return $size * 1024 * 1024;
    }

}
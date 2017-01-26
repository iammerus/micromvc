<?php

    namespace MicroPos\Core\Helpers;

    use Illuminate\Support\Arr as Arry;
    /**
     * Class Arr
     *
     * @package \MicroPos\Core\Helpers
     */
    class Arr extends Arry
    {
        /**
         * Checks if a value exists in a multi-dimensional array
         * @param mixed $needle
         * @param array $haystack
         * @return boolean
         */
        public static function in_multiarray($needle, array $haystack)
        {
            $valueExists = false;
            if (in_array($needle, $haystack)) {
                $valueExists = true;
            } else {
                for ($i = 0; $i < sizeof($haystack); $i++) {
                    if (is_array($haystack[$i])) {
                        if (self::in_multiarray($needle, $haystack[$i])) {
                            $valueExists = true;
                            break;
                        }
                    }
                }
            }
            return $valueExists;
        }

        /**
         * Determine if the given key exists in the provided array.
         *
         * @param  array $array
         * @param  string|int $key
         * @return bool
         */
        public static function remove($array, $key)
        {
            unset($array[$key]);

            return $array;
        }


        /**
         * Searches a multidimensional array array for a given value and returns the corresponding key if successful
         * @param mixed $needle
         * @param mixed $haystack
         * @param mixed $index
         * @return boolean
         */
        public static function multiarray_key($needle, $haystack, $index = null)
        {
            $arrayIterator = new ArrIterator($haystack);
            $iterator = new RIterator($arrayIterator);

            while ($iterator->valid()) {
                if (((isset($index) AND ($iterator->key() == $index)) OR (!isset($index))) AND ($iterator->current() == $needle)) {
                    return $arrayIterator->key();
                }

                $iterator->next();
            }
            return false;
        }

    }

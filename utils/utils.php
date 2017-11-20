<?php

/**
 * Class Utils
 */
class Utils
{

    private static $instance;

    public static function getInstance()
    {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {

    }

    /**
     * Checks if the array contains any empty field
     * @fields {Array}
     * @return bool
     */
    function checkEmptyFields($fields)
    {
        foreach ($fields as $key => $value) {
            if ($value == "")
                return false;
        }

        return true;
    }
    /** search for the position of $selected in $array and returns it */
     function getSelectedOptionPosition($array, $selected)
    {
        //number of elemets
        $length = count($array);

        $position = null;

        for ($i = 0; $i < $length; $i++) {
            //saco el valor de cada elemento
            if ($array[$i]->getId() == $selected) {
                $position = $i;
            }

        }
        return $position;
    }

    /** move the selected opction to the first poition of the array */
    function moveUp($array, $selectedOption)
    {
        $index1 = Utils::getInstance()->getSelectedOptionPosition($array, $selectedOption);
        if ($index1 == 0) {
            return $array;
        } else {
            $index2 = 0;
            $value1 = $array[$index1];
            $value2 = $array[$index2];
            $array[$index1] = $value2;
            $array[$index2] = $value1;
            return $array;
        }
    }

    /** change quotes and other special characters of SQL from the elements in the Array, to avoid sql injection */
    function sanitizeArray($unsafeData)
    {
        $safeData = array();
        foreach ($unsafeData as $key => $value) {
            $safeData[$key] = filter_var($value, FILTER_SANITIZE_STRING);
        }
        return $safeData;
    }

    function validateDate($date){
        $d = DateTime::createFromFormat('d-m-Y', $date);
        return $d && $d->format('d-m-Y') == $date;
    }
}


<?php

class ContactsError extends Exception {
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class Contacts {
    private $mysqli;

    /**
     * MySQL connection config
     */
    private $mysql_url = "localhost";
    private $mysql_user = "root";
    private $mysql_pw = "root";
    private $mysql_db = "contacts";

    /**
     * Prepared Statement Queries
     */
    private $contactsByT9Query = "SELECT name, firstname, number FROM contacts WHERE (firstname_t9 LIKE ? OR name_t9 LIKE ?)";
    private $createContactQuery = "INSERT INTO contacts.contacts (name, name_t9, firstname, firstname_t9, number) VALUES (?, ?, ?, ?, ?)";

    /**
     * Simple map for availabe chars to the respective T9 Number
     */
    private $charToT9Map = array(
        'a' => '2',
        'ä' => '2',
        'b' => '2',
        'c' => '2',
        'd' => '3',
        'e' => '3',
        'f' => '3',
        'g' => '4',
        'h' => '4',
        'i' => '4',
        'j' => '5',
        'k' => '5',
        'l' => '5',
        'm' => '6',
        'n' => '6',
        'o' => '6',
        'ö' => '6',
        'p' => '7',
        'q' => '7',
        'r' => '7',
        's' => '7',
        'ß' => '7',
        't' => '8',
        'u' => '8',
        'ü' => '8',
        'v' => '8',
        'w' => '9',
        'x' => '9',
        'y' => '9',
        'z' => '9',
        ' ' => '0',
    );

    /**
     * Constructor that connects to the database
     */
    function __construct() {
        $this->mysqli = new mysqli($this->mysql_url, $this->mysql_user, $this->mysql_pw, $this->mysql_db);

        if (mysqli_connect_errno()) {
            throw new ContactsError("MySQL Connection Failed");
        }
    }

    /**
     * Destructor that closes database connection
     */
    function __destruct() {
        $this->mysqli->close();
    }

    /**
     * Function to search contacts by a T9 number
     * parameter 1 - $T9:   T9 number
     * returns  An array of contacts. A contact is an array with 3 elelemts
     *          name, firstname and number.
     */
    function getContactsByT9($T9) {
        if (!is_numeric($T9)) {
            throw new ContactsError("T9 search is not a number.");
        }

        $search = $T9."%";
        $stmt = $this->mysqli->prepare($this->contactsByT9Query);
        $stmt->bind_param("ss", $search, $search);
        $stmt->execute();
        $stmt->bind_result($name, $firstname, $number);

        $contacts = array();

        while ($stmt->fetch()) {
            $contacts[] = [ "name" => $name, "firstname" => $firstname, "number" => $number ];
        }

        $stmt->close();
        return $contacts;
    }

    /**
     * Function to convert a string to a T9 number
     * parameter 1 - $str:   A String
     * returns  A T9 number as String.
     */
    function stringToT9($str) {
        $array_keys = array_keys($this->charToT9Map);
        $char_array = str_split(strtolower($str));
        $str_T9 = "";

        foreach ($char_array as $char) {
            $str_T9 .= in_array($char, $array_keys) ? $this->charToT9Map[$char] : "0";
        }

        return $str_T9;
    }

    /**
     * Function to create a contact. T9 number representatives are generated
     * beforehand for faster searching.
     * parameter 1 - $name:         A String, the name
     * parameter 2 - $firstname:    A String, the first name
     * parameter 3 - $number:       A String, the phone number
     */
    function createContact($name, $firstname, $number) {
        if(empty($name) || empty($firstname) || empty($number)) {
            throw new ContactsError("Missing name, first name or phone number when creating contact.");
        }

        $name_T9 = $this->stringToT9($name);
        $firstname_T9 = $this->stringToT9($firstname);
        $stmt = $this->mysqli->prepare($this->createContactQuery);
        $stmt->bind_param("sssss", $name, $name_T9, $firstname, $firstname_T9, $number);
        $stmt->execute();
        $stmt->close();
    }

}

?>

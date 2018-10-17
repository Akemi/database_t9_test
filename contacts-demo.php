<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>


<?php

error_reporting(E_ALL|E_STRICT);

include 'contacts.php';


if(isset($_POST['createContact'])) {
    $name = $_POST['name'];
    $firstname = $_POST['firstname'];
    $phone = $_POST['phone'];

    try {
        $con = new Contacts();
        $con->createContact($name, $firstname, $phone);
        echo("Create contact: {$name} {$firstname} {$phone}<br>");
    } catch (ContactsError $e) {
        echo $e->getMessage();
    }
}

if (isset($_POST['searchContacts'])) {
    $search = $_POST['t9'];

    if(empty($search)) {
        echo("Couldn't search contacts, no serach term entered.<br>");
    } else {
        try {
            $con = new Contacts();
            $contacts = $con->getContactsByT9($search);

            if(empty($contacts)) {
                echo("Couldn't find any contacts.<br>");
            } else {
                echo("<table style=\"width:100%\">");
                echo("<tr><th>Name</th><th>First name</th> <th>Phone</th></tr>");

                foreach ($contacts as $contact) {
                    echo("<tr>");
                    echo("<td>".$contact["name"]."</td>");
                    echo("<td>".$contact["firstname"]."</td>");
                    echo("<td>".$contact["number"]."</td>");
                    echo("</tr>");
                }

                echo("</table>");
            }
        } catch (ContactsError $e) {
            echo $e->getMessage();
        }
    }
}

?>

<h1>Create Contact</h1>
<form action="" method="post">
    Name: <input type="text" name="name"><br>
    First name: <input type="text" name="firstname"><br>
    Phone: <input type="text" name="phone"><br>
    <input type="Submit" name="createContact" value="Create Contact">
</form>

<h1>Search Contacts</h1>
<form action="" method="post">
    T9: <input type="text" name="t9"><br>
    <input type="Submit" name="searchContacts" value="Search Contacts">
</form>

</body>
</html>

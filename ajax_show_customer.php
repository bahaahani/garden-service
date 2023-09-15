<?php
//Include functions
include('includes/functions.php');

?>



<?php

/****************Getting  report menu to ajax *******************/

//Collecting id from Ajax url

$id = $_GET['cid'];


//require database class files
require('includes/config.php');
try {
    $dbname = 'mysql:host=phpdb1.mysql.database.azure.com;dbname=servicesql;charset=utf8';
    $user = 'servicesystem';
    $pass = 'm96nABJhYMp7Qf';
    $ca_cert = 'DigiCertGlobalRootCA.crt.pem'; // Replace with the actual path to your CA certificate file

    $pdoOptions = [
        PDO::MYSQL_ATTR_SSL_CA => $ca_cert,
    ];

     $ca_cert = 'DigiCertGlobalRootCA.crt.pem';
        $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_SSL_CA => $ca_cert,
    ];
  $db = new PDO('mysql:host=phpdb1.mysql.database.azure.com;dbname=servicesql;charset=utf8', 'servicesystem', 'm96nABJhYMp7Qf',$options);    
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt_search = $pdo->prepare("SELECT id, name FROM service");
    $stmt_search->execute();
    $results = $stmt_search->fetchAll(PDO::FETCH_ASSOC);

    // Process the $results array here if needed...

    // The database connection will be automatically closed when the script finishes.
} catch (PDOException $ex) {
    echo "Error Occurred!";
    die($ex->getMessage());
}

//instatiating our database objects
$db = new config;


$db->query('SELECT * FROM user WHERE id=:id');


$db->bindValue(':id', $id, PDO::PARAM_INT);


$row = $db->fetchSingle();
$db->execute();

$db->query('SELECT * FROM booking WHERE id=:id');
$db->bindvalue(':id', $id, PDO::PARAM_INT);
$rows = $db->fetchSingle();
$db->execute();

//Display this result to ajax
if ($row) {

    echo '<div  class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr >
                                <th class="text-center">Name</th>
                                <th class="text-center">Email</th>
                                <th class="text-center" colspan="3">Spend Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center">
                                <td>' . $row['fullName'] . '</td>
                                <td>' . $row['email'] . '</td>
                                <td colspan="3">BHD ' . $row['spending'] . '</td>
                            </tr>
                            <tr>
                                <th class="text-center">Service Name</th>
                                <th class="text-center">Period</th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Payment</th>
                                <th class="text-center">price</th>
                            </tr>';
    if (!count($row_user_book) == 0) {

        foreach ($row_user_book as $usoh) {
            $stmt_srv_details->bindParam(':srvoh', $usoh['service']);
            $stmt_srv_details->execute();
            $conoh = $stmt_srv_details->fetchAll(PDO::FETCH_ASSOC)[0];

            echo '
            <tr class="text-center">
                                    <td>' . $conoh['name'] . '</td>
                                    <td>' . $usoh['period'] . '</td>
                                    <td>' . $usoh['date_time'] . '</td>
                                    <td>' . $usoh['payment'] . '</td>
                                    <td>' . $conoh['price'] . '</td>
                                </tr>';
        }
    } else {
        echo '<tr class="text-center">
                                    <td>  -  </td>
                                    <td>  -  </td>
                                    <td>  -  </td>
                                    <td>  -  </td>
                                    <td>  -  </td>
                                </tr>';
        echo '<tr class="text-center">
                                <td style="color:red;" colspan="5">This user has not yet made any bookings.</td>
                            </tr>';
    }
    echo '</tbody>
                    </table>
                </div>';
}
?>
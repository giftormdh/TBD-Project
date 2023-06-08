<?php
$dbHost = '127.0.0.1';
$dbName = 'tbdproject';
$dbUsername = 'giftormdh';
$dbPassword = 'Llplga10';

$config = [
    'dbHost' => $dbHost,
    'dbName' => $dbName,
    'dbUsername' => $dbUsername,
    'dbPassword' => $dbPassword,
];
try {
    $pdo = new PDO("pgsql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit();
}

function postgreQuery($queryString, $hasReturnValue=true) {
    global $pdo;
    try {
        $stmt = $pdo->prepare($queryString);
        $stmt->execute();
        return $hasReturnValue ? $stmt->fetchAll(PDO::FETCH_ASSOC) : true;
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}
?>
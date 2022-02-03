<?php
$id = $_GET['id'];
$db = new SQLite3('flashpoint.sqlite', SQLITE3_OPEN_READONLY);
$stmt = $db->prepare('SELECT * FROM game WHERE id = :id');
$stmt->bindValue(':id', $id);
$result = $stmt->execute();
$game = $result->fetchArray(SQLITE3_ASSOC);
if (!$game) {
    header('HTTP/1.1 404 Not Found');
    die('Game not found');
}

if (isset($_GET["getapps"])) {
    $stmt = $db->prepare('SELECT * FROM additional_app WHERE parentGameId = :id');
    $stmt->bindValue(':id', $id);
    $result = $stmt->execute();

    $apps = array();
    while($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $apps[] = $row;
    }
    $game['additionalApps'] = $apps;
}

header('Content-type: application/json');
echo json_encode($game);

?>

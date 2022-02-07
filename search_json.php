<?php
require 'common.php';

$extreme = isset($_POST['extreme']);
$q = $_POST['q'];
$by = $_POST['by'];
if (strlen($q) < 3) {
    die();
}

$db = new SQLite3('flashpoint.sqlite', SQLITE3_OPEN_READONLY);
switch ($by) {
    case 'host':
        $stmt = $db->prepare('SELECT * FROM game WHERE launchCommand LIKE :launch');
        $stmt->bindValue(':launch', "%$q%");
        break;
    case 'keywords':
        $keywords = explode(' ', $q);
        $condition = 'title LIKE ' . implode(' AND title LIKE ', array_fill(0, count($keywords), '?'));
        $stmt = $db->prepare("SELECT * FROM game WHERE $condition");
        foreach($keywords as $i => $keyword) {
            $stmt->bindValue(($i+1), "%$keyword%");
        }
        break;
    case 'uuid':
        $stmt = $db->prepare('SELECT * FROM game WHERE id LIKE :id');
        $stmt->bindValue(':id', "%$q%");
        break;
    default:
        $stmt = $db->prepare('SELECT * FROM game WHERE title LIKE :title OR alternateTitles LIKE :alternate');
        $stmt->bindValue(':title', "%$q%");
        $stmt->bindValue(':alternate', "%$q%");
        break;
}
$result = $stmt->execute();

$myJson = array();
while($game = $result->fetchArray(SQLITE3_ASSOC)) {
    $isExtreme = isExtreme($game, $nsfw);
    if($extreme || !$isExtreme) {
        $myJson[] = array(
            "id" => $game["id"],
            "title" => $game["title"],
            "platform" => $game["platform"],
            "legacy" => empty($game['activeDataId']),
            "isExtreme" => $isExtreme
        );
    }
}
header('Content-type: application/json');
echo json_encode($myJson);
?>
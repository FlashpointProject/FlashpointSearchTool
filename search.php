<?php
include 'common.php';

$q = $_POST['q'];
$by = $_POST['by'];
if (strlen($q) < 3) {
    die();
}

$extreme = isset($_POST['extreme']);
$db = new SQLite3('flashpoint.sqlite');
switch ($by) {
    case 'host':
        $stmt = $db->prepare("SELECT * FROM game WHERE launchCommand LIKE :launch");
        $stmt->bindValue(':launch', "http://$q/%");
        break;
    case 'keywords':
        $keywords = explode(' ', $q);
        $condition = 'title LIKE ' . implode(' AND title LIKE ', array_fill(0, count($keywords), '?'));
        $stmt = $db->prepare("SELECT * FROM game WHERE ($condition)");
        foreach($keywords as $i => $keyword) {
            $stmt->bindValue(($i+1), "%$keyword%");
        }
        break;
    default:
        $stmt = $db->prepare("SELECT * FROM game WHERE (title LIKE :title OR alternateTitles LIKE :alternate)");
        $stmt->bindValue(':title', "%$q%");
        $stmt->bindValue(':alternate', "%$q%");
        break;
}
$result = $stmt->execute();

for($i = 0; $game = $result->fetchArray(SQLITE3_ASSOC); ++$i)
{
	if(!$extreme && is_extreme($game, $nsfw))
	{
		continue;
	}
	
	echo '<div class="game">';
	echo '<a href="#" data-toggle="collapse" data-target="#game-' . $i . '">[' . $game['platform'] . "] " . $game['title'] . '</a>';
	echo is_extreme($game, $nsfw) ? ' 🔞' : '';
	echo empty($game['activeDataId']) ? '<span title="This game is in LEGACY format."> 📜</span>' : '';
	echo isset($_POST['fpurl']) ? '<a href="flashpoint://' . $game['id'] . '"> ↪️</a>' : '';
	echo '<div class="game-details collapse" id="game-' . $i . '" data-id="' . $game['id'] . '">Loading...</div>';
}
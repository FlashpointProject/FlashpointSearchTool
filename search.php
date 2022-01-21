<?php

$q = $_POST['q'];
$by = $_POST['by'];
if (strlen($q) < 3) {
    die();
}

$db = new SQLite3('flashpoint.sqlite');
switch ($by) {
    case 'host':
        $stmt = $db->prepare('SELECT * FROM game WHERE launchCommand LIKE :launch');
        $stmt->bindValue(':launch', "http://$q/%");
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
?>
<?php for($i = 0; $game = $result->fetchArray(SQLITE3_ASSOC); ++$i): ?>
    <div class="game">
        <a href="#" data-toggle="collapse" data-target="#game-<?php echo $i ?>"><?php echo "[{$game['platform']}] {$game['title']}" ?></a>
        <div class="game-details collapse" id="game-<?php echo $i ?>" data-id="<?php echo $game['id'] ?>">Loading...</div>
    </div>
<?php endfor ?>

<?php

$q = $_POST['q'];
$by = $_POST['by'];
if (strlen($q) < 3) {
    die();
}

$extreme = isset($_POST['extreme']) ? '' : ' AND EXTREME = 0';
$db = new SQLite3('flashpoint.sqlite');
switch ($by) {
    case 'host':
        $stmt = $db->prepare("SELECT * FROM game WHERE launchCommand LIKE :launch $extreme");
        $stmt->bindValue(':launch', "http://$q/%");
        break;
    case 'keywords':
        $keywords = explode(' ', $q);
        $condition = 'title LIKE ' . implode(' AND title LIKE ', array_fill(0, count($keywords), '?'));
        $stmt = $db->prepare("SELECT * FROM game WHERE ($condition) $extreme");
        foreach($keywords as $i => $keyword) {
            $stmt->bindValue(($i+1), "%$keyword%");
        }
        break;
    default:
        $stmt = $db->prepare("SELECT * FROM game WHERE (title LIKE :title OR alternateTitles LIKE :alternate) $extreme");
        $stmt->bindValue(':title', "%$q%");
        $stmt->bindValue(':alternate', "%$q%");
        break;
}
$result = $stmt->execute();
?>
<?php for($i = 0; $game = $result->fetchArray(SQLITE3_ASSOC); ++$i): ?>
    <div class="game">
        <a href="#" data-toggle="collapse" data-target="#game-<?php echo $i ?>"><?php echo "[{$game[platform]}] {$game[title]}" ?></a>
<?php if(isset($_POST['fpurl'])): ?>
		<a href="<?php echo "flashpoint://{$game[id]}" ?>"><i class="las la-external-link-alt"></i></a>
<?php endif ?>
        <div class="game-details collapse" id="game-<?php echo $i ?>" data-id="<?php echo $game['id'] ?>">Loading...</div>
    </div>
<?php endfor ?>

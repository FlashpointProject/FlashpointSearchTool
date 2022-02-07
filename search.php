<?php
require 'common.php';

$q = $_POST['q'];
$by = $_POST['by'];
if (strlen($q) < 3) {
    die();
}

$extreme = isset($_POST['extreme']);
$db = new SQLite3('flashpoint.sqlite', SQLITE3_OPEN_READONLY);
switch ($by) {
    case 'host':
        $stmt = $db->prepare("SELECT * FROM game WHERE launchCommand LIKE :launch");
        $stmt->bindValue(':launch', "%$q%");
        break;
    case 'keywords':
        $keywords = explode(' ', $q);
        $condition = 'title LIKE ' . implode(' AND title LIKE ', array_fill(0, count($keywords), '?'));
        $stmt = $db->prepare("SELECT * FROM game WHERE ($condition)");
        foreach($keywords as $i => $keyword) {
            $stmt->bindValue(($i+1), "%$keyword%");
        }
        break;
    case 'uuid':
        $stmt = $db->prepare('SELECT * FROM game WHERE id LIKE :id');
        $stmt->bindValue(':id', "%$q%");
        break;
    default:
        $stmt = $db->prepare("SELECT * FROM game WHERE (title LIKE :title OR alternateTitles LIKE :alternate)");
        $stmt->bindValue(':title', "%$q%");
        $stmt->bindValue(':alternate', "%$q%");
        break;
}
$result = $stmt->execute();
?>
<?php for($i = 0; $game = $result->fetchArray(SQLITE3_ASSOC); ++$i): ?>
<?php if(!$extreme && isExtreme($game, $nsfw)): continue; endif; ?>
    <div class="game">
        <a href="#" data-toggle="collapse" data-target="#game-<?php echo $i ?>"><?php echo "[$game[platform]] $game[title]" ?></a>
<?php if(isExtreme($game, $nsfw)): ?>
        <span title="This content is considered Not Safe For Work."><span class="badge badge-pill badge-danger">18+</span></span>
<?php endif ?>
<?php if(empty($game['activeDataId'])): ?>
        <span title="This game is in LEGACY format."><i class="las la-broom"></i></span>
<?php endif ?>
<?php if(isset($_POST['fpurl'])): ?>
        <a href="<?php echo "flashpoint://{$game[id]}" ?>"><i class="las la-external-link-alt"></i></a>
<?php endif ?>
        <div class="game-details collapse" id="game-<?php echo $i ?>" data-id="<?php echo $game['id'] ?>">Loading...</div>
    </div>
<?php endfor ?>

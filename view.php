<?php
require 'common.php';

$baseurl = 'http://infinity.unstable.life/Flashpoint/Data/Images';

$keys = [
    'id' => 'UUID',
    'alternateTitles' => 'Alternate Titles',
    'series' => 'Series',
    'developer' => 'Developer',
    'publisher' => 'Publisher',
    'releaseDate' => 'Release Date',
    'language' => 'Languages',
    'applicationPath' => 'Application Path',
    'launchCommand' => 'Launch Command'
];

$id = $_GET['id'];
$db = new SQLite3('flashpoint.sqlite');
$stmt = $db->prepare('SELECT * FROM game WHERE id = :id');
$stmt->bindValue(':id', $id);
$result = $stmt->execute();
$game = $result->fetchArray(SQLITE3_ASSOC);
$blur = isExtreme($game, $nsfw);

if (!$game) {
    header('HTTP/1.1 404 Not Found');
    die('Game not found');
}

$a = substr($id, 0, 2);
$b = substr($id, 2, 2);
$img = "$a/$b/$id.png";
?>
<img class="thumb<?php echo $blur ? ' blur' : '' ?>" src="<?php echo "$baseurl/Logos/$img"; ?>" />
<img class="thumb<?php echo $blur ? ' blur' : '' ?>" src="<?php echo "$baseurl/Screenshots/$img"; ?>" />

<pre>
<?php
foreach($keys as $key => $name) {
    if ($game[$key]) {
        echo "$name: {$game[$key]}\n";
    }
}
?>
</pre>

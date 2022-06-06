<table class="table">
<tr>
    <th>Пользователь</th>
    <th>Рекорд</th>
</tr>

<?php
require_once('../db/db.php');
require_once('../utils/session.php');

$table = db\get_table('user');

usort($table, function ($a, $b) {
    return $b['record'] - $a['record'];
});

foreach ($table as $rec) {
?>
<tr class="<?php echo(($_SESSION['login'] === $rec['login'] ? 'highlight' : '')) ?>">
    <td><?php echo($rec['login'])?></td>
<?php 
if ($rec['record'] === null) {
    $rec['record'] = 0;
}
?>
    <td><?php echo($rec['record'])?></td>
</tr>
    <?php
}
?>
</table>

<table>
<tr>
    <th>Пользователь</th>
    <th>Рекорд</th>
</tr>

<?php
require_once('../db/db.php');

$table = db\get_table('user');

foreach ($table as $rec) {
?>
<tr>
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

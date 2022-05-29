<?php
assert(isset($form));
?>
<form id="<?php echo($form['id']); ?>">
    <h2><?php echo($form['header']);?>
    </h2>
    <?php
foreach ($form['fields'] as $id => $field) {
    $displayName = $field['displayName'];
    $type = $field['type'];
    require('form_elements/field.php');
}
?>
    <span id="general_error" class="gen-err"></span>
    <span id="general_info" class="gen-inf"></span>
    <?php
foreach ($form['buttons'] as $id => $button) {
    $displayName = $button['displayName'];
    require('form_elements/button.php');
}
foreach ($form['links'] as $id => $link) {
    $href = $link['href'];
    $displayName = $link['displayName'];
    require('form_elements/link.php');
}
?>
</form>
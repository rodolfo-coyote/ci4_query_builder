<!-- If csrf validation fails -->
<?= session()->getFlashdata('error') ?>
<!-- Form Validation provided by helper(form) -->
<?= validation_list_errors() ?>

<form action="/news" method="POST">
    <!-- csrf hidden input token -->
    <?= csrf_field() ?>

    <label for="title">Title</label>
    <input type="input" name="title" value="<?= set_value('title') ?>">
    <br>

    <label for="body">Text</label>
    <textarea name="body" cols="45" rows="4"><?= set_value('body') ?></textarea>
    <br>

    <input type="submit" name="submit" value="Create new article">
</form>
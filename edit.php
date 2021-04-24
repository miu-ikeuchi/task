<?php
require_once __DIR__ . '/functions.php';

$id = filter_input(INPUT_GET, 'id');

$task = findById($id);

$title = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = filter_input(INPUT_POST, 'title');

    $errors = updateValidate($title, $task);

    if (empty($errors)) {
        updateTask($id, $title);

        header('Location: index.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<?php include_once __DIR__ . '/_head.html' ?>

<body>
    <div class="wrapper">
        <h2>タスク更新</h2>
        <?php if ($errors) echo (createErrMsg($errors)) ?>
        <form action="" method="post">
            <input type="text" name="title" value="<?= h($task['title']) ?>">
            <input type="submit" value="更新" class="btn submit-btn">
        </form>
        <a href="index.php" class="btn return-btn">戻る</a>
    </div>
</body>

</html>
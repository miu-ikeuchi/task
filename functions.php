<?php

require_once __DIR__ . '/config.php';

function connectDb()
{
    try {
        return new PDO(
            DSN,
            USER,
            PASSWORD,
            [PDO::ATTR_ERRMODE =>
            PDO::ERRMODE_EXCEPTION]
            );
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit;
    }
}

function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
function insertValidate($title)
{
    $errors = [];

    if ($title === '') {
        $errors[] = MSG_TITLE_REQUIRED;
    }

    return $errors;
}
function insertTask($title)
{
    try {
        $dbh = connectDb();
        $sql = <<<EOM
        INSERT INTO
            tasks
            (title)
        VALUES
            (:title);
        EOM;
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function createErrMsg($errors)
{
    $err_msg = "<ul class=\"errors\">\n";

    foreach ($errors as $error) {
        $err_msg .= "<li>" . h($error) . "</li>\n";
    }

    $err_msg .= "</ul>\n";

    return $err_msg;
}
function updateStatusToDone($id)
{
    $dbh = connectDb();
    $sql = <<<EOM
    UPDATE
        tasks
    SET
        status = 'done'
    WHERE
        id = :id
    EOM;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
}

function findTaskByStatus($status)
{
    $dbh = connectDb();

    $sql = <<<EOM
    SELECT
        *
    FROM
        tasks
    WHERE
        status = :status;
    EOM;

    // プリペアドステートメントの準備
    $stmt = $dbh->prepare($sql);

    // パラメータのバインド
    $stmt->bindParam(':status', $status, PDO::PARAM_STR);

    // プリペアドステートメントの実行
    $stmt->execute();

    // 結果の取得
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function updateValidate($title, $task)
{
    $errors = [];

    if ($title == '') {
        $errors[] = MSG_TITLE_REQUIRED;
    }

    if ($title == $task['title']) {
        $errors[] = MSG_TITLE_NO_CHANGE;
    }

    return $errors;
}

function updateTask($id, $title)
{
    $dbh = connectDb();

    $sql = <<<EOM
    UPDATE
        tasks
    SET
        title = :title
    WHERE
        id = :id
    EOM;

    $stmt = $dbh->prepare($sql);

    $stmt->bindParam(':title', $title, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    $stmt->execute();
}

function deleteTask($id)
{
    $dbh =connectDb();

    $sql = <<<EOM
    DELETE FROM
        tasks
    WHERE
        id = :id
    EOM;

    $stmt = $dbh->prepare($sql);

    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    $stmt->execute();
}

function findById($id)
{
    $dbh = connectDb();

    $sql = <<<EMO
    SELECT
        *
    FROM
        tasks
    WHERE
        id = :id;
    EMO;

    $stmt = $dbh->prepare($sql);

    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}
<?php
require_once __DIR__ . '/functions.php';

$id = filter_input(INPUT_GET, 'id');

deleteTask($id);

header('Location: index.php');
exit;
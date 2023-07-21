<?php

require_once __DIR__ . '/../../utils.php';
require_once __DIR__ . '/../../controllers/reactions.php';
require_once __DIR__ . '/../../config.php';

$data = json_decode(file_get_contents('php://input'), true);
// Perform your data processing or other operations here (if needed)

// Send back the received data as a response
header('Content-Type: application/json');
echo json_encode($data);


if (!isset($_SESSION))
    session_start();

$postId = $_POST['postId'];
$type = $_POST['type'];
$result = Reaction::create($postId, $type);
var_dump($result);
redirect("/post.php?id=" . $postId);

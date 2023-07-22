<?php
if (!isset($_SESSION))
    session_start();

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../config.php';

$reactionTypes = array('like', 'haha', 'love', 'sad', 'angry');

class Reaction
{
    private $id;
    private $postId;
    private $userId;
    public $type;

    public function __construct($id, $postId, $userId, $type)
    {
        $this->id = $id;
        $this->postId = $postId;
        $this->userId = $userId;
        $this->type = $type;
    }

    static function create($postId, $type)
    {
        Auth::AuthOnly();
        $user = Auth::isAuth();
        $userId = $user->getId();
        $errors = [];
        $allowed_types = array('like', 'haha', 'love', 'sad', 'angry');

        # Validation
        if (empty($type))
            $errors['reaction'] = 'You must provide a reaction type';

        if (!in_array($type, $allowed_types))
            $errors['reaction'] = 'Incorrect reaction';

        if (count($errors)) {
            $_SESSION['errors'] = $errors;
            // redirect("/post.php?id=" . $postId);
            return null;
        }

        $query = "INSERT INTO reactions
                (`userId`, `postId`, `type`) 
                VALUES ('$userId', '$postId', '$type');";
        $result = db_exec_query($query, "INSERT");
        if (!$result)
            return null;
        $_SESSION["success"] = "Reaction Created Successfully";
        return true;
        // header("location: ../post.php?id=" . $postId);
    }

    static function getUserPostReaction($postId)
    {
        Auth::AuthOnly();
        $user = Auth::isAuth();
        $userId = $user->getId();
        // SQL query to fetch comments and their replies
        $query = "SELECT id, postId, userId, type FROM reactions WHERE postId =$postId and userId=$userId ORDER BY type;";
        $reactions = [];
        $result = db_exec_query($query, "SELECT");
        if (!$result->num_rows)
            return null;
        $row = $result->fetch_assoc();
        $reaction = new Reaction($row['id'], $row['postId'], $row['userId'], $row['type']);
        return $reaction;
    }

    static function getAllPostReactions($postId)
    {
        // SQL query to fetch comments and their replies
        $query = "SELECT id, postId, userId, type FROM reactions WHERE postId =$postId ORDER BY type;";
        $reactions = [];
        $result = db_exec_query($query, "SELECT");
        while ($row = $result->fetch_assoc()) {
            $reaction = new Reaction($row['id'], $row['postId'], $row['userId'], $row['type']);
            $reactions[$row['id']] = $reaction;
        }
        return $reactions;
    }

    static function update($id,  $type)
    {
        $allowed_types = array('like', 'haha', 'love', 'sad', 'angry');
        if (empty($id))
            return false;
        if (empty($type))
            return false;
        if (!in_array($type, $allowed_types))
            return false;
        $query = "UPDATE reactions SET `type` = '$type' WHERE (`id` = $id);";
        $result = db_exec_query($query, "UPDATE");
        if (!$result)
            return false;
        return true;
    }


    static function delete($id)
    {
        $query = "DELETE FROM reactions WHERE id = $id";
        $result = db_exec_query($query, "DELETE");
        if (!$result)
            return false;
        return true;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getPostId()
    {
        return $this->postId;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getType()
    {
        return $this->type;
    }
}

// $result = Reaction::getAllPostReactions(54);
// // $result = Comment::getAllPostComments(53);
// foreach ($result as $key => $reaction) {
//     print_r($reaction);
//     echo "<br>";
// }
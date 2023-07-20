<?php
if (!isset($_SESSION))
    session_start();

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../config.php';



class Comment
{
    private $id;
    private $postId;
    private $userId;
    public $content;
    public $updatedAt;
    public $username;
    public $avatar;
    public $replies = [];

    public function __construct($id, $postId, $userId, $content, $updatedAt, $username, $avatar)
    {
        if (empty($avatar))
            $avatar = "/assets/imgs/default-avatar.jpg";
        $this->id = $id;
        $this->postId = $postId;
        $this->userId = $userId;
        $this->content = $content;
        $this->updatedAt = $updatedAt;
        $this->username = $username;
        $this->avatar = $avatar;
        $this->replies = [];
    }

    static function create($postId, $content, $parentCommentId = null)
    {
        Auth::AuthOnly();
        $user = Auth::isAuth();
        $userId = $user->getId();
        $errors = [];

        # Validation
        if (empty($content))
            $errors['content'] = 'You must provide a content';

        if (count($errors)) {
            $_SESSION['errors'] = $errors;
            header("location: ../post.php?id=" . $postId);
            return null;
        }

        $escaped_content = addslashes($content);
        if (empty($parentCommentId))
            $query = " INSERT INTO comments 
                (`userId`, `postId`, `content`) 
                VALUES ('$userId', '$postId', '$escaped_content');";
        else
            $query = " INSERT INTO comments 
                (`userId`, `postId`, `content`, `parentId`) 
                VALUES ('$userId', '$postId', '$escaped_content', '$parentCommentId');";
        $result = db_exec_query($query, "INSERT");
        var_dump($result);
        if (!$result)
            return null;
        $_SESSION["success"] = "Comment Created Successfully";
        header("location: ../post.php?id=" . $postId);
    }

    static function getAllPostComments($postId)
    {
        // SQL query to fetch comments and their replies
        $query = "SELECT 
                        c.id,
                        c.postId,
                        c.userId,
                        c.parentId,
                        c.content,
                        c.updatedAt,
                        CONCAT(u.firstName, ' ', u.lastName) as username,
                        u.picture AS avatar
                    FROM
                        comments c
                            JOIN
                        users u ON u.id = c.userId
                    WHERE
                        c.postId = $postId
                    ORDER BY c.parentId;";
        $comments = [];
        $result = db_exec_query($query, "SELECT");
        while ($row = $result->fetch_assoc()) {
            $id =  $row['id'];
            $parentId =  $row['parentId'];
            $comment = new Comment($id, $row['postId'], $row['userId'], $row['content'], $row['updatedAt'], $row['username'], $row['avatar']);
            if ($parentId)
                $comments[$parentId]->replies[] = $comment;
            else
                $comments[$id] = $comment;
        }
        return $comments;
    }

    static function update($id,  $content)
    {
        if (empty($id))
            return null;

        $escaped_content = addslashes($content);
        $query = "UPDATE comments SET `content` = '$escaped_content' WHERE (`id` = $id);";
        $result = db_exec_query($query, "UPDATE");
        if (!$result)
            return false;
        return true;
    }


    static function delete($id)
    {
        $query = "DELETE FROM comments WHERE id = $id";
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

    public function getContent()
    {
        return $this->content;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function getReplies()
    {
        return $this->replies;
    }
}

// $result = Comment::create(51, "Test Reply 2", 21);
// $result = Comment::getAllPostComments(53);
// var_dump ($result);

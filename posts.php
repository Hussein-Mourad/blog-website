<?php
if (!isset($_SESSION))
    session_start();

require_once "auth.php";
require_once "db.php";
require_once "config.php";



class Post
{
    private $id;
    private $authorId;
    private $thumbnail;
    private $title;
    private $content;
    private $category;
    private $updatedAt;

    public function __construct($id, $authorId, $title, $content, $category,  $updatedAt, $thumbnail = null)
    {
        $this->id = $id;
        $this->authorId = $authorId;
        $this->title = $title;
        $this->content = $content;
        $this->category = $category;
        $this->updatedAt = $updatedAt;
        $this->thumbnail = $thumbnail;
    }

    static function create($title, $content, $category, $thumbnail = null)
    {
        Auth::AuthOnly();
        $user = Auth::isAuth();
        $authorId = $user->getId();
        $errors = [];
        # Validation
        if (empty($title))
            $errors['title'] = 'You must provide a title';
        if (empty($content))
            $errors['content'] = 'You must provide a content';
        if (empty($category))
            $errors['category'] = 'You must provide a category';
        if (empty($thumbnail))
            $thumbnail = null;

        if (count($errors)) {
            $_SESSION['errors'] = $errors;
            header("location: " . ADD_POST_PAGE);
            return null;
        }

        $query = " INSERT INTO posts 
                (`authorId`, `title`, `content`, `thumbnail`) 
                VALUES ('$authorId', '$title', '$content', '$thumbnail');";
        $result = db_exec_query($query, "INSERT");

        if (!$result)
            return null;

        $id = $result;
        $post = new Post($id, $authorId, $title, $content, $category, time(), $thumbnail);
        header("location: " . ADD_POST_PAGE);
        return $post;
    }

    static function getPosts()
    {
        $query = "SELECT
                    p.id,
                    p.title,
                    p.content,
                    u.picture,
                    concat(u.firstName, ' ', u.lastName) as author,
                    p.updatedAt,
                    c.id as category_id,
                    c.name as category
                FROM posts p
                JOIN users u ON u.id = p.authorId
                JOIN  posts_categories pc ON p.id = pc.postId
                JOIN categories c ON pc.categoryId = c.id
                ORDER BY c.id;";
        $result = db_exec_query($query, "SELECT");
        var_dump($result);

        if (!$result)
            return null;

        $id = $result;
        $post = new Post($id, $authorId, $title, $content, time());
        header("location: " . ADD_POST_PAGE);
        return $post;
    }

    static function update($title, $content, $thumbnail = null)
    {
        // TODO: update;
    }

    static function delete($title, $content, $thumbnail = null)
    {
        // TODO: CREATE;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthorId()
    {
        return $this->authorId;
    }

    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }


    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;
        return $this;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}

Post::getPosts();
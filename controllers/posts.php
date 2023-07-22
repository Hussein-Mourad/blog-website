<?php
if (!isset($_SESSION))
    session_start();

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../config.php';



class Post
{
    public $id;
    public $authorId;
    public $author;
    public $authorAvatar;
    public $thumbnail;
    public $title;
    public $content;
    public $categoryId;
    public $category;
    public $createdAt;
    public $updatedAt;

    public function __construct($id, $title, $content, $category, $updatedAt, $thumbnail = null)
    {
        if (empty($thumbnail))
            $thumbnail = "/assets/imgs/default_image.png";
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->category = $category;
        $this->updatedAt = $updatedAt;
        $this->thumbnail = $thumbnail;
    }

    static function create($title, $content, $categoryId, $thumbnail = null)
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
        if (empty($categoryId))
            $errors['category'] = 'You must provide a category';
        if (empty($thumbnail))
            $thumbnail = null;

        if (count($errors)) {
            $_SESSION['errors'] = $errors;
            redirect("/createPost.php");
            return null;
        }

        $escaped_title = addslashes($title);
        $escaped_content = addslashes($content);

        // Insert Post
        $query = " INSERT INTO posts 
                (`authorId`, `title`, `content`, `thumbnail`) 
                VALUES ('$authorId', '$escaped_title', '$escaped_content', '$thumbnail');";
        $result = db_exec_query($query, "INSERT");
        if (!$result)
            return null;
        $postId = $result;

        // Associate Post with Category
        $query = "INSERT INTO posts_categories (`categoryId`, `postId`) VALUES ('$categoryId', '$postId');";
        $result = db_exec_query($query, "INSERT");
        if (!$result)
            return null;

        // Get Category name from id
        $query = "SELECT name FROM categories WHERE id = $categoryId;";
        $result = db_exec_query($query, "SELECT");
        if (!$result)
            return null;
        $result = $result->fetch_assoc();
        $category = $result['name'];
        $post = new Post($postId, $title, $content, $category, time(), $thumbnail);
        $post->authorId = $authorId;
        $_SESSION["post-success-msg"] = "Post Created Successfully";
        redirect("/createPost.php");
        return $post;
    }

    static function getAll()
    {
        $query = "SELECT
                    p.id,
                    p.title,
                    p.content,
                    p.thumbnail,
                    p.createdAt,
                    p.updatedAt,
                    u.picture,
                    concat(u.firstName, ' ', u.lastName) as author,
                    c.id as category_id,
                    c.name as category
                FROM posts p
                JOIN users u ON u.id = p.authorId
                JOIN  posts_categories pc ON p.id = pc.postId
                JOIN categories c ON pc.categoryId = c.id
                ORDER BY c.id;";
        $result = db_exec_query($query, "SELECT");
        if (!$result)
            return [];
        if (!$result->num_rows)
            return [];
        $posts = [];
        while ($row = $result->fetch_assoc()) {
            $post =  new Post($row['id'], $row['title'], $row['content'], $row['category'], $row['updatedAt'], $row['thumbnail']);
            $post->setAuthor($row['author']);
            $post->setAuthorAvatar($row['picture']);
            $post->setCategoryId($row['category_id']);
            $post->setCreatedAt($row['createdAt']);
            $posts[$row['id']] = $post;
        }
        return  $posts;
    }

    static function get($id)
    {
        $query = "SELECT
                    p.id,
                    p.authorId,
                    p.title,
                    p.content,
                    p.thumbnail,
                    p.createdAt,
                    p.updatedAt,
                    u.picture,
                    concat(u.firstName, ' ', u.lastName) as author,
                    c.id as category_id,
                    c.name as category
                FROM posts p
                JOIN users u ON u.id = p.authorId
                JOIN  posts_categories pc ON p.id = pc.postId
                JOIN categories c ON pc.categoryId = c.id
                WHERE p.id = $id
                ORDER BY c.id;";
        $result = db_exec_query($query, "SELECT");
        if (!$result)
            return null;
        if (!$result->num_rows)
            return null;
        $row = $result->fetch_assoc();
        $post =  new Post($row['id'], $row['title'], $row['content'], $row['category'], $row['updatedAt'], $row['thumbnail']);
        $post->setAuthor($row['author']);
        $post->setAuthorAvatar($row['picture']);
        $post->setCategoryId($row['category_id']);
        $post->setCreatedAt($row['createdAt']);
        return  $post;
    }

    static function update($id, $title = null, $content = null, $thumbnail = null)
    {
        $updateString = '';
        if (empty($id))
            return null;
        if ($title) {
            $escaped_title = addslashes($title);
            $updateString .= "`title` = '$escaped_title'";
        }
        if ($content) {
            $escaped_content = addslashes($content);
            $updateString .= ", `content` = '$escaped_content'";
        }
        if ($thumbnail)
            $updateString .= ", `thumbnail` = '$thumbnail'";
        $query = "UPDATE posts SET $updateString WHERE (`id` = $id);";
        $result = db_exec_query($query, "UPDATE");
        if (!$result)
            return false;
        return true;
    }

    static function delete($id)
    {
        $query = "DELETE FROM posts WHERE id = $id";
        $result = db_exec_query($query, "DELETE");
        if (!$result)
            return false;
        return true;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getAuthorId()
    {
        return $this->authorId;
    }

    public function setAuthorId($authorId)
    {
        $this->authorId = $authorId;

        return $this;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    public function getAuthorAvatar()
    {
        return $this->authorAvatar;
    }

    public function setAuthorAvatar($authorAvatar)
    {
        if (empty($authorAvatar))
            $authorAvatar = "/assets/imgs/default-avatar.jpg";
        $this->authorAvatar = $authorAvatar;

        return $this;
    }

    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    public function setThumbnail($thumbnail)
    {

        if (empty($thumbnail))
            $thumbnail = "/assets/imgs/default_image.png";
        $this->thumbnail = $thumbnail;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    public function getCategoryId()
    {
        return $this->categoryId;
    }

    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}

// $data = Post::create("Test Blog", "Dummy", "dummy");
// var_dump($data);
// Post::delete(12);
// Post::update(15, "Test", "Testdfjdlkfj", "dlkfjdlkfj/dfjklj");

// $posts = Post::getAll();
// foreach ($posts as $id => $post) {
//     print_r($post);
//     echo "<br>";
// }

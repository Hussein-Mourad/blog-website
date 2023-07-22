<?php
if (!isset($_SESSION))
    session_start();

require_once __DIR__. '/auth.php';
require_once __DIR__. '/../db.php';
require_once __DIR__. '/../config.php';



class Category
{
    private $id;
    private $postId;
    private $name;

    public function __construct($id, $postId = null, $name)
    {
        $this->id = $id;
        $this->postId = $postId;
        $this->name = $name;
    }

    static function create()
    {
        // TODO: Create category  
    }

    static function getAllCategories()
    {
        $query = "SELECT id, name from categories";
        $result = db_exec_query($query, "SELECT");
        if (!$result)
            return null;
        if (!$result->num_rows)
            return null;
        return  $result->fetch_all();
    }
}

// $data = Post::create("Test Blog", "Dummy", "dummy");
// var_dump($data);
// Post::delete(12);
// Post::update(15, "Test", "Testdfjdlkfj", "dlkfjdlkfj/dfjklj");
// $result = Category::getAllCategories();

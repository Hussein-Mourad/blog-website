SELECT posts.id, posts.title, posts.content, posts.createdAt, comments.id, comments.content AS CommentContent, comments.createdAt, users.firstName, comments.parentId
FROM posts
JOIN comments ON comments.postId = posts.id
JOIN users ON comments.userId = users.id
WHERE posts.id = 1;

SELECT p.id, p.title, c.id, c.postId, c.parentId, c.content
FROM posts p
JOIN comments c ON p.id = c.postId
ORDER BY p.id, c.id;

SELECT p.id, p.title, c.id, c.postId, c.parentId, c.content
FROM posts p
JOIN comments c ON p.id = c.postId
WHERE p.id = 1 
ORDER BY p.id, c.id;

SELECT p.id, p.title, c.id as comment_id, c.parentId, c.content, r.reply_id, r.parentId, r.reply_content
FROM posts p
JOIN comments c ON p.id = c.postId
JOIN (SELECT parentId, id as reply_id, content as reply_content
	FROM comments
	WHERE parentId IS NOT NULL) r 
ON c.id = r.parentId
ORDER BY p.id, c.id, r.reply_id;


select concat(users.firstName, ' ', users.lastName) as Name
from posts
join users on posts.authorId = users.id
where posts.id = 1;

select * from posts;

SELECT
    p.id,
    p.title,
    p.content,
    c.id as category_id,
    c.name as category,
    r.type as reaction,
    concat(u.firstName, ' ', u.lastName) as reacted_user,
    com.content as comment,
    concat(u2.firstName, ' ', u2.lastName) as commented_user
FROM
    posts p
JOIN
    posts_categories pc ON p.id = pc.postId
JOIN
    categories c ON pc.categoryId = c.id
JOIN
	reactions r  ON r.postId = p.id
JOIN
	users u on u.id = r.userId
JOIN
	comments com on com.postId = p.id
JOIN
	users u2 on u2.id = com.userId
WHERE
	p.id = 1;

-- Get All Post by category
SELECT
    p.id,
    p.title,
    p.content,
    concat(u.firstName, ' ', u.lastName) as author,
    p.updatedAt,
    c.id as category_id,
    c.name as category
FROM posts p
JOIN users u ON u.id = p.authorId
JOIN  posts_categories pc ON p.id = pc.postId
JOIN categories c ON pc.categoryId = c.id
ORDER BY c.id;

select * from categories;

 INSERT INTO `blogs`.`posts` 
 (`id`, `authorId`, `title`, `content`) 
 VALUES ('4', '2', 'Test Blog 4', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.');

select * from posts;
delete from posts where id =11;

INSERT INTO `blogs`.`posts_categories` (`categoryId`, `postId`) VALUES ('2', '3');


UPDATE `blogs`.`posts` SET `title` = 'Test Blog 3' WHERE (`id` = '14');

UPDATE `blogs`.`posts` SET `title` = 'Test Blog', `content` = 'Dummy text' WHERE (`id` = '14');


select name from categories where id =1 ;


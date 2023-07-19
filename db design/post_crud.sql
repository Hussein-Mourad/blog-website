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
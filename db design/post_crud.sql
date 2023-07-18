SELECT posts.id, posts.title, posts.content, posts.createdAt, comments.id, comments.content AS CommentContent, comments.createdAt, users.firstName
FROM posts
JOIN comments ON comments.postId = posts.id
JOIN users ON comments.userId = users.id
WHERE posts.id = 1;

select concat(users.firstName, ' ', users.lastName) as Name
from posts
join users on posts.authorId = users.id
where posts.id = 1;
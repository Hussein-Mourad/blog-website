SELECT * FROM blogs.comments;

WITH RECURSIVE comment_hierarchy AS (
  SELECT c.id, c.parentId, c.content, 0 AS level
  FROM comments c
  WHERE c.parentId IS NULL -- Fetch top-level comments
  UNION ALL
  SELECT c.id, c.parentId, c.content, ch.level + 1
  FROM comments c
  INNER JOIN comment_hierarchy ch ON c.parentId = ch.id
)
SELECT id, parentId, content, level
FROM comment_hierarchy
ORDER BY parentId;

SELECT c.id, c.parentId, c.content
FROM comments c
WHERE c.parentId IS NULL -- Fetch top-level comments
UNION ALL
SELECT c.id, c.parentId, c.content
FROM comments c
INNER JOIN comments c2 ON c.parentId = c2.id;


INSERT INTO `blogs`.`comments` (`id`, `postId`, `userId`, `content`, `createdAt`) VALUES ('4', '2', '1', 'Cool blog', '');
INSERT INTO `blogs`.`comments` (`id`, `postId`, `userId`, `parentId`, `content`) VALUES ('', '2', '1', '4', 'Sure');
INSERT INTO `blogs`.`comments` (`postId`, `userId`, `parentId`, `content`) VALUES ('2', '1', '4', 'Sure 2');
INSERT INTO `blogs`.`comments` (`postId`, `userId`, `parentId`, `content`) VALUES ('2', '1', '4', 'Sure 3');


# Working it can be made as a view
WITH RECURSIVE comment_hierarchy AS (
  SELECT c.postId, c.id, c.content, c.parentId, 0 AS level
  FROM comments c
  WHERE c.parentId IS NULL -- Fetch top-level comments
  UNION ALL
  SELECT c.postId, c.id, c.content, c.parentId, ch.level + 1
  FROM comments c
  INNER JOIN comment_hierarchy ch ON c.parentId = ch.id
)
SELECT p.id as post_id, p.title, p.content as post_content, ch.id as comment_id, ch.content as comment_content, ch.level
FROM posts p
LEFT JOIN comment_hierarchy ch ON p.id = ch.postId
ORDER BY p.id, ch.id;


SELECT 
    *
FROM
    comments c
        JOIN
    posts p ON p.id = c.postId
WHERE
    p.id = 51;
    
    
    
SELECT 
	c.postId,
    c.parentId,
    c.id AS comment_id,
    c.content AS comment_text,
    r.id AS reply_id,
    r.content AS reply_text
FROM
    comments c
        JOIN
    comments r ON c.id = r.parentId
WHERE c.postId = 53 and c.parentId IS NULL;

SELECT 
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
    c.postId = 53
ORDER BY c.parentId;










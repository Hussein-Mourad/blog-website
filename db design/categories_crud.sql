SELECT 
	p.id,
    p.title,
    p.content,
    concat(u.firstName, ' ', u.lastName) as author,
    p.updatedAt,
    c.id as category_id,
    c.name as category
FROM posts_categories pc
JOIN categories c on pc.categoryId = c.id
JOIN posts p ON pc.postId = p.id
JOIN users u on p.authorId = u.id;

select * from users;

select id, firstName, lastName, email, role, phone, picture
from users 
where id = 1;

INSERT INTO users 
(`firstName`, `lastName`, `email`, `password`, `phone`) 
VALUES ('hussein', 'mourad', 'husseinmourad@gmail.com', '123456','01005498578494'); 

UPDATE users
SET picture = '/mnt/d/test/test.png'
WHERE id = 1;

DELETE FROM users 
where id = 4 
limit 1 
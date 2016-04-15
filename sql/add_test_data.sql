-- Create two users
INSERT INTO Blogger (username, email, password) VALUES ('admin', 'admin@admin.com', 'admin');
INSERT INTO Blogger (username, email, password) VALUES ('sekakäyttäjä', 'user@mail.com', 'salasana');
INSERT INTO Blogger (username, email, password) VALUES ('trolli', 'trolli@mail.com', 'password');

-- Grant admin rights for the first user
INSERT INTO Admin (userId) VALUES (1);

-- Create blog and set the owner
INSERT INTO Blog (name, description) VALUES ('Gurula kotina', 'Kertomuksia elämästä Gurulassa');
INSERT INTO Blog (name, description) VALUES ('Ensimmäinen blogini', 'Tarinoita koulusta');
INSERT INTO Blog (name, description) VALUES ('SanomatBlogi', 'Uutisia');

INSERT INTO BlogOwner (userId, blogId) VALUES (1, 1);
INSERT INTO BlogOwner (userId, blogId) VALUES (1, 2);
INSERT INTO BlogOwner (userId, blogId) VALUES (2, 3);

-- Create a post for the blog
INSERT INTO BlogPost (blogId, author, title, content) VALUES (1, 1, 'Otsikko', 'Kirjoituksen sisältö');
INSERT INTO BlogPost (blogId, author, title, content) VALUES (2, 2, 'Elämä', 'Kirjoituksen sisältö');
INSERT INTO BlogPost (blogId, author, title, content) VALUES (3, 3, 'Hyvä palvelu', 'Kirjoituksen sisältö');
INSERT INTO BlogPost (blogId, author, title, content) VALUES (2, 2, 'Loistavaa', 'Kirjoituksen sisältö');

-- Like the post
INSERT INTO Likes (userId, postId) VALUES (2, 1);
INSERT INTO Likes (userId, postId) VALUES (1, 1);
INSERT INTO Likes (userId, postId) VALUES (3, 2);

-- Make the non-admin user follow the other
INSERT INTO Follows (follower, followee) VALUES (2, 1);
INSERT INTO Follows (follower, followee) VALUES (1, 2);
   
INSERT INTO Comment (postId, userId, content) VALUES(1, 3, 'trolli täällä');
INSERT INTO Comment (postId, userId, content) VALUES(1, 2, 'kävi täällä');


-- Create a few tags
INSERT INTO Tag (name) VALUES ('dull');
INSERT INTO Tag (name) VALUES ('boring');
INSERT INTO Tag (name) VALUES ('blunt');

-- Link tags to the blog (max 3 per BlogPost)
INSERT INTO TagCloud (postId, tagId) VALUES (1, 1);
INSERT INTO TagCloud (postId, tagId) VALUES (1, 2);
INSERT INTO TagCloud (postId, tagId) VALUES (1, 3);

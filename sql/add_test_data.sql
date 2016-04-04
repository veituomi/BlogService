-- Create two users
INSERT INTO Blogger (username, email, password) VALUES ('admin', 'admin@admin.com', 'admin');
INSERT INTO Blogger (username, email, password) VALUES ('sekakäyttäjä', 'user@mail.com', 'salasana');

-- Grant admin rights for the first user
INSERT INTO Admin (userId) VALUES (1);

-- Create blog and set the owner
INSERT INTO Blog (name, description) VALUES ('Gurula kotina', 'Kertomuksia elämästä Gurulassa');
INSERT INTO BlogOwner (userId, blogId) VALUES (1, 1);

-- Create a post for the blog
INSERT INTO BlogPost (blogId, title, content) VALUES (1, 'Otsikko', 'Kirjoituksen sisältö');

-- Like the post
INSERT INTO Likes (userId, postId) VALUES (2, 1);

-- Make the non-admin user follow the other
INSERT INTO Follows (follower, followee) VALUES (2, 1);

-- Create a few tags
INSERT INTO Tag (name) VALUES ('dull');
INSERT INTO Tag (name) VALUES ('boring');
INSERT INTO Tag (name) VALUES ('blunt');

-- Link tags to the blog
INSERT INTO TagCloud (blogId, tagId) VALUES (1, 1);
INSERT INTO TagCloud (blogId, tagId) VALUES (1, 2);

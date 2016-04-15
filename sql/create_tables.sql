CREATE TABLE Blogger (
	user_id serial PRIMARY KEY,
	username text NOT NULL UNIQUE ,
	email text NOT NULL UNIQUE ,
	password text NOT NULL,
	join_date timestamp DEFAULT now(),
	profile_description text
);

CREATE TABLE Blog (
    blog_id serial PRIMARY KEY,
    name text NOT NULL,
    description text
);

CREATE TABLE Admin (
	user_id int REFERENCES Blogger(user_id)
);

CREATE TABLE BlogPost (
	post_id serial PRIMARY KEY,
	blog_id int REFERENCES Blog(blog_id),
    author int REFERENCES Blogger(user_id),
    title text NOT NULL,
	content text NOT NULL,
	date timestamp DEFAULT now()
);

CREATE TABLE Comment (
	comment_id serial PRIMARY KEY,
	post_id int REFERENCES BlogPost(post_id),
	user_id int REFERENCES Blogger(user_id),
	content text NOT NULL,
	date timestamp DEFAULT now()
);

CREATE TABLE Tag (
	tag_id serial PRIMARY KEY,
	name text NOT NULL
);

CREATE TABLE TagCloud (
	tag_id int REFERENCES Tag(tag_id),
	post_id int REFERENCES BlogPost(post_id)
);

CREATE TABLE BlogOwner (
	user_id int REFERENCES Blogger(user_id),
	blog_id int REFERENCES Blog(blog_id)
);

CREATE TABLE Likes (
    user_id int REFERENCES Blogger(user_id),
	post_id int REFERENCES BlogPost(post_id)
);

CREATE TABLE Follows (
	follower int REFERENCES Blogger(user_id),
	followee int REFERENCES Blogger(user_id)
);
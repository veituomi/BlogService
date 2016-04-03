CREATE TABLE Blogger (
	userId serial PRIMARY KEY,
	username text NOT NULL UNIQUE ,
	email text NOT NULL UNIQUE ,
	password text NOT NULL,
	joinDate timestamp DEFAULT now(),
	profileDescription text
);

CREATE TABLE Blog (
    blogId serial PRIMARY KEY,
    name text NOT NULL,
    description text
);

CREATE TABLE Admin (
	userId int REFERENCES Blogger(userId)
);

CREATE TABLE BlogPost (
	postId serial PRIMARY KEY,
	blogId int REFERENCES Blog(blogId),
	content text NOT NULL,
	date timestamp DEFAULT now()
);

CREATE TABLE Comment (
	commentId serial PRIMARY KEY,
	postId int REFERENCES BlogPost(postId),
	userId int REFERENCES Blogger(userId),
	content text NOT NULL,
	date timestamp DEFAULT now()
);

CREATE TABLE Tag (
	tagId serial PRIMARY KEY,
	name text NOT NULL
);

CREATE TABLE TagCloud (
	tagId int REFERENCES Tag(tagId),
	blogId int REFERENCES Blog(blogId)
);

CREATE TABLE BlogOwner (
	userId int REFERENCES Blogger(userId),
	blogId int REFERENCES Blog(blogId)
);

CREATE TABLE Likes (
userId int REFERENCES Blogger(userId),
	postId int REFERENCES BlogPost(postId)
);

CREATE TABLE Follows (
	follower int REFERENCES Blogger(userId),
	followee int REFERENCES Blogger(userId)
);
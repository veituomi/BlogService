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
	userId int REFERENCES Blogger(userId) ON DELETE CASCADE
);

CREATE TABLE BlogPost (
	postId serial PRIMARY KEY,
	blogId int REFERENCES Blog(blogId) ON DELETE CASCADE,
    author int REFERENCES Blogger(userId) ON DELETE CASCADE,
    title text NOT NULL,
	content text NOT NULL,
	date timestamp DEFAULT now()
);

CREATE TABLE Comment (
	commentId serial PRIMARY KEY,
	postId int REFERENCES BlogPost(postId) ON DELETE CASCADE,
	userId int REFERENCES Blogger(userId) ON DELETE CASCADE,
	content text NOT NULL,
	date timestamp DEFAULT now()
);

CREATE TABLE Tag (
	tagId serial PRIMARY KEY,
	name text NOT NULL
);

CREATE TABLE TagCloud (
	tagId int REFERENCES Tag(tagId) ON DELETE CASCADE,
	postId int REFERENCES BlogPost(postId) ON DELETE CASCADE
);

CREATE TABLE BlogOwner (
	userId int REFERENCES Blogger(userId) ON DELETE CASCADE,
	blogId int REFERENCES Blog(blogId) ON DELETE CASCADE
);

CREATE TABLE Likes (
    userId int REFERENCES Blogger(userId) ON DELETE CASCADE,
	postId int REFERENCES BlogPost(postId) ON DELETE CASCADE
);

CREATE TABLE Follows (
	follower int REFERENCES Blogger(userId) ON DELETE CASCADE,
	followee int REFERENCES Blogger(userId) ON DELETE CASCADE
);
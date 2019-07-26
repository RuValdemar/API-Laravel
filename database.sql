CREATE DATABASE IF NOT EXISTS apiguitars;

USE apiguitars;

CREATE TABLE users(
	id 			int(255) auto_increment not null,
	role		varchar(20),
	name		varchar(255),
	surname		varchar(255),
	email		varchar(255),
	password 	varchar(255),
	created_at	datetime DEFAULT NULL,
	updated_at	datetime DEFAULT NULL,
	remember_token	varchar(255),

	CONSTRAINT pk_users PRIMARY KEY(id)
)ENGINE=InnoDb;

CREATE TABLE brands(
	id 			int(255) auto_increment not null,
	name		varchar(255),
	history		varchar(255),
	created_at	datetime DEFAULT NULL,
	updated_at	datetime DEFAULT NULL,

	CONSTRAINT pk_brands PRIMARY KEY(id)	

)ENGINE=InnoDb;

CREATE TABLE guitars(
	id 			int(255) auto_increment not null,
	user_id		int(255) not null,
	brand_id	int(255) not null,	
	model		varchar(255),
	type		varchar(50),
	cords		varchar(10),
	price		float(10),
	description	varchar(255),
	created_at	datetime DEFAULT NULL,
	updated_at	datetime DEFAULT NULL,

	CONSTRAINT pk_guitars PRIMARY KEY(id),
	CONSTRAINT fk_guitars_users FOREIGN KEY(user_id) REFERENCES users(id),
	CONSTRAINT fk_guitars_brands FOREIGN KEY(brand_id) REFERENCES brands(id)

)ENGINE=InnoDb;
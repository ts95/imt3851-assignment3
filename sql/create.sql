CREATE TABLE user (
    id INTEGER NOT NULL AUTO_INCREMENT,
    email VARCHAR(30) NOT NULL,
    full_name VARCHAR(20) NOT NULL,
    password VARCHAR(60) NOT NULL,
    admin BOOLEAN NOT NULL DEFAULT false,
    PRIMARY KEY (id, email)
);

CREATE VIEW safe_user AS
SELECT id, email, full_name, admin
FROM user;

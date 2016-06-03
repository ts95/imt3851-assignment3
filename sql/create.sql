CREATE TABLE user (
    id INTEGER NOT NULL AUTO_INCREMENT,
    email VARCHAR(30) NOT NULL,
    full_name VARCHAR(20) NOT NULL,
    password VARCHAR(60) NOT NULL,
    admin BOOLEAN NOT NULL DEFAULT false,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(id)
);

CREATE VIEW safe_user AS
SELECT id, email, full_name, admin, created_at, updated_at
FROM user;

CREATE TABLE category (
    id INTEGER NOT NULL AUTO_INCREMENT,
    name VARCHAR(30) NOT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE item (
    id INTEGER NOT NULL AUTO_INCREMENT,
    user_id INTEGER NOT NULL,
    category_id INTEGER NOT NULL,
    title VARCHAR(50) NOT NULL,
    description VARCHAR(5000) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(id),
    FOREIGN KEY(user_id) REFERENCES user(id),
    FOREIGN KEY(category_id) REFERENCES category(id)
);

CREATE TABLE item_image (
    id INTEGER NOT NULL AUTO_INCREMENT,
    item_id INTEGER NOT NULL,
    filename VARCHAR(255) NOT NULL,
    position INTEGER NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(item_id) REFERENCES item(id)
);

CREATE TABLE exchange (
    id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    PRIMARY KEY(id, user_id),
    FOREIGN KEY(user_id) REFERENCES user(id)
);

CREATE TABLE message (
    id INTEGER NOT NULL AUTO_INCREMENT,
    user_id INTEGER NOT NULL,
    exchange_id INTEGER NOT NULL,
    message VARCHAR(2000) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(id),
    FOREIGN KEY(user_id) REFERENCES user(id),
    FOREIGN KEY(exchange_id) REFERENCES exchange(id)
);

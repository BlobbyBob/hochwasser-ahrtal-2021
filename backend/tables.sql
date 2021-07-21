CREATE TABLE towns
(
    id    INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name  VARCHAR(64)  NOT NULL,
    route VARCHAR(64)  NOT NULL,
    x     FLOAT        NOT NULL,
    y     FLOAT        NOT NULL,
    label VARCHAR(8)   NOT NULL DEFAULT 'right'
);

CREATE TABLE media
(
    id        INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    town      INT UNSIGNED NOT NULL,
    title     VARCHAR(256) NOT NULL,
    timestamp DATETIME     NOT NULL,
    latitude  FLOAT        NOT NULL,
    longitude FLOAT        NOT NULL,
    type      VARCHAR(32)  NOT NULL,
    data      TEXT         NULL DEFAULT NULL,
    FOREIGN KEY `fk_media_towns` (`town`) REFERENCES `towns` (`id`) ON UPDATE CASCADE
);


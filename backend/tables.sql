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
    inserted  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    town      INT UNSIGNED NOT NULL,
    title     VARCHAR(256) NOT NULL,
    timestamp DATETIME     NOT NULL,
    latitude  FLOAT        NOT NULL,
    longitude FLOAT        NOT NULL,
    type      VARCHAR(32)  NOT NULL,
    data      TEXT         NULL     DEFAULT NULL,
    disabled  BOOL         NOT NULL DEFAULT FALSE,
    FOREIGN KEY `fk_media_towns` (`town`) REFERENCES `towns` (`id`) ON UPDATE CASCADE
);

CREATE TABLE contact
(
    id        INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    date      DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    name      VARCHAR(128) NOT NULL,
    email     VARCHAR(128) NOT NULL,
    request   TEXT         NOT NULL,
    latitude  FLOAT        NOT NULL,
    longitude FLOAT        NOT NULL,
    copyright VARCHAR(8)   NOT NULL,
    media     INT UNSIGNED NULL     DEFAULT NULL,
    FOREIGN KEY `fk_contact_media` (`media`) REFERENCES `media` (`id`) ON UPDATE CASCADE
);

CREATE TABLE complaints
(
    id      INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    date    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    name    VARCHAR(128) NOT NULL,
    email   VARCHAR(128) NOT NULL,
    request TEXT         NOT NULL,
    media   INT UNSIGNED NOT NULL,
    FOREIGN KEY `fk_complaints_media` (`media`) REFERENCES `media` (`id`) ON UPDATE CASCADE
);

CREATE TABLE personalMedia
(
    id      int(10) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    date    datetime         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    name    varchar(128)     NOT NULL,
    email   varchar(128)     NOT NULL,
    request text             NOT NULL
);

CREATE TABLE corrections
(
    id        INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    date      DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    media     INTEGER UNSIGNED NOT NULL,
    title     VARCHAR(256)     NOT NULL,
    latitude  FLOAT            NOT NULL,
    longitude FLOAT            NOT NULL,
    done      BOOLEAN          NOT NULL DEFAULT 0,
    FOREIGN KEY `fk_corrections_media` (`media`) REFERENCES `media` (`id`) ON UPDATE CASCADE
)
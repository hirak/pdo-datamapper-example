-- PDOex schema
PRAGMA foreign_keys = ON;

CREATE TABLE Entry (
    entryId   INTEGER PRIMARY KEY AUTOINCREMENT,
    author    TEXT NOT NULL,
    title     TEXT NOT NULL,
    content   TEXT NOT NULL,
    published TEXT NOT NULL
);

CREATE TABLE Tag (
    tag     TEXT NOT NULL,
    entryId INTEGER NOT NULL,

    PRIMARY KEY(tag, entryId),
    FOREIGN KEY(entryId) REFERENCES Entry(entryId) ON DELETE CASCADE
);


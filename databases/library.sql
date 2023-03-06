#==========USERS TABLE===========#
CREATE TABLE IF NOT EXISTS Users
(
    id                   int                          NOT NULL PRIMARY KEY,
    username             varchar(30)                  NOT NULL,
    name                 varchar(30)                  NOT NULL,
    las_name             varchar(30)                  NOT NULL,
    identity_card_number varchar(8)                   NOT NULL,
    birthday             date                         NOT NULL,
    type                 varchar(20)                  NOT NULL,
    phone_number         varchar(14)                  NOT NULL,
    email                varchar(256)                 NOT NULL,
    password             varchar(256)                 NOT NULL,
    creation_date        date       DEFAULT CURDATE() NOT NULL,
    tickets              int(1)     DEFAULT 0,
    role                 varchar(5) DEFAULT 'user',
    creator_id           int,
    FOREIGN KEY (creator_id) REFERENCES Users (id)
);
#==========ITEM TABLE===========#
CREATE TABLE IF NOT EXISTS Item
(
    id           int          NOT NULL PRIMARY KEY,
    title        varchar(100) NOT NULL,
    author       varchar(30)  NOT NULL,
    type         varchar(10)  NOT NULL,
    picture      varchar(256) NOT NULL,
    release_date date         NOT NULL,
    page_cout    int          NOT NULL
);
#==========ITEM_UNIT TABLE===========#
CREATE TABLE IF NOT EXISTS Item_unit
(
    id           int         NOT NULL PRIMARY KEY,
    status       varchar(30) NOT NULL,
    brought_date date        NOT NULL,
    item_id      int         NOT NULL,
    FOREIGN KEY (item_id) REFERENCES Item (id)
);
#==========RESERVATION TABLE===========#
CREATE TABLE IF NOT EXISTS Reservations
(
    id           int      NOT NULL PRIMARY KEY,
    opening_date datetime NOT NULL DEFAULT CURDATE(),
    user_id      int      NOT NULL,
    item_unit_id int      NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users (id),
    FOREIGN KEY (item_unit_id) REFERENCES Item_unit (id)
);
#==========RESERVATION TABLE===========#
CREATE TABLE IF NOT EXISTS Borrowings
(
    id              int      NOT NULL PRIMARY KEY,
    opening_date    datetime NOT NULL DEFAULT CURDATE(),
    closing_date    datetime NOT NULL,
    opening_user_id int      NOT NULL,
    closing_user_id int      NOT NULL,
    reservation_id  int      NOT NULL,
    FOREIGN KEY (opening_user_id) REFERENCES Users (id),
    FOREIGN KEY (closing_user_id) REFERENCES Users (id),
    FOREIGN KEY (reservation_id) REFERENCES Reservations (id)
);
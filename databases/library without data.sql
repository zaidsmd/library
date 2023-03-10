#==========USERS TABLE===========#
CREATE TABLE IF NOT EXISTS Users
(
    id                   int                          NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username             varchar(30)                  NOT NULL,
    name                 varchar(30)                  NOT NULL,
    last_name            varchar(30)                  NOT NULL,
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
    id           int          NOT NULL PRIMARY KEY AUTO_INCREMENT,
    title        varchar(100) NOT NULL,
    author       varchar(30)  NOT NULL,
    type         varchar(10)  NOT NULL,
    picture      varchar(256) NOT NULL,
    release_date varchar(10)  NOT NULL,
    language     varchar(30)  NOT NULL,
    page_cout    int          NOT NULL
);
#==========ITEM_UNIT TABLE===========#
CREATE TABLE IF NOT EXISTS Item_unit
(
    id           int         NOT NULL PRIMARY KEY AUTO_INCREMENT,
    status       varchar(30) NOT NULL,
    brought_date date        NOT NULL,
    item_id      int         NOT NULL,
    FOREIGN KEY (item_id) REFERENCES Item (id)
);
#==========RESERVATION TABLE===========#
CREATE TABLE IF NOT EXISTS Reservations
(
    id           int      NOT NULL PRIMARY KEY AUTO_INCREMENT,
    opening_date datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    user_id      int      NOT NULL,
    item_unit_id int      NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users (id),
    FOREIGN KEY (item_unit_id) REFERENCES Item_unit (id)
);
#==========RESERVATION TABLE===========#
CREATE TABLE IF NOT EXISTS Borrowings
(
    id              int      NOT NULL PRIMARY KEY AUTO_INCREMENT,
    opening_date    datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    closing_date    datetime,
    opening_user_id int      NOT NULL,
    closing_user_id int,
    reservation_id  int      NOT NULL,
    FOREIGN KEY (opening_user_id) REFERENCES Users (id),
    FOREIGN KEY (closing_user_id) REFERENCES Users (id),
    FOREIGN KEY (reservation_id) REFERENCES Reservations (id)
);
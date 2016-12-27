CREATE TABLE player (
  ID           INT PRIMARY KEY       AUTO_INCREMENT,
  username     VARCHAR(16)  NOT NULL,
  password     VARCHAR(255) NOT NULL,
  chips        INT(10)      NOT NULL DEFAULT 0,
  current_bid  INT(10)      NOT NULL DEFAULT 0,
  games_won    INT(10)      NOT NULL DEFAULT 0,
  games_lost   INT(10)      NOT NULL DEFAULT 0,
  chips_won    INT(10)      NOT NULL DEFAULT 0,
  chips_lost   INT(10)      NOT NULL DEFAULT 0,
  connected    INT(1)       NOT NULL DEFAULT 0,
  needs_update INT(1)       NOT NULL DEFAULT 0
);
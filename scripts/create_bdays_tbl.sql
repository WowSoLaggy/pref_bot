-- personal / common - all users can subscribe to common (shared) groups (if allowed); Only owner can subscribe to personal group (and actually cannot unsubscribe :) )
-- public / private - any user can join public group. However subscribing private group requires invite from owner

DROP TABLE IF EXISTS bdays_tbl;

CREATE TABLE bdays_tbl (
  id    INT     NOT NULL AUTO_INCREMENT,
  name  TEXT    NOT NULL,
  date  DATE    NOT NULL,
  fake  BOOLEAN NOT NULL,
  group INT     NOT NULL,
  
  PRIMARY KEY (id),
  FOREIGN KEY (group) REFERENCES groups_tbl(id)
);

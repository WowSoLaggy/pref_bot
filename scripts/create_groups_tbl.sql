-- personal / common - all users can subscribe to common (shared) groups (if allowed); Only owner can subscribe to personal group (and actually cannot unsubscribe :) )
-- public / private - any user can join public group. However subscribing private group requires invite from owner

DROP TABLE IF EXISTS groups_tbl;

CREATE TABLE groups_tbl (
  id        INT     NOT NULL AUTO_INCREMENT,
  name      TEXT    NOT NULL,
  owner     INT     NOT NULL,
  personal  BOOLEAN NOT NULL,
  public    BOOLEAN NOT NULL,
  
  PRIMARY KEY (id),
  FOREIGN KEY (owner) REFERENCES users_tbl(id)
);

INSERT INTO groups_tbl (id, name, owner, personal, public) VALUES
  (1, '1278&Co',    1, FALSE, FALSE),
  (2, 'My Friends', 1, FALSE, FALSE),
  (3, 'Relatives'   1, FALSE, FALSE)
;

DROP TABLE IF EXISTS subs_tbl;

CREATE TABLE subs_tbl (
  user_id   INT NOT NULL,
  group_id  INT NOT NULL,
  
  FOREING KEY (user_id)   REFERENCES users_tbl(id),
  FOREIGN KEY (group_id)  REFERENCES groups_tbl(id)
);

INSERT INTO subs_tbl (user_id, group_id) VALUES
  (1,  1),
  (2,  1),
  (3,  1),
  (4,  1),
  (5,  1),
  (6,  1),
  (7,  1),
  (8,  1),
  (9,  1),
  (10, 1),
  (11, 1),
  (12, 1),
  (1,  2),
  (1,  3),
  (2,  3)
;

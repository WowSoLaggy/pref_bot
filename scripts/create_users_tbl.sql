DROP TABLE IF EXISTS users_tbl;

CREATE TABLE users_tbl (
  id        INT     NOT NULL AUTO_INCREMENT,
  user_id   TEXT    NOT NULL,
  name      TEXT    NOT NULL,
  admin     BOOLEAN NOT NULL,
  group_id  INT     NOT NULL,
  d0        BOOLEAN NOT NULL,
  d1        BOOLEAN NOT NULL,
  
  PRIMARY KEY (id)
);

INSERT INTO users_tbl (id, user_id, name, admin, group_id, d0, d1) VALUES
  (1,  '305099932',  'ae',          TRUE,  -1, TRUE,  FALSE),
  (2,  '1092343373', 'N',           FALSE, -1, FALSE, FALSE),
  (3,  '1878144297', 'degt',        FALSE, -1, FALSE, FALSE),
  (4,  '5236221588', 'Nik',         FALSE, -1, FALSE, FALSE),
  (5,  '1857829702', 'Slava',       FALSE, -1, FALSE, FALSE),
  (6,  '225599231',  'Nemkin',      FALSE, -1, FALSE, FALSE),
  (7,  '322416610',  'Vano',        FALSE, -1, FALSE, FALSE),
  (8,  '1753858804', 'Kozlov',      FALSE, -1, FALSE, FALSE),
  (9,  '582065565',  'Den',         FALSE, -1, FALSE, FALSE),
  (10, '1166111956', 'Goryunov',    FALSE, -1, FALSE, FALSE),
  (11, '1605467087', 'Atyaka',      FALSE, -1, FALSE, FALSE),
  (12, '246963951',  'Ira Nemkina', FALSE, -1, FALSE, FALSE)
;

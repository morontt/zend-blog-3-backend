CREATE VIEW `v_commentators` AS
  SELECT
    c.id,
    c.name,
    c.mail,
    c.website,
    c.force_image
  FROM commentators AS c
  UNION ALL
  SELECT
    10000000 + u.id AS id,
    u.username AS name,
    u.mail,
    NULL AS website,
    1 AS force_image
  FROM users AS u

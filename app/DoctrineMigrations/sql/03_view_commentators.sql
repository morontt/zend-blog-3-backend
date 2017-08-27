CREATE VIEW `v_commentators` AS
  SELECT
    c.id,
    c.name,
    c.mail,
    c.website,
    c.disqus_id,
    c.email_hash
  FROM commentators AS c
  UNION ALL
  SELECT
    10000000 + u.id AS id,
    u.username AS name,
    u.mail,
    NULL AS website,
    NULL AS disqus_id,
    u.email_hash
  FROM users AS u

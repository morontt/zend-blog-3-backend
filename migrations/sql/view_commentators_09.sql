CREATE VIEW `v_commentators` AS
  SELECT
    c.id,
    c.name,
    c.mail,
    c.website,
    c.gender,
    c.fake_email,
    c.avatar_variant,
    c.time_created,
    c.email_check
  FROM commentators AS c
  UNION ALL
  SELECT
    10000000 + u.id AS id,
    u.username AS name,
    u.mail,
    NULL AS website,
    u.gender,
    u.fake_email,
    u.avatar_variant,
    u.time_created,
    u.email_check
  FROM users AS u

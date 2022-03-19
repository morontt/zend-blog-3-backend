CREATE VIEW `v_comments` AS
  SELECT
    c.id,
    c.parent_id,
    c.post_id,
    IF (c.user_id IS NULL, c.commentator_id, 10000000 + c.user_id) AS uid,
    IF (c.user_id IS NULL, t.name, u.username) AS username,
    IF (c.user_id IS NULL, t.mail, u.mail) AS mail,
    t.website,
    c.text,
    c.ip_addr,
    gci.city,
    gci.region,
    gci.latitude,
    gci.longitude,
    gci.time_zone,
    gco.country_name,
    gco.country_code,
    IF (c.user_id IS NULL, t.email_hash, u.email_hash) AS email_hash,
    c.deleted,
    c.time_created
  FROM comments AS c
    LEFT JOIN geo_location AS gl ON c.geo_location_id = gl.id
    LEFT JOIN geo_location_city AS gci ON gl.city_id = gci.id
    LEFT JOIN geo_location_country AS gco ON gci.country_id = gco.id
    LEFT JOIN commentators AS t ON c.commentator_id = t.id
    LEFT JOIN users AS u ON c.user_id = u.id

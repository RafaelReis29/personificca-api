CREATE TABLE IF NOT EXISTS categories (
  id SERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS attributes (
  id SERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS personas (
  id SERIAL PRIMARY KEY,
  name VARCHAR(50) NOT NULL,
  story TEXT NOT NULL,
  category_id INTEGER NOT NULL REFERENCES categories(id),
  share VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS persona_attributes (
  id SERIAL PRIMARY KEY,
  persona_id INTEGER NOT NULL REFERENCES personas(id) ON DELETE CASCADE,
  attribute_id INTEGER NOT NULL REFERENCES attributes(id),
  level INTEGER NOT NULL
);

INSERT INTO categories (name)
SELECT name
FROM (VALUES
  ('Cyberpunk'),
  ('Sci-fi'),
  ('Ordinary'),
  ('Steampunk'),
  ('Fantasy')
) AS seed(name)
WHERE NOT EXISTS (SELECT 1 FROM categories);

INSERT INTO attributes (name)
SELECT name
FROM (VALUES
  ('Intelligence'),
  ('Strength'),
  ('Dexterity'),
  ('Constitution'),
  ('Wisdom'),
  ('Charisma')
) AS seed(name)
WHERE NOT EXISTS (SELECT 1 FROM attributes);

INSERT INTO personas (name, story, category_id, share)
SELECT 'Mira Vale', 'A careful city guide who reads people quickly and keeps her promises simple.', 3, 'public'
WHERE NOT EXISTS (SELECT 1 FROM personas);

INSERT INTO persona_attributes (persona_id, attribute_id, level)
SELECT 1, id, CASE name
  WHEN 'Intelligence' THEN 72
  WHEN 'Strength' THEN 38
  WHEN 'Dexterity' THEN 54
  WHEN 'Constitution' THEN 62
  WHEN 'Wisdom' THEN 80
  ELSE 68
END
FROM attributes
WHERE NOT EXISTS (SELECT 1 FROM persona_attributes WHERE persona_id = 1);

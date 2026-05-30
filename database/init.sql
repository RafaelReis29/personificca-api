CREATE TABLE IF NOT EXISTS users (
  id SERIAL PRIMARY KEY,
  status VARCHAR(255),
  name VARCHAR(255),
  email VARCHAR(255),
  password VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS categories (
  id SERIAL PRIMARY KEY,
  name VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS attributes (
  id SERIAL PRIMARY KEY,
  name VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS personas (
  id SERIAL PRIMARY KEY,
  user_id INTEGER,
  name VARCHAR(50),
  story TEXT,
  category_id INTEGER,
  share VARCHAR(255) CHECK (share IN ('private', 'only_with_link', 'public')),

  CONSTRAINT fk_personas_user_id_users
    FOREIGN KEY (user_id) REFERENCES users(id),

  CONSTRAINT fk_personas_category_id_categories
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE IF NOT EXISTS favorites (
  id SERIAL PRIMARY KEY,
  user_id INTEGER,
  persona_id INTEGER,

  CONSTRAINT fk_favorites_user_id_users
    FOREIGN KEY (user_id) REFERENCES users(id),

  CONSTRAINT fk_favorites_persona_id_personas
    FOREIGN KEY (persona_id) REFERENCES personas(id)
);

CREATE TABLE IF NOT EXISTS persona_attributes (
  id SERIAL PRIMARY KEY,
  persona_id INTEGER,
  attribute_id INTEGER,
  level INTEGER,

  CONSTRAINT fk_persona_attributes_persona_id_personas
    FOREIGN KEY (persona_id) REFERENCES personas(id),

  CONSTRAINT fk_persona_attributes_attribute_id_attributes
    FOREIGN KEY (attribute_id) REFERENCES attributes(id)
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
SELECT 'Boba, Only Boba', 'A careful city guide who reads people quickly and keeps her promises simple.', 3, 'public'
WHERE NOT EXISTS (SELECT 1 FROM personas);

INSERT INTO persona_attributes (persona_id, attribute_id, level)
SELECT p.id, a.id, CASE a.name
  WHEN 'Intelligence' THEN 72
  WHEN 'Strength' THEN 38
  WHEN 'Dexterity' THEN 54
  WHEN 'Constitution' THEN 62
  WHEN 'Wisdom' THEN 80
  ELSE 68
END
FROM attributes a
CROSS JOIN personas p
WHERE p.name = 'Mira Vale'
  AND NOT EXISTS (SELECT 1 FROM persona_attributes WHERE persona_id = p.id);

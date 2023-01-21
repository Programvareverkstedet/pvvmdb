CREATE TABLE IF NOT EXISTS "users" (
  "id" serial PRIMARY KEY,
  "name" varchar(255) NOT NULL,
  "username" varchar(32) UNIQUE NOT NULL,
  "phone" varchar(32),
  "external_email" varchar(320),
  "locked" boolean NOT NULL DEFAULT false,
  "created_at" date NOT NULL DEFAULT CURRENT_DATE,
  "comment" varchar(255)
);

CREATE TABLE IF NOT EXISTS "membership_types" (
  "id" serial PRIMARY KEY,
  "name" varchar(32) UNIQUE NOT NULL,
  "description" varchar(400) NOT NULL,
  "price" integer NOT NULL
);

CREATE TABLE IF NOT EXISTS "purchases" (
  "id" serial PRIMARY KEY,
  "user_id" integer NOT NULL,
  "created_at" date NOT NULL DEFAULT CURRENT_DATE,
  "amount_paid" integer NOT NULL,
  "comment" varchar(400),

  CONSTRAINT "purchases_user_id_fkey" FOREIGN KEY ("user_id")
      REFERENCES "users" ("id") MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
);

CREATE TABLE IF NOT EXISTS "disk_purchases" (
  "size_mb" integer NOT NULL
) INHERITS ("purchases");

CREATE TABLE IF NOT EXISTS "membership_purchases" (
  "membership_type" integer NOT NULL,

  CONSTRAINT "membership_purchases_membership_type_fkey" FOREIGN KEY ("membership_type")
      REFERENCES "membership_types" ("id") MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
) INHERITS ("purchases");

INSERT INTO membership_types (name, description, price) VALUES
  ('Livstid', 'Livstidsmedlemskap, betales én gang, varer livet ut.', 1024),
  ('Årlig Kontingent', 'Årlig medlemskap, en fast sum betales hvert år.', 50);

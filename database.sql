CREATE TABLE article
(
  id serial NOT NULL,
  destination character varying(256) NOT NULL,
  "text" text NOT NULL,
  CONSTRAINT article_pkey PRIMARY KEY (id)
)
WITH (OIDS=FALSE);
ALTER TABLE article OWNER TO postgres;


CREATE TABLE venue
(
  id serial NOT NULL,
  name character varying(256) NOT NULL,
  url character varying(256) NOT NULL,
  CONSTRAINT venue_pkey PRIMARY KEY (id)
)
WITH (OIDS=FALSE);
ALTER TABLE venue OWNER TO postgres;


CREATE TABLE trip
(
  id serial NOT NULL,
  departure character varying(256) NOT NULL,
  arrival character varying(256) NOT NULL,
  CONSTRAINT trip_pkey PRIMARY KEY (id)
)
WITH (OIDS=FALSE);
ALTER TABLE trip OWNER TO postgres;


CREATE TABLE event
(
  id serial NOT NULL,
  venue_id integer NOT NULL,
  title character varying(256) NOT NULL,
  url character varying(256) NOT NULL,
  description character varying(256) NOT NULL,
  date date,
 CONSTRAINT event_venue_id_fkey FOREIGN KEY (venue_id)
      REFERENCES venue (id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE RESTRICT,
 CONSTRAINT event_pkey PRIMARY KEY (id)
)
WITH (OIDS=FALSE);
ALTER TABLE event OWNER TO postgres;




CREATE TABLE event_trip
(
  event_id integer NOT NULL,
  trip_id integer NOT NULL,
  CONSTRAINT event_trip_pkey PRIMARY KEY (event_id, trip_id),
  CONSTRAINT event_trip_relation_ibfk_1 FOREIGN KEY (event_id)
      REFERENCES event (id) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT event_trip_relation_ibfk_2 FOREIGN KEY (trip_id)
      REFERENCES trip (id) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT
)
WITH (OIDS=FALSE);
ALTER TABLE event_trip OWNER TO postgres;


CREATE TABLE poi
(
  id serial NOT NULL,
  "name" character varying(256) NOT NULL,
  types character varying(256) NOT NULL,
  address character varying(256) NOT NULL,
  latitude double precision,
  longitude double precision,
  url character varying(256) NOT NULL,
  imageurl character varying(256) NOT NULL,
  CONSTRAINT poi_pkey PRIMARY KEY (id)
)
WITH (OIDS=FALSE);
ALTER TABLE poi OWNER TO postgres;


CREATE TABLE poi_trip
(
  poi_id integer NOT NULL,
  trip_id integer NOT NULL,
  CONSTRAINT poi_trip_pkey PRIMARY KEY (poi_id, trip_id),
  CONSTRAINT poi_trip_relation_ibfk_1 FOREIGN KEY (poi_id)
      REFERENCES poi (id) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT poi_trip_relation_ibfk_2 FOREIGN KEY (trip_id)
      REFERENCES trip (id) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT
)
WITH (OIDS=FALSE);
ALTER TABLE poi_trip OWNER TO postgres;

CREATE TABLE step
(
  id serial NOT NULL,
  trip_id integer NOT NULL,
  sequenceorder integer NOT NULL DEFAULT 0,
  distance integer NOT NULL,
  duration integer NOT NULL,
  instructions text NOT NULL,
  CONSTRAINT step_pkey PRIMARY KEY (id),
  CONSTRAINT step_trip_id_fkey FOREIGN KEY (trip_id)
      REFERENCES trip (id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE RESTRICT
)
WITH (OIDS=FALSE);
ALTER TABLE step OWNER TO postgres;




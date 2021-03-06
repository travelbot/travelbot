CREATE TABLE article (
  id SERIAL NOT NULL PRIMARY KEY,
  destination VARCHAR(256) NOT NULL,
  text TEXT NOT NULL
) WITHOUT OIDS;

CREATE TABLE venue (
  id SERIAL NOT NULL PRIMARY KEY,
  name VARCHAR(256) NOT NULL,
  url VARCHAR(256) NOT NULL
) WITHOUT OIDS;

CREATE TABLE trip (
  id SERIAL NOT NULL PRIMARY KEY,
  departure VARCHAR(256) NOT NULL,
  arrival VARCHAR(256) NOT NULL,
  departuredate TIMESTAMP WITH TIME ZONE,
  arrivaldate TIMESTAMP WITH TIME ZONE
) WITHOUT OIDS;

CREATE TABLE eventgroup (
	id SERIAL NOT NULL PRIMARY KEY,
	location VARCHAR(256) NOT NULL,
	date TIMESTAMP WITH TIME ZONE NOT NULL
) WITHOUT OIDS;

CREATE TABLE event
(
  id SERIAL NOT NULL PRIMARY KEY,
  venue_id INTEGER NOT NULL REFERENCES venue(id) ON UPDATE CASCADE ON DELETE RESTRICT,
  group_id INTEGER NULL REFERENCES eventgroup(id) ON UPDATE CASCADE ON DELETE RESTRICT,
  title VARCHAR(256) NOT NULL,
  latitude NUMERIC,
  longitude NUMERIC,
  url VARCHAR(256) NOT NULL,
  description TEXT NOT NULL,
  date TIMESTAMP WITH TIME ZONE NOT NULL
) WITHOUT OIDS;

CREATE TABLE event_trip
(
  event_id INTEGER NOT NULL REFERENCES event(id) ON UPDATE CASCADE ON DELETE RESTRICT,
  trip_id INTEGER NOT NULL REFERENCES trip(id) ON UPDATE CASCADE ON DELETE RESTRICT,
  PRIMARY KEY (event_id, trip_id)
) WITHOUT OIDS;

CREATE TABLE poigroup (
  id SERIAL NOT NULL PRIMARY KEY,
  latitude NUMERIC NOT NULL,
  longitude NUMERIC NOT NULL
) WITHOUT OIDS;

CREATE TABLE poi
(
  id SERIAL NOT NULL PRIMARY KEY,
  group_id INTEGER NULL REFERENCES poigroup(id) ON UPDATE CASCADE ON DELETE RESTRICT,
  name VARCHAR(256) NOT NULL,
  types VARCHAR(256),
  address VARCHAR(256) NULL,
  latitude NUMERIC,
  longitude NUMERIC,
  url VARCHAR(256) NOT NULL,
  imageurl VARCHAR(256) NOT NULL
) WITHOUT OIDS;

CREATE TABLE poi_trip
(
  poi_id INTEGER NOT NULL REFERENCES poi(id) ON UPDATE CASCADE ON DELETE RESTRICT,
  trip_id INTEGER NOT NULL REFERENCES trip(id) ON UPDATE CASCADE ON DELETE RESTRICT,
  PRIMARY KEY (poi_id, trip_id)
) WITHOUT OIDS;

CREATE TABLE step
(
  id SERIAL NOT NULL PRIMARY KEY,
  trip_id INTEGER NOT NULL REFERENCES trip(id) ON UPDATE CASCADE ON DELETE CASCADE,
  sequenceorder INTEGER NOT NULL DEFAULT 0,
  distance INTEGER NOT NULL,
  duration INTEGER NOT NULL,
  instructions text NOT NULL,
  polyline TEXT NOT NULL
) WITHOUT OIDS;

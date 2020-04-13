
CREATE TABLE Admins
(
	email                VARCHAR(255) NOT NULL,
	firstName            VARCHAR(50) NOT NULL,
	lastName             VARCHAR(50) NOT NULL
);

ALTER TABLE Admins
ADD CONSTRAINT XPKAdmins PRIMARY KEY (email);

CREATE TABLE Cinemas
(
	email                VARCHAR(255) NOT NULL,
	name                 VARCHAR(64) NOT NULL,
	address              VARCHAR(64) NOT NULL,
	phoneNumber          VARCHAR(64) NOT NULL,
	description          TEXT NULL,
	mngFirstName         VARCHAR(64) NOT NULL,
	mngLastName          VARCHAR(64) NOT NULL,
	mngPhoneNumber       VARCHAR(64) NOT NULL,
	mngEmail             VARCHAR(255) NOT NULL,
	banner               VARBINARY NULL,
	approved             boolean NOT NULL,
	closed               boolean NOT NULL,
	idCountry            BIGINT NULL,
	idCity               BIGINT NULL
);

ALTER TABLE Cinemas
ADD CONSTRAINT XPKCinemas PRIMARY KEY (email);

CREATE TABLE Cities
(
	idCity               BIGINT NOT NULL,
	name                 VARCHAR(50) NOT NULL,
	idCountry            BIGINT NOT NULL
);

ALTER TABLE Cities
ADD CONSTRAINT XPKCities PRIMARY KEY (idCity);

CREATE TABLE ComingSoon
(
	tmdbID               VARCHAR(64) NOT NULL,
	email                VARCHAR(255) NOT NULL
);

ALTER TABLE ComingSoon
ADD CONSTRAINT XPKComingSoon PRIMARY KEY (tmdbID,email);

CREATE TABLE Countries
(
	idCountry            BIGINT NOT NULL,
	name                 VARCHAR(50) NOT NULL
);

ALTER TABLE Countries
ADD CONSTRAINT XPKCountries PRIMARY KEY (idCountry);

CREATE TABLE Galleries
(
	email                VARCHAR(255) NOT NULL,
	name                 VARCHAR(64) NOT NULL,
	image                VARBINARY NOT NULL
);

ALTER TABLE Galleries
ADD CONSTRAINT XPKGalleries PRIMARY KEY (email,name);

CREATE TABLE Movies
(
	tmdbID               VARCHAR(64) NOT NULL,
	title                VARCHAR(64) NOT NULL,
	released             DATE NOT NULL,
	runtime              INTEGER NOT NULL,
	genre                TEXT NOT NULL,
	director             TEXT NOT NULL,
	writer               TEXT NOT NULL,
	actors               TEXT NOT NULL,
	plot                 TEXT NULL,
	poster               VARCHAR(255) NULL,
	backgroundImg        VARCHAR(255) NULL,
	imdbRating           DECIMAL(2) NULL CHECK ( <=10
 ),
	imdbID               VARCHAR(64) NOT NULL,
	reviews              TEXT NULL,
	trailer              VARCHAR(255) NULL
);

ALTER TABLE Movies
ADD CONSTRAINT XPKMovies PRIMARY KEY (tmdbID);

CREATE TABLE Projections
(
	idPro                BIGINT NOT NULL,
	roomName             VARCHAR(64) NOT NULL,
	email                VARCHAR(255) NOT NULL,
	dateTime             DATE NOT NULL,
	price                DECIMAL(19,4) NOT NULL,
	canceled             boolean NOT NULL,
	tmdbID               VARCHAR(64) NOT NULL,
	idTech               INTEGER NOT NULL
);

ALTER TABLE Projections
ADD CONSTRAINT XPKProjections PRIMARY KEY (idPro);

CREATE TABLE Reservations
(
	idRes                BIGINT NOT NULL,
	confirmed            boolean NOT NULL,
	idPro                BIGINT NOT NULL,
	email                VARCHAR(255) NOT NULL
);

ALTER TABLE Reservations
ADD CONSTRAINT XPKReservations PRIMARY KEY (idRes);

CREATE TABLE Rooms
(
	name                 VARCHAR(64) NOT NULL,
	email                VARCHAR(255) NOT NULL,
	numberOfRows         INTEGER NOT NULL,
	seatsInRow           INTEGER NOT NULL
);

ALTER TABLE Rooms
ADD CONSTRAINT XPKRooms PRIMARY KEY (name,email);

CREATE TABLE RoomTechnologies
(
	name                 VARCHAR(64) NOT NULL,
	email                VARCHAR(255) NOT NULL,
	idTech               INTEGER NOT NULL
);

ALTER TABLE RoomTechnologies
ADD CONSTRAINT XPKRoomTechnologies PRIMARY KEY (name,email,idTech);

CREATE TABLE RUsers
(
	email                VARCHAR(255) NOT NULL,
	firstName            VARCHAR(64) NOT NULL,
	lastName             VARCHAR(64) NOT NULL,
	phoneNumber          VARCHAR(64) NULL,
	idCountry            BIGINT NULL,
	idCity               BIGINT NULL
);

ALTER TABLE RUsers
ADD CONSTRAINT XPKRUsers PRIMARY KEY (email);

CREATE TABLE Seats
(
	idPro                BIGINT NOT NULL,
	rowNumber            INTEGER NOT NULL,
	seatNumber           INTEGER NOT NULL,
	status               VARCHAR(64) NOT NULL,
	idRes                BIGINT NULL
);

ALTER TABLE Seats
ADD CONSTRAINT XPKSeats PRIMARY KEY (idPro,rowNumber,seatNumber);

CREATE TABLE Technologies
(
	idTech               INTEGER NOT NULL,
	name                 VARCHAR(64) NOT NULL
);

ALTER TABLE Technologies
ADD CONSTRAINT XPKTechnologies PRIMARY KEY (idTech);

CREATE TABLE Users
(
	email                VARCHAR(255) NOT NULL,
	password             VARCHAR(255) NOT NULL,
	image                VARBINARY NULL
);

ALTER TABLE Users
ADD CONSTRAINT XPKUsers PRIMARY KEY (email);

CREATE TABLE Verifications
(
	email                VARCHAR(255) NOT NULL,
	code                 VARCHAR(64) NOT NULL,
	created              TIMESTAMP NOT NULL
);

ALTER TABLE Verifications
ADD CONSTRAINT XPKVerifications PRIMARY KEY (email);

CREATE TABLE Workers
(
	email                VARCHAR(255) NOT NULL,
	idCinema             VARCHAR(255) NOT NULL,
	firstName            VARCHAR(50) NOT NULL,
	lastName             VARCHAR(50) NOT NULL
);

ALTER TABLE Workers
ADD CONSTRAINT XPKWorkers PRIMARY KEY (email);

ALTER TABLE Admins
ADD CONSTRAINT R_9 FOREIGN KEY (email) REFERENCES Users (email)
		ON DELETE CASCADE;

ALTER TABLE Cinemas
ADD CONSTRAINT R_10 FOREIGN KEY (email) REFERENCES Users (email)
		ON DELETE CASCADE;

ALTER TABLE Cinemas
ADD CONSTRAINT R_21 FOREIGN KEY (idCountry) REFERENCES Countries (idCountry);

ALTER TABLE Cinemas
ADD CONSTRAINT R_22 FOREIGN KEY (idCity) REFERENCES Cities (idCity);

ALTER TABLE Cities
ADD CONSTRAINT R_16 FOREIGN KEY (idCountry) REFERENCES Countries (idCountry);

ALTER TABLE ComingSoon
ADD CONSTRAINT R_24 FOREIGN KEY (tmdbID) REFERENCES Movies (tmdbID);

ALTER TABLE ComingSoon
ADD CONSTRAINT R_25 FOREIGN KEY (email) REFERENCES Cinemas (email);

ALTER TABLE Galleries
ADD CONSTRAINT R_23 FOREIGN KEY (email) REFERENCES Cinemas (email);

ALTER TABLE Projections
ADD CONSTRAINT R_30 FOREIGN KEY (tmdbID) REFERENCES Movies (tmdbID);

ALTER TABLE Projections
ADD CONSTRAINT R_31 FOREIGN KEY (roomName, email) REFERENCES Rooms (name, email);

ALTER TABLE Projections
ADD CONSTRAINT R_32 FOREIGN KEY (idTech) REFERENCES Technologies (idTech);

ALTER TABLE Reservations
ADD CONSTRAINT R_35 FOREIGN KEY (idPro) REFERENCES Projections (idPro);

ALTER TABLE Reservations
ADD CONSTRAINT R_36 FOREIGN KEY (email) REFERENCES RUsers (email);

ALTER TABLE Rooms
ADD CONSTRAINT R_27 FOREIGN KEY (email) REFERENCES Cinemas (email);

ALTER TABLE RoomTechnologies
ADD CONSTRAINT R_28 FOREIGN KEY (name, email) REFERENCES Rooms (name, email);

ALTER TABLE RoomTechnologies
ADD CONSTRAINT R_29 FOREIGN KEY (idTech) REFERENCES Technologies (idTech);

ALTER TABLE RUsers
ADD CONSTRAINT R_11 FOREIGN KEY (email) REFERENCES Users (email)
		ON DELETE CASCADE;

ALTER TABLE RUsers
ADD CONSTRAINT R_19 FOREIGN KEY (idCountry) REFERENCES Countries (idCountry);

ALTER TABLE RUsers
ADD CONSTRAINT R_20 FOREIGN KEY (idCity) REFERENCES Cities (idCity);

ALTER TABLE Seats
ADD CONSTRAINT R_33 FOREIGN KEY (idPro) REFERENCES Projections (idPro);

ALTER TABLE Seats
ADD CONSTRAINT R_34 FOREIGN KEY (idRes) REFERENCES Reservations (idRes);

ALTER TABLE Verifications
ADD CONSTRAINT R_8 FOREIGN KEY (email) REFERENCES Users (email);

ALTER TABLE Workers
ADD CONSTRAINT R_12 FOREIGN KEY (email) REFERENCES Users (email)
		ON DELETE CASCADE;

ALTER TABLE Workers
ADD CONSTRAINT R_18 FOREIGN KEY (idCinema) REFERENCES Cinemas (email);

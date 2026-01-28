-- ---------------------------------
-- SCRIPT 1

-- Set up the database
SHOW DATABASES;
DROP DATABASE IF EXISTS assign2db;
CREATE DATABASE assign2db;
USE assign2db; 

-- Create the tables for the database
SHOW TABLES;

CREATE TABLE doctor(docid CHAR(5) NOT NULL, firstname VARCHAR(20) NOT NULL, lastname VARCHAR(20) NOT NULL, birthdate DATE, startdate DATE, PRIMARY KEY(docid));

CREATE TABLE patient (ohip CHAR(9) NOT NULL, firstname VARCHAR(20) NOT NULL, lastname VARCHAR(20), weight SMALLINT, birthdate DATE, height DECIMAL(3,2), treatsdocid CHAR(5), PRIMARY KEY(ohip), FOREIGN KEY (treatsdocid) REFERENCES doctor(docid) ON DELETE RESTRICT);

CREATE TABLE nurse (nurseid CHAR(5) NOT NULL, firstname VARCHAR(20) NOT NULL, lastname VARCHAR(20) NOT NULL, startdate DATE, reporttonurseid CHAR(5),  PRIMARY KEY(nurseid), FOREIGN KEY(reporttonurseid) REFERENCES nurse(nurseid));

CREATE TABLE workingfor (docid CHAR(5), nurseid CHAR(5), hours SMALLINT, PRIMARY KEY (docid, nurseid), FOREIGN KEY(docid) REFERENCES doctor(docid), FOREIGN KEY(nurseid) REFERENCES nurse(nurseid));

SHOW TABLES;

-- ------------------------------------
-- insert some data
INSERT INTO doctor (docid, firstname, lastname, birthdate, startdate) VALUES
('RAD34', 'Sue', 'Tanaka', '1978-06-15', '2010-04-20'),
('AGD56', 'Sean', 'Aziz', '1985-02-23', '2015-08-14'),
('HIT45', 'Scott', 'Mortensen', '1960-11-07', '2000-12-01'),
('YRT67', 'Gerry', 'Webster', '1972-04-11', '2005-07-18'),
('JKK78', 'Jon', 'Joselyn', '1980-09-19', '2012-03-25'),
('SEE66', 'Colleen', 'Tyler', '1965-01-30', '1999-09-10');
INSERT INTO doctor (docid, firstname, lastname, birthdate, startdate) VALUES ('D001', 'Gregory', 'House', '1959-06-11', '1996-05-01');
INSERT INTO doctor (docid, firstname, lastname, birthdate, startdate) VALUES ('D002', 'John', 'Watson', '1854-07-31', '1881-06-01');
INSERT INTO doctor (docid, firstname, lastname, birthdate, startdate) VALUES ('D003', 'Meredith', 'Grey', '1978-11-10', '2005-03-27');
INSERT INTO doctor (docid, firstname, lastname, birthdate, startdate) VALUES ('D004', 'Doug', 'Ross', '1958-02-02', '1994-09-19');
INSERT INTO doctor (docid, firstname, lastname, birthdate, startdate) VALUES ('D005', 'Michaela', 'Quinn', '1833-03-01', '1867-01-01');
INSERT INTO doctor (docid, firstname, lastname, birthdate, startdate) VALUES ('D006', 'Hawkeye', 'Pierce', '1922-11-10', '1950-06-25');
INSERT INTO doctor (docid, firstname, lastname, birthdate, startdate) VALUES ('D007', 'Dana', 'Scully', '1964-02-23', '1993-09-10');
INSERT INTO doctor (docid, firstname, lastname, birthdate, startdate) VALUES ('D008', 'John', 'Carter', '1970-06-04', '1994-09-19');
INSERT INTO doctor (docid, firstname, lastname, birthdate, startdate) VALUES ('D009', 'Mark', 'Sloan', '1954-03-02', '1984-10-13');
INSERT INTO doctor (docid, firstname, lastname, birthdate, startdate) VALUES ('D010', 'Leonard', 'McCoy', '2227-01-20', '2253-05-01');


-- insert into the patient table
INSERT INTO patient (ohip, firstname, lastname, weight, birthdate, height, treatsdocid) VALUES
('111111111', 'Homer', 'Simpson', 66, '1987-02-02', 1.81, 'AGD56'),
('222222222', 'Marge', 'Simpson', 58, '1990-03-19', 1.72, 'RAD34'),
('333333333', 'Bart', 'Simpson', 40, '2010-04-01', 1.55, 'AGD56'),
('444444444', 'Lisa', 'Simpson', 30, '2012-05-09', 1.45, 'AGD56'),
('555555555', 'Maggie', 'Simpson', 20, '2020-06-21', 0.91, 'AGD56'),
('666666666', 'Ned', 'Flanders', 80, '1968-01-15', 1.75, 'YRT67'),
('777777777', 'Jon', 'Burns', 70, '1930-02-22', 1.68, 'YRT67'),
('888888888', 'Rod', 'Flanders', 45, '2000-11-05', 1.60, 'SEE66'),
('999999999', 'Todd', 'Flanders', 50, '1999-12-12', 1.65, 'SEE66'),
('000000000', 'Milhouse', 'Van Houten', 60, '1985-07-15', 1.70, 'RAD34');
INSERT INTO patient (ohip, firstname, lastname, weight, birthdate, height, treatsdocid) VALUES ('P00000001', 'Walter', 'White', 70, '1959-09-07', 1.75, 'D001');
INSERT INTO patient (ohip, firstname, lastname, weight, birthdate, height, treatsdocid) VALUES ('P00000002', 'Jesse', 'Pinkman', 68, '1984-09-24', 1.76, 'D001');
INSERT INTO patient (ohip, firstname, lastname, weight, birthdate, height, treatsdocid) VALUES ('P00000003', 'Sherlock', 'Holmes', 75, '1854-01-06', 1.83, 'D002');
INSERT INTO patient (ohip, firstname, lastname, weight, birthdate, height, treatsdocid) VALUES ('P00000004', 'Rachel', 'Green', 58, '1969-05-05', 1.67, 'D003');
INSERT INTO patient (ohip, firstname, lastname, weight, birthdate, height, treatsdocid) VALUES ('P00000005', 'Chandler', 'Bing', 72, '1968-04-08', 1.83, 'D003');
INSERT INTO patient (ohip, firstname, lastname, weight, birthdate, height, treatsdocid) VALUES ('P00000006', 'Ross', 'Geller', 80, '1967-10-18', 1.87, 'D003');
INSERT INTO patient (ohip, firstname, lastname, weight, birthdate, height, treatsdocid) VALUES ('P00000007', 'Phoebe', 'Buffay', 54, '1967-02-16', 1.75, 'D003');
INSERT INTO patient (ohip, firstname, lastname, weight, birthdate, height, treatsdocid) VALUES ('P00000008', 'Monica', 'Geller', 57, '1969-03-27', 1.68, 'D003');
INSERT INTO patient (ohip, firstname, lastname, weight, birthdate, height, treatsdocid) VALUES ('P00000009', 'Joey', 'Tribbiani', 85, '1967-01-09', 1.77, 'D003');
INSERT INTO patient (ohip, firstname, lastname, weight, birthdate, height, treatsdocid) VALUES ('P00000010', 'James', 'Wilson', 72, '1968-04-18', 1.80, 'D001');
INSERT INTO patient (ohip, firstname, lastname, weight, birthdate, height, treatsdocid) VALUES ('P00000011', 'Don', 'Draper', 78, '1926-06-01', 1.85, 'D004');
INSERT INTO patient (ohip, firstname, lastname, weight, birthdate, height, treatsdocid) VALUES ('P00000012', 'Peggy', 'Olson', 59, '1930-05-25', 1.70, 'D004');
INSERT INTO patient (ohip, firstname, lastname, weight, birthdate, height, treatsdocid) VALUES ('P00000013', 'Betty', 'Draper', 62, '1932-01-14', 1.68, 'D004');
INSERT INTO patient (ohip, firstname, lastname, weight, birthdate, height, treatsdocid) VALUES ('P00000014', 'Harry', 'Morgan', 85, '1924-04-10', 1.82, 'D006');
INSERT INTO patient (ohip, firstname, lastname, weight, birthdate, height, treatsdocid) VALUES ('P00000015', 'Daphne', 'Moon', 55, '1962-09-13', 1.67, 'D004');
INSERT INTO patient (ohip, firstname, lastname, weight, birthdate, height, treatsdocid) VALUES ('P00000016', 'Frasier', 'Crane', 75, '1952-05-01', 1.89, 'D004');
INSERT INTO patient (ohip, firstname, lastname, weight, birthdate, height, treatsdocid) VALUES ('P00000017', 'Niles', 'Crane', 72, '1957-02-03', 1.82, 'D004');
INSERT INTO patient (ohip, firstname, lastname, weight, birthdate, height, treatsdocid) VALUES ('P00000018', 'Eric', 'Foreman', 70, '1974-07-15', 1.80, 'D001');
INSERT INTO patient (ohip, firstname, lastname, weight, birthdate, height, treatsdocid) VALUES ('P00000019', 'Lisa', 'Cuddy', 62, '1968-01-21', 1.75, 'D001');
INSERT INTO patient (ohip, firstname, lastname, weight, birthdate, height, treatsdocid) VALUES ('P00000020', 'Remy', 'Hadley', 60, '1979-11-22', 1.70, 'D001');


-- insert into the nurse Table
INSERT INTO nurse (nurseid, firstname, lastname, startdate, reporttonurseid) VALUES ('AAAA1', 'Hannah', 'Montana', '2020-03-03', NULL),
('BBBB2', 'Alex', 'Russo', '2018-07-10', NULL),
('CCCC3', 'Justin', 'Russo', '2015-06-12', NULL),
('DDDD4', 'Max', 'Russo', '2017-05-15', NULL),
('EEEE5', 'Miley', 'Stewart', '2019-08-20', NULL),
('FFFF6', 'Lilly', 'Truscott', '2021-02-14', NULL),
('GGGG7', 'Oliver', 'Oken', '2016-11-30', NULL),
('HHHH8', 'Harper', 'Finkle', '2014-09-25', NULL);
INSERT INTO nurse (nurseid, firstname, lastname, startdate, reporttonurseid) VALUES ('N001', 'Jackie', 'Peyton', '2000-05-01', NULL);
INSERT INTO nurse (nurseid, firstname, lastname, startdate, reporttonurseid) VALUES ('N002', 'Carla', 'Espinosa', '2001-03-12', NULL);
INSERT INTO nurse (nurseid, firstname, lastname, startdate, reporttonurseid) VALUES ('N003', 'Greg', 'Focker', '2003-08-20', NULL);
INSERT INTO nurse (nurseid, firstname, lastname, startdate, reporttonurseid) VALUES ('N004', 'Ratched', 'Nurse', '1995-01-15', 'N001');
INSERT INTO nurse (nurseid, firstname, lastname, startdate, reporttonurseid) VALUES ('N005', 'Samantha', 'Taggart', '2004-06-18', 'N002');
INSERT INTO nurse (nurseid, firstname, lastname, startdate, reporttonurseid) VALUES ('N006', 'Annie', 'Wilkes', '1990-11-30', 'N003');
INSERT INTO nurse (nurseid, firstname, lastname, startdate, reporttonurseid) VALUES ('N007', 'Gaylord', 'Focker', '2005-02-14', 'N003');
INSERT INTO nurse (nurseid, firstname, lastname, startdate, reporttonurseid) VALUES ('N008', 'Julia', 'Russell', '2006-07-21', 'N004');
INSERT INTO nurse (nurseid, firstname, lastname, startdate, reporttonurseid) VALUES ('N009', 'Pam', 'Byrnes', '2007-03-09', 'N005');
INSERT INTO nurse (nurseid, firstname, lastname, startdate, reporttonurseid) VALUES ('N010', 'Betty', 'Smith', '2008-11-11', 'N006');



-- insert into the workingfor table
SELECT * FROM workingfor;
INSERT INTO workingfor (docid, nurseid, hours) VALUES
('RAD34','BBBB2',100), ('RAD34','CCCC3',242),('RAD34','HHHH8',22),('SEE66','BBBB2',100),('SEE66','CCCC3',55), ('AGD56','CCCC3',55), ('AGD56','DDDD4',75), ('AGD56','BBBB2',55), ('YRT67','FFFF6',100), ('JKK78','HHHH8',200),('RAD34','GGGG7',10),('SEE66','GGGG7',20),('AGD56','GGGG7',15),('YRT67','GGGG7',5),('JKK78','GGGG7',7),('YRT67','EEEE5',33);
INSERT INTO workingfor (docid, nurseid, hours) VALUES ('D001', 'N001', 40);
INSERT INTO workingfor (docid, nurseid, hours) VALUES ('D001', 'N004', 35);
INSERT INTO workingfor (docid, nurseid, hours) VALUES ('D002', 'N002', 50);
INSERT INTO workingfor (docid, nurseid, hours) VALUES ('D003', 'N005', 45);
INSERT INTO workingfor (docid, nurseid, hours) VALUES ('D004', 'N006', 30);
INSERT INTO workingfor (docid, nurseid, hours) VALUES ('D004', 'N008', 60);
INSERT INTO workingfor (docid, nurseid, hours) VALUES ('D005', 'N007', 25);
INSERT INTO workingfor (docid, nurseid, hours) VALUES ('D004', 'N009', 40);
INSERT INTO workingfor (docid, nurseid, hours) VALUES ('D004', 'N003', 50);
INSERT INTO workingfor (docid, nurseid, hours) VALUES ('D004', 'N010', 55);
INSERT INTO workingfor (docid, nurseid, hours) VALUES ('D009', 'N001', 35);
INSERT INTO workingfor (docid, nurseid, hours) VALUES ('D009', 'N005', 45);
INSERT INTO workingfor (docid, nurseid, hours) VALUES ('D010', 'N002', 50);
INSERT INTO workingfor (docid, nurseid, hours) VALUES ('D010', 'N009', 70);
INSERT INTO workingfor (docid, nurseid, hours) VALUES ('D003', 'N007', 60);


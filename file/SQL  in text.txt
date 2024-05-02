CREATE DATABASE UNIVERSITY;
USE UNIVERSITY;
CREATE TABLE Student (
  stud_ID INT PRIMARY KEY,
  stfname VARCHAR(50) NOT NULL,
  stlname VARCHAR(50) NOT NULL,
  stcontact VARCHAR(15) NOT NULL,
  stbirthdate DATE NOT NULL,
  stgender CHAR(1) NOT NULL
);

INSERT INTO Student (stud_ID, stfname, stlname, stcontact, stbirthdate, stgender)
VALUES (1001, 'John', 'Doe', '123-456-7890', '1990-01-01', 'M'),
       (1002, 'Jane', 'Smith', '987-654-3210', '1995-05-15', 'F'),
       (1003, 'Mike', 'Lee', '555-123-4567', '2000-10-20', 'M');

CREATE TABLE Staff (
  staff_ID INT PRIMARY KEY,
  fname VARCHAR(50) NOT NULL,
  lname VARCHAR(50) NOT NULL,
  address VARCHAR(100) NOT NULL,
  contact VARCHAR(15) NOT NULL,
  gender CHAR(1) NOT NULL
);

INSERT INTO Staff (staff_ID, fname, lname, address, contact, gender)
VALUES (2001, 'Alice', 'Johnson', '1 Main St', '555-555-5555', 'F'),
       (2002, 'Bob', 'Williams', '123 Any St', '888-888-8888', 'M');

CREATE TABLE Courses (
  course_ID INT PRIMARY KEY,
  coursename VARCHAR(50) NOT NULL,
  department VARCHAR(50) NOT NULL
);

INSERT INTO Courses (course_ID, coursename, department)
VALUES (3001, 'Introduction to Programming', 'Computer Science'),
       (3002, 'Database Management Systems', 'Information Technology'),
       (3003, 'Calculus I', 'Mathematics');

CREATE TABLE Registration (
  stud_ID INT NOT NULL,
  course_ID INT NOT NULL,
  registration_date DATE NOT NULL,
  FOREIGN KEY (stud_ID) REFERENCES Student(stud_ID),
  FOREIGN KEY (course_ID) REFERENCES Courses(course_ID),
  PRIMARY KEY (stud_ID, course_ID)
);

INSERT INTO Registration (stud_ID, course_ID, registration_date)
VALUES (1001, 3001, '2024-01-10'),
       (1002, 3002, '2024-02-05'),
       (1003, 3003, '2024-03-15');

CREATE TABLE Reports (
  report_ID INT PRIMARY KEY,
  name VARCHAR(50) NOT NULL,
  staff_ID INT NOT NULL,
  project VARCHAR(50) NOT NULL,
  date DATE NOT NULL,
  FOREIGN KEY (staff_ID) REFERENCES Staff(staff_ID)
);


INSERT INTO Reports (report_ID, name, staff_ID, project, date)
VALUES (4001, 'Progress Report', 2001, 'Database Design', '2024-04-08');

CREATE TABLE Transactions (
  trans_ID INT PRIMARY KEY,
  stud_ID INT NOT NULL,
  staff_ID INT NOT NULL,
  transaction_date DATE NOT NULL,
  course_line VARCHAR(50) NOT NULL,
  FOREIGN KEY (stud_ID) REFERENCES Student(stud_ID),
  FOREIGN KEY (staff_ID) REFERENCES Staff(staff_ID)
);

-- Sample data for Transactions (you can add more transactions as needed)
INSERT INTO Transactions (trans_ID, stud_ID, staff_ID, transaction_date, course_line)
VALUES (5001, 1001, 2002, '2024-04-05', 'Book purchase for Introduction to Programming');

CREATE TABLE Attendance (
  attendance_ID INT PRIMARY KEY,
  stud_ID INT NOT NULL,
  course_ID INT NOT NULL,
  attend_date DATE NOT NULL,
  status VARCHAR(10) NOT NULL,  -- Present, Absent, Excused
  FOREIGN KEY (stud_ID) REFERENCES Student(stud_ID),
  FOREIGN KEY (course_ID) REFERENCES Courses(course_ID)
);


INSERT INTO Attendance (attendance_ID, stud_ID, course_ID, attend_date, status)
VALUES (2401, 1001, 3001, '2024-04-01', 'Present'),
       (2402, 1001, 3001, '2024-04-03', 'Present'),
       (2403, 1001, 3001, '2024-04-05', 'Absent'),
       (2404, 1002, 3002, '2024-04-02', 'Present'),
       (2405, 1002, 3002, '2024-04-04', 'Excused');
       
SELECT
    stud_ID,
    course_ID,
    ROUND((SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2) AS attendance_percentage
FROM
    Attendance
GROUP BY
    stud_ID,
    course_ID;

DELIMITER //
CREATE TRIGGER CheckAttendance
AFTER INSERT ON Attendance
FOR EACH ROW
BEGIN
    DECLARE total_classes INT;
    DECLARE attended_classes INT;
    DECLARE attendance_percent DECIMAL(5,2);

    SELECT COUNT(*) INTO total_classes
    FROM Attendance
    WHERE course_ID = NEW.course_ID;

    SELECT COUNT(*) INTO attended_classes
    FROM Attendance
    WHERE course_ID = NEW.course_ID AND stud_ID = NEW.stud_ID AND status = 'Present';

    SET attendance_percent = (attended_classes / total_classes) * 100;

    IF attendance_percent < 75 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = '75% attendance is mandatory for this course';
    END IF;
END;
//
DELIMITER ;
select * from Attendance;
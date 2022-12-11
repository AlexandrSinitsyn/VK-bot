CREATE TABLE Student (
    Id int not null PRIMARY KEY ,
    Name varchar(255) not null ,
    IsStudent varchar(1) not null ,
);

CREATE TABLE Homework (
    Number int not null ,
    Deadline date not null ,
);

CREATE TABLE Results (
  HwId int REFERENCES Homework(Number) not null PRIMARY KEY ,
  StudentId int REFERENCES Student(Id) not null ,
  Mark int not null ,
);

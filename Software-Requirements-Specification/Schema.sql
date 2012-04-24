create table TablePrefix_Client(
	id	integer auto_increment,
		primary key(id),
	memberIdentifier varchar(255) not null,
	firstName	varchar(255) not null,
	lastName	varchar(255) not null,
	dateOfBirth	date not null,
	sex	char not null,
	physicalAddress	varchar(255) not null,
	phoneNumber	varchar(255) not null,
	email	varchar(255) not null unique
);

create table TablePrefix_User(
	id	integer auto_increment,
		primary key(id),
	username	varchar(255) not null unique,
	md5Password	char(32) not null
);

create table TablePrefix_Bike(
	id	integer auto_increment,
		primary key(id),
	bikeIdentifier varchar(255) not null,
	vendorName	varchar(255) not null,
	modelName	varchar(255) not null,
	serialNumber	varchar(255) not null,
	acquisitionDate	date not null
);

create table TablePrefix_Repair(
	id	integer auto_increment,
		primary key(id),
	creationTime	datetime not null,
	bikeIdentifier	integer not null,
		index bikeIdentifier_index (bikeIdentifier),
		foreign key (bikeIdentifier) references TablePrefix_Bike(id),
	description	varchar(255) not null,
	employeePersonIdentifier	integer not null,
		index employeePersonIdentifier_index (employeePersonIdentifier),
		foreign key (employeePersonIdentifier) references TablePrefix_Person(id),
	repairIsCompleted	bool not null,
	completionTime	datetime not null
);

create table TablePrefix_Loan(

	id	integer auto_increment,
		primary key(id),
	bikeIdentifier	integer not null,
		index bikeIdentifier_index (bikeIdentifier),
		foreign key (bikeIdentifier) references TablePrefix_Bike(id),
	employeePersonIdentifier	integer not null,
		index employeePersonIdentifier_index (employeePersonIdentifier),
		foreign key (employeePersonIdentifier) references TablePrefix_Person(id),
	clientPersonIdentifier integer not null,
		index clientPersonIdentifier_index (clientPersonIdentifier),
		foreign key (clientPersonIdentifier) references TablePrefix_Person(id),
	startingDate	datetime not null,
	expectedEndingDate	datetime not null,
	actualEndingDate	datetime not null

);

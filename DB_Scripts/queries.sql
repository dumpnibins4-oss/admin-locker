CREATE TABLE admin_locker_restrictions(
	id INT PRIMARY KEY IDENTITY(1,1),
	biometrics_id NVARCHAR(255),
	role NVARCHAR(50)
)


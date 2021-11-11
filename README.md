# snowtricks
Snowboard community site made with Symfony 5.

## INSTALLATION

### **Download or clone**
Download zip files or clone the project repository with github.

### **Configure environment variables**
1.  Open the *env.local.example* file at the project's root folder
2.  Replace the example values with your own values (new database name and your mailtrap)
3.  Rename the file *env.local*

### **Install the project**
1.  Install **Composer** by following ([the official instructions](https://getcomposer.org/download/)).
2.  Go to the project directory in your cmd:
```shell
cd some\directory
```
3.  Install dependencies with the following command:
```shell
composer install
```
Dependencies should be installed in your project

### **Create the database**
1.  Starting the SQL Server
2.  Create the new MySQL Database
```shell
php bin/console doctrine:database:create
```
3. Create database structure with migrations
```shell
php bin/console doctrine:migrations:migrate
```
4.  Install fixtures
```shell
php bin/console doctrine:fixtures:load
```
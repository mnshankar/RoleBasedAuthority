Laravel4 - RoleBasedAuthority
=============================
This is a simple package that provides role-based access control (RBAC) for Laravel4 applications.

It is very similar to Authority-L4 in that it provides a Laravel 4 shell around the "Authority" 
package. This package however focuses on managing permissions assigned to "roles" rather than "users".

After examining a whole host of authorization systems for Laravel, I finally settled on Authority
* Easy to understand and manage
* No other external dependencies
* Runs on PHP 5.3+
* Works exactly as advertised

(As far as authentication is concerned, I believe the stock laravel implementation works perfectly)

To install RoleBasedAuthority via composer:

```
"require": {
		"laravel/framework": "4.0.*",
		"mnshankar/role-based-authority": "dev-master"
	},
```
Run composer update to bring in the dependencies.

Add to Provider list (app.php):
```
'mnshankar\RoleBasedAuthority\RoleBasedAuthorityServiceProvider',
```
Add to Alias list (app.php):
```
'Authority'        => 'mnshankar\RoleBasedAuthority\Facades\Authority',
```
Run all migrations (remember to setup your database config prior to doing this):
```
php artisan migrate --package="mnshankar/role-based-authority"
```
Publish the configuration file:
```
php artisan config:publish mnshankar/role-based-authority
```
(this central config file is where you would put all your rules)

Create/Modify your User, Role and Permission models to permit relationships
(many-to-many between users and roles, one-to-many between roles and permissions):
User.php
--------
```
...
public function roles() {
		return $this->belongsToMany('Role');
	}
...
```
Role.php
--------
```
class Role extends Eloquent
{
	public function permissions() {
		return $this->hasMany('Permission');
	}
}
```	
Permission.php
--------------
```
class Permission extends Eloquent
{
	
}
```
The major changes from Authority are:
* DB migrations (include relationships between users,roles and permissions)
* The "type" field in table "permissions" is set to enum (allow/deny)
* Support for role inheritance (using the "inherited_roleid" column in Roles)
* Config file changes (to loop through all user roles, and create rule list)

Common usage pattern:

Once you have the tables setup (with users, roles and permissions), checking authorization 
within your application is fairly trivial. The following snippet of code demonstrates 
my preferred method of checking for appropriate privileges:

```
if (Authority::cannot('action','resource'))
    {
        App::abort(403, 'You are not authorized.');
    }
//else.. proceed with logic
    
```    
For more instructions on usage and available options, please follow the 
writeup on authority-l4 (and authority)

https://github.com/machuga/authority-l4

https://github.com/machuga/authority
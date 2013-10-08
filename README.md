Laravel4 - RoleBasedAuthority
=============================
This is a simple package that provides role based access control for Laravel4 applications.

It is very similar to Authority-L4 in that it provides a Laravel 4 shell around the "Authority" 
package. This package however focuses on managing permissions assigned to "roles" rather than users.

After examining a whole host of authorization systems for Laravel, I settled on Authority
* Easy to understand and manage
* Runs on PHP 5.3+
* Works exactly as advertised
 
To install RoleBasedAuthority via composer:

```
"require": {
		"laravel/framework": "4.0.*",
		"mnshankar/role-based-authority": "dev-master"
	},
```
Add to Provider list (app.php):
```
'mnshankar\RoleBasedAuthority\RoleBasedAuthorityServiceProvider',
```
Add to Alias list (app.php):
```
'Authority'        => 'mnshankar\RoleBasedAuthority\Facades\Authority',
```
Run all migrations:
```
php artisan migrate --package="mnshankar/role-based-authority"
```
Publish the configuration file:
```
php artisan config:publish mnshankar/role-based-authority
```
(this central config file is where you would put all your rules)

The major changes from Authority are:
* DB migrations (include one to many relationship between roles and permissions)
* Support for role inheritance (using the "inherited_roleid" column in Roles)
* Config file changes (to loop through all user roles, and create rule list)

Common usage pattern:

Once you have the tables setup (with users, roles and permissions), checking authorization 
within your application is failry trivial. The following snippet of code demonstrates 
my preferred method of checking for appropriate privileges:

```
if (Authority::cannot('action','resource'))
    {
        App::abort(401, 'You are not authorized.');
    }
//else.. proceed with logic
    
```    
For more instructions on usage, please follow the writeup on authority-l4 (and authority)

https://github.com/machuga/authority-l4
https://github.com/machuga/authority

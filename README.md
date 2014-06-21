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
		"laravel/framework": "4.2.*",
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
Run all migrations (Remember to setup your database config prior to doing this. Note that it contains a migration for the users table, and as such you might want to
inspect the code prior to running):
```
php artisan migrate --package="mnshankar/role-based-authority"
```
Publish the configuration file:
```
php artisan config:publish mnshankar/role-based-authority
```
(this central config file is where you would put all your rules. In order to cache the 
"Authority" object, set 'cache' to true and specify cache_ttl)

Create/Modify your User, Role and Permission models to permit relationships
(many-to-many between users and roles, one-to-many between roles and permissions):
User.php
--------
```
...
public function roles() {
		return $this->belongsToMany('Role');
	}
public function hasRole($key)
    {
        foreach ($this->roles as $role) {            
            if ($role->role_name === $key) {
                return true;
            }
        }        
        return false;
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
* Support for caching the Authority object (set 'cache' to true in config file)

Common usage pattern:

Once you have the tables setup (with users, roles and permissions), checking authorization within your application is fairly trivial.
Here is some seed data to get you started:
```
--create a user (pwd must be hashed!)
INSERT INTO users VALUES ('1', 'LastName', 'FirstName', 'email@email.com', 'hashedpwd****', now(), now());

-- Establish roles. member inherits from guest and admin inherits from member
INSERT INTO roles VALUES ('1', 'guest', null, now(), now());
INSERT INTO roles VALUES ('2', 'member', '1', now(), now());
INSERT INTO roles VALUES ('3', 'admin', '2', now(), now());

-- Assign roles to users. Userid 1 has 'admin' role
INSERT INTO role_user(role_id, user_id) VALUES ('3', '1');

-- Setup Permissions table
-- ----------------------------
-- Role id 3 (admin) is allowed action "admin" on "all" resources
-- NOTE: 'all' - is a special resource that can be used as a wildcard
-- https://github.com/machuga/authority/blob/master/src/Authority/Rule.php#L101
-- Role id 2 (member) is allowed "create", "view" and "edit" actions on resource named 'album'
-- ----------------------------
INSERT INTO permissions VALUES ('1', '3', 'allow', 'admin', 'all', now(), now());
INSERT INTO permissions VALUES ('2', '2', 'allow', 'create', 'album', now(), now());
INSERT INTO permissions VALUES ('3', '2', 'allow', 'view', 'album', now(), now());
INSERT INTO permissions VALUES ('4', '2', 'allow', 'edit', 'album', now(), now());
```
The following snippets of code demonstrate methods of checking for appropriate privileges:
```
/***Check in controller - create() method of resource album***/
//checks if the logged in user has authority to create an album
//if person with admin or member role is logged in, they will be permitted. Not otherwise

Auth::loginUsingId(1);  //force login - testing only
if (Authority::cannot('create', 'album')) {
       App::abort('403', 'Sorry! Not authorized');
}

```    
Adding a role-check can be accomplished using filters like so:
```
Route::filter('admin', function(){
    if (!Auth::user()->hasRole('admin'))
    {
        return App::abort('403', 'You are not authorized.');
    }
});
```
('admin' can then be used as a before filter)

For more instructions on usage and available options, please follow the
writeup on authority-l4 (and authority)

https://github.com/machuga/authority-l4

https://github.com/machuga/authority
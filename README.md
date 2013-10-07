Laravel4 - RoleBasedAuthority
=============================
This is a simple package that provide role based access control for Laravel4 applications.

It is very similar to Authority-L4 but focuses on managing permissions assigned to 
user "roles" instead of users.

https://github.com/machuga/authority-l4

To install via composer:

```
"require": {
		"laravel/framework": "4.0.*",
		"mnshankar/role-based-authority": "dev-master"
	},
```
Add to Provider list:
```
'mnshankar\RoleBasedAuthority\RoleBasedAuthorityServiceProvider',
```
Add to alias list:
```
'Authority'        => 'mnshankar\RoleBasedAuthority\Facades\Authority',
```

The major changes from Authority are:
1. DB migrations (include one to many relationship between roles and permissions
1. Support for role inheritance (using "inherited_roleid" column in Roles)
1. Config file changes (to loop through all user roles, and add rule list)

For all other instructions on usage, please follow the writeup on authority-l4 (and authority)

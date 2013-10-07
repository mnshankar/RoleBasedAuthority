RoleBasedAuthority
==================
Very similar to Authority-L4 but focuses on user roles instead of users.

https://github.com/machuga/authority-l4

To install via composer:

```
"require": {
		"laravel/framework": "4.0.*",
		"mnshankar/role-based-authority": "dev-master"
	},
```

The major changes are
1. DB migrations (include one to many relationship between roles and permissions
2. Support for role inheritance (using "inherited_roleid" in Roles)
3. Config file changes to loop through all user roles

For all other instructions on usage, please follow the writeup on authority-l4 (and authority)

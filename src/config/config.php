<?php
return array (
		
		'initialize' => function ($authority) {
			$user = $authority->getCurrentUser ();
			
			$authority->addAlias ( 'manage', array (
					'create',
					'read',
					'update',
					'delete' 
			) );
			$authority->addAlias ( 'moderate', array (
					'read',
					'update',
					'delete' 
			) );
			//$authority->allow ( 'manage', 'all' );
            $setRules = function ($authority, $role)
            {
                foreach ($role->permissions as $perm) {
                    if ($perm->type == 'allow') {
                        $authority->allow($perm->action, $perm->resource);
                    } else {
                        $authority->deny($perm->action, $perm->resource);
                    }
                }
            };
			
			// loop through each of the users permissions, and create rules
			foreach ( $user->roles as $role ) {
				if ($role->inherited_roleid) {					
					$inherited = Role::find ( $role->inherited_roleid );
					$setRules($authority, $inherited);					
				}
				$setRules($authority, $role);				
			}
		
		} 
);

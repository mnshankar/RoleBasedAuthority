<?php
return array(
    'cache'=>false,
    'cache_ttl'=>5, //minutes 0= forever
    'initialize' => function ($authority)
    {
        $user = $authority->getCurrentUser();
        
        $authority->addAlias('manage', array(
            'create',
            'read',
            'update',
            'delete'
        ));
        $authority->addAlias('moderate', array(
            'read',
            'update',
            'delete'
        ));
        //ORDER OF RULES IS IMPORTANT
        //put your custom rules here.. note that they will be superceded by 
        //rules dictated in your roles table.
        
        // $authority->allow ( 'manage', 'all' );
        
        //do the below only if there is a logged in user
        if ($user) {
            //this is an internal function that DRY's up rule creation
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
            
            // loop through each of the users roles, and create rules based on 
            // whether or not there is an inherited role involved
            foreach ($user->roles as $role) {
                if ($role->inherited_roleid) {
                    $inherited = Role::find($role->inherited_roleid);
                    $setRules($authority, $inherited);
                }
                $setRules($authority, $role);
            }
        }       
    }
);

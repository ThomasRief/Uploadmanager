<?php

/**
 * Überprüft, ob Nutzer die nötigen Berechtigungen hat.
 * @param string $neededPermGroup die nötigen Berechtigungen
 * @param string $userPermGroup die Nutzerberechtigungen
 * 
 * @return boolen TRUE, wenn er die Rechte hat, FALSE wenn nicht
 */
function hasPermission( $neededPermGroup, $userPermGroup ) {
	
	$permGroups = 
	array(
		'user' => array(
			'user' => TRUE,
			'admin' => FALSE ),
		'admin' => array(
			'user' => TRUE,
			'admin' => TRUE ) );
	
	foreach( $permGroups as $groupName => $permission ) {
		
		// search for right permission group
		if( $groupName == $userPermGroup ) {
			$foundGroup = TRUE;
			
			// test permissions
			foreach( $permission as $perm => $value ) {
				
				// test for searched permission
				if( $perm == $neededPermGroup ) {
					$neededPermFound = TRUE;
					
					if( $value == TRUE ) {
						
						return TRUE;
						
					} else {
						
						return FALSE;
					}
				}
			}
		}
	}
}

/// check for attributes
	//user permission
	if( isset( $attributes['userPerm'] ) ) {
		
		$userPerm = $attributes['userPerm'];

	} else {
		
		$userPerm = 'user';
	}
	
/// generate links
	if( isset( $attributes['links'] ) && isset( $attributes['selectedPage'] ) ) {
		
		// set some vars
		$selected = $attributes['selectedPage'];
		$links = $attributes['links'];
		
		// get all groups and the group with selected page
		$groups = array();
		foreach( $links as $groupName => $subLinks ) {
			
			$groups[$groupName] = FALSE;
			foreach( $subLinks as $subs ) {
				foreach( $subs as $linkName => $attr ) {
					
					if( $attr['id'] == $selected ) {
						
						$groups[$groupName] = TRUE;
					}
				}
			}
		}
		
		// second foreach - get actuall HTML
		$groupsHTML = '';
		$sublinksHTML = '';
		foreach( $groups as $groupName => $isSelected ) {
			
			// some needed informations about the first link into this group
			$firstLink = $links[$groupName]['links'][ array_keys( $links[$groupName]['links'] )[0] ]['href'];
			$firstLinkID = $links[$groupName]['links'][ array_keys( $links[$groupName]['links'] )[0] ]['id'];
			$firstLinkPerm = $links[$groupName]['links'][ array_keys( $links[$groupName]['links'] )[0] ]['perm'];
			
			// test if user has permission to enter this page
			if( hasPermission( $firstLinkPerm, $userPerm ) ) {
				
				$classes = 'headerGroupLink';
				// test if this group is selected
				if( $isSelected == TRUE ) {
					
					$classes .= ' selected';
					
					foreach( $links[$groupName]['links'] as $linkName => $attr ) {
						
						// some informations of the link
						$href = $attr['href'];
						$perm = $attr['perm'];
						$id = $attr['id'];
						
						// test if user has permission to enter this page
						if( hasPermission( $perm, $userPerm ) ) {
							
							$subClasses = 'sidebarLink';
							if( $selected == $id ) {
								
								$subClasses .= ' subSelected';
							}
							
							$sublinksHTML .= '<a href="'.$href.'" class="'.$subClasses.'">'.$linkName.'</a>';
						
						} elseif( $selected == $id ) {
							
							header( 'Location: dashboard.index.php?noPerm' );
							
						} 
					}
				}
				
				$groupsHTML .= '<a class="'.$classes.'" href="'.$firstLink.'">'.$groupName.'</a>';
			
			} elseif( $firstLinkID == $selected ) {
			
				header( 'Location: dashboard.index.php?noPerm' );
			}
		}
	
	} else {
		
		$groupsHTML = '';
		$sublinksHTML = '';
	}

/// actuall template
$template = 
'<div id="headerFrame">
	<div id="topRow">
	
		<a href="dashboard.index.php" class="headerProjectTitle">Uploadmanager</a>
		<div id="loggedInUser">
			Willkommen <span class="userName">'.$getHook( 'menu_username' ).'</span>! | 
			<a href="logout.php" class="loggoutLink">Abmelden</a>
		</div>
		
		<div class="clear"></div>
		
	</div>
	<div id="bottomRow">
		
		'.$groupsHTML.'
		
	</div>
	<div id="sideMenu">
		
		'.$sublinksHTML.'
		
	</div>
</div>';

?>
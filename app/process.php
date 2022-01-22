<?php
	if( isset( $_SESSION['auth'] ) && $_SESSION['level']  == 1 ) {
		if( isset( $_GET['accept'] ) )
			$database -> update( "t_order", [ "action" => 1 ], [ "id" => $_GET['accept'] ] );
		if( isset( $_GET['done'] ) )
			$database -> update( "t_order", [ "action" => 2 ], [ "id" => $_GET['done'] ] );
		if( isset( $_GET['paid'] ) )
			$database -> update( "t_order", [ "action" => 3 ], [ "id" => $_GET['paid'] ] );
		if( isset( $_GET['reject'] ) )
			$database -> delete( "t_order", [ "id" => $_GET['reject'] ] );
	}

<?php
	if( isset( $_SESSION['auth'] ) && ! $_SESSION['level'] ) {
		// jeśli podano adres i numer telefonu
		if( isset( $_POST['address'] ) && isset( $_POST['phone'] ) ) {
			$pizzas = $database -> select( 't_pizza', [ 'id', 'name' ] );

			foreach( $pizzas as $pizza )
				// jeśli podano ilość produktów
				if( $_POST[ $pizza['id'] ] ) $database -> insert( "t_order", [ "pizza_id" => $pizza['id'], "count" => $_POST[ $pizza['id'] ], "address" => $_POST['address'], "phone" => $_POST['phone'] ] );

			echo file_get_contents( './template/registered.htm' );
		}
	}

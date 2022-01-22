<?php
	session_start();

	include( './template/head.htm' );

	require( "./config.php" );

	require( "./lib/Medoo.php" ); use Medoo\Medoo;

	$pdo = new PDO( 'mysql:host='. $db_host .';dbname='. $db_name, $db_user, $db_password );

	$database = new Medoo([
		'pdo' => $pdo,
		'type' => 'mysql'
	]);

	require( './app/auth.php' );
       
        
	if( ! isset( $_SESSION['auth'] ) ) include( './template/login.htm' );
	else {
                include('./template/top.htm');
		switch( $_SESSION['level'] ) {
			case FALSE:
				$menu = file_get_contents( './template/menu.htm' );
				$item = file_get_contents( './template/item.htm' );

				// pobierz wszystkie dostępne produkty
				$order = '';
				$items = $database -> select( 't_pizza', [ 'id', 'name' ] );
				foreach( $items as $option ) {
					$tmp = str_replace( '{id}', $option['id'], $item );
					$order .= str_replace( '{name}', ucfirst( $option['name'] ), $tmp );
				}

				echo str_replace( '{items}', $order, $menu );

				require( './app/register.php' );

				break;
			default:
				require( './app/process.php' );
				
				$accepted = '';
				$todo = '';
				$drivers = '';
				$archived = '';

				$orders = $database -> select( "t_order",
					[ "[><]t_pizza" => [ "pizza_id" => "id" ] ],
					[ "t_order.id", "t_order.action", "t_pizza.name", "t_order.count", "t_order.address", "t_order.phone" ]
				);

				foreach( $orders as $order ) {
					switch( $order['action'] ) {
						case 0:
							$tmp = file_get_contents( './template/accepted.htm' );
							$tmp = str_replace( '{id}', $order['id'], $tmp );
							$tmp = str_replace( '{name}', ucfirst( $order['name'] ), $tmp );
							$tmp = str_replace( '{count}', $order['count'], $tmp );
							$tmp = str_replace( '{address}', $order['address'], $tmp );
							$accepted .= str_replace( '{phone}', $order['phone'], $tmp );

							break;
						case 1:
							$tmp = file_get_contents( './template/todo.htm' );
							$tmp = str_replace( '{id}', $order['id'], $tmp );
							$tmp = str_replace( '{name}', ucfirst( $order['name'] ), $tmp );
							$tmp = str_replace( '{count}', $order['count'], $tmp );
							$tmp = str_replace( '{address}', $order['address'], $tmp );
							$todo .= str_replace( '{phone}', $order['phone'], $tmp );

							break;
						case 2:
							$tmp = file_get_contents( './template/drivers.htm' );
							$tmp = str_replace( '{id}', $order['id'], $tmp );
							$tmp = str_replace( '{name}', ucfirst( $order['name'] ), $tmp );
							$tmp = str_replace( '{count}', $order['count'], $tmp );
							$tmp = str_replace( '{address}', $order['address'], $tmp );
							$drivers .= str_replace( '{phone}', $order['phone'], $tmp );

							break;
						default:
							$tmp = file_get_contents( './template/archived.htm' );
							$tmp = str_replace( '{id}', $order['id'], $tmp );
							$tmp = str_replace( '{name}', ucfirst( $order['name'] ), $tmp );
							$tmp = str_replace( '{count}', $order['count'], $tmp );
							$tmp = str_replace( '{address}', $order['address'], $tmp );
							$archived .= str_replace( '{phone}', $order['phone'], $tmp );
					}
				}

				// montuj się
				$accepted = str_replace( '{entries}', $accepted, file_get_contents( './template/orders.htm' ) );
				$todo = str_replace( '{entries}', $todo, file_get_contents( './template/orders.htm' ) );
				$drivers = str_replace( '{entries}', $drivers, file_get_contents( './template/orders.htm' ) );
				$archived = str_replace( '{entries}', $archived, file_get_contents( './template/orders.htm' ) );

				// generuj wynik
				$tmp = str_replace( '{accept}', $accepted, file_get_contents( './template/admin.htm' ) );
				$tmp = str_replace( '{todo}', $todo, $tmp );
				$tmp = str_replace( '{drivers}', $drivers, $tmp );
				echo str_replace( '{archive}', $archived, $tmp );
		}
	include('./template/bottom.htm');
                
        }

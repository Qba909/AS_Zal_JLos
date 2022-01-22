<?php
	// wyloguj
	if( isset( $_GET['logout'] ) ) unset( $_SESSION['auth'] );

	// jeśli wypełniono formularz panelu logowania
	if( isset( $_POST['login'] ) && isset( $_POST['password'] ) ) {
		// pobierz poziom uprawnień konta na podstawie danych logowania
		$level = $database -> get( "t_account", "level", [ "login" => $_POST['login'], "passwd" => $_POST['password'] ] );

		// konto istnieje?
		if( isset( $level ) ) {
			$_SESSION['auth'] = TRUE;
			$_SESSION['level'] = $level;
		}
	}

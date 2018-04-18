<?php
	function lang($phrase){
		static $lang = array(
			'hello' => 'Salut',
			'Settings'=>'Paramètres',
			'Logout'=>'Se déconnecter',
			'login_to_app'=>'Se connecter',
			'app_title'=>'Plateforme de gestion de flote',
			'intro_user'=>'s\'identifier',
			'login_error'=>'mot de passe incorrect',
			'login'=>'Se connecter',
			'username'=>'Nom d\'utilisateur',
			'user_password'=>'mot de passe',
			'menu'=>'menu',
			'liste_des_vehicules'=>'La liste de véhicules',
			'statistique'=>'statistique',
			'vehicule'=>'véhicule',
			'Dashboard'=>'Tableau de bord',
			'chauffeur'=>'chauffeur',
			'km_depart'=>'km départ',
			'h_depart'=>'heure départ',
			'carburant'=>'carburant',
			'camion_marque'=>'marque',
			'camion_matricule'=>'matricule',
			'camion_designation'=>'Désignation',
			'driver'=>'chauffeur',
			'Close'=>'Fermer',
			'ok'=>'Confirmer',
			/***********************amina**************************/


			'menu'=>'menu',
			'liste_des_vehicules'=>'La liste de véhicules',
			'statistique'=>'statistique',
			'vehicule'=>'véhicule',
			'Dashboard'=>'Tableau de bord',

			//////////fish amina
			'Message' => 'Bienvenu',
			'Admin' => 'Admin',
			'Home' => 'Accueil',
			'customize_Profile' => 'Paramètres',
			'Logout' => 'Se deconnecter',
			'initial_menu' => 'Menu principal',
			'step1_next' => 'Suivant',
			'Choose.' => 'Rechercher',

			'search.' => 'Recherche par',
			'enter_here' => 'Recherche',
			'search_word' => 'Recherche',
			'marhaba'=>'Bienvenu',
			'login_to_app'=>'Se connecter',
			'login'=>'Se connecter',
			'username'=>'Login',
			'user_password'=>'Mot de passe',
			'Accueil'=>'Accueil',
			'gestion_camions'=>'Gestion des camions',
			'Contact'=>'Contacter nous',

			'print'=>'Imprimer',

			'delete_operation'=>'Suppression',
			'update'=>'Modifier',
			'Close'=>'Fermer',

			'ok'=>'Oui',
			'Cancel'=>'Annuler',


			'Annuler'=>'Annuler',
			'Ajouter'=>'Ajouter',
			'AucunResultat'=>'Aucun resultat',

			'Periode'=>'Période  ',
			'de'=>'de',
			'a'=>'à  ',
			'patienter' =>" Merci de patienter ...",
			'Enregistrer' => "Enregistrer",
			'Fermer' => "Fermer",

			'messageAjoutSucces' => "Opération réussie",
			'Operation' => "Opération réussie",
			'terminerOperation' => "Voulez-vous vraiments enregistrer les données ? ",
			'Confirm' => "Confirmer",

			'Statistic' => "Statistiques",

			'Graphe' => "Graphe",
			'Tableau' => "Tableau",

			'From' => "de  ",
			'To' => "à",

			'Footer' => "Copyright © 2018",
			'gestion_chauf' => "Gestion des chauffeurs",
			'RemplirChp'=>'Merci de verifier les données',
			'Marque'=>'Marque',
			'Matricule'=>'Matricule',
			'Tare'=>'Tare',
			'Designation'=>'Designation',
			'Modifier'=>'Modifier',
			'DateEmb'=>'Date embauche',
			'CIN'=>'CIN',
			'Nom'=>'Nom',


			'EtatChauf'=>'Etat des chauffeurs',
			'EtatCamion'=>'Etat des camions',
			'Tous'=>'Tous',
			'Libre'=>'Libre',
			'Affecter'=>'Affecter',
			'Demarer'=>'Démarer',


			//************ youssef 16-04-2018
			'etat_actuel'=>'l\'état actuel',
			'no_data'=>'ce camion n\'a aucune donnée',
			'no_clients_data'=>'il n\'y a aucune donnée des clients'
			//*******************

		);
		return $lang[$phrase];
	}

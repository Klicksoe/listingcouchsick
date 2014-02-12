<?php
/**
 * Classe pour la gestion des logs utilisateur
 * @author mbettayeb
 *
 */
class Logger{

	private static $instance;// instance de la classe
	private $cheminLogs="logs/";//endroit ou se trouve les logs de debug (par defaut logs/)
	private $niveauDebug=0;//Niveau de debug actuel

	//constantes valeur debug
	const ERROR=1;
	const WARNING=2;
	const DEBUG=3;


	private $fichier=null;//nom du fichier de log


	/**
	 *
	 * Constructeur privé
	 */
	private function __construct(){
	}

	/**
	 * clonage impossible
	 */
	public function __clone(){
		 
	}


	/**
	 * creation d'une instance de la classe Logger (Singleton)
	 */
	public static function getInstance($nivDebug,$cheminLogs=null) {
		if (!isset(self::$instance)){
			self::$instance = new Logger();
			if(empty($nivDebug))
			self::$instance->niveauDebug=0;
			else
			self::$instance->niveauDebug=$nivDebug;//niveau de debug

			if($cheminLogs!=null){
				self::$instance->cheminLogs=$cheminLogs;//chemin des logs
				self::$instance->fichier=$cheminLogs."log_";//nom du fichier
			}
		}
		return self::$instance;
	}


	/**
	 *
	 * Set du niveau de debug
	 *
	 */
	public function setNiveauDebug($niv){
		$this->niveauDebug=$niv;
	}

	/**
	 *
	 * Get du niveau de debug
	 *
	 */
	public function getNiveauDebug(){
		return $this->niveauDebug;
	}

	/**
	 *
	 * Fonction generique d'ecriture d'un log
	 *
	 */
	private function log($type,$log,$user){
		//ouverture du fichier en lecture ecriture(creation si necessaire)
		//fichier de type fichierAAAAMMJJ.log
		$fic=fopen($this->fichier.date("Ymd").".log","a+");

		if($fic){
			$ligne="[".$user." ".date("H:i:s")."][".$type."]>".$log;
			fwrite($fic,$ligne. "\n");// ecriture du log
			fclose($fic);// Fermeture du fichier
		}
	}

	/**
	 *
	 * ecriture d'un log de type DEBUG
	 */
	public function debug($log,$user){
		if($this->niveauDebug>= self::DEBUG)//si niveau de debug correspond a DEBUG
		self::log("DEBUG",$log,$user);
	}


	/**
	 *
	 * ecriture d'un log de type WARNING
	 */
	public function warning($log,$user){
		if($this->niveauDebug>= self::WARNING)//si niveau de debug correspond a WARNING
		self::log("WARNING",$log,$user);
	}

	/**
	 *
	 * ecriture d'un log de type ERROR
	 */
	public function error($log,$user){
		if($this->niveauDebug >= self::ERROR)//si niveau de debug correspond a ERROR
		self::log("ERROR",$log,$user);
	}

}
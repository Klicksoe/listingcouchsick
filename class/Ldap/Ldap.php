<?php

include_once 'class/Ldap/Organigramme.php';
include_once 'class/Ldap/Site.php';
include_once 'class/Ldap/User.php';
include_once 'class/Ldap/Logger.php';


class Ldap {

    // d�finition des variables
    private $ldap_connect;
    private $ldap_bind;
    private $server = array();
    private $log;
    const ORGA = 0;
    const POLE = 2;
    const DIRECTION = 4;
    const SERVICE = 6;
    
    // constructeur servant � se connecter au ldap
    public function __construct($server, $port, $login_domain, $login_user, $login_pass, $ldap_ou_user, $ldap_ou_bat, $ldap_ou_orga) {
    	
    
        try {
            // connexion au ldap
            $ldap_connect = ldap_connect($server, $port);
            if (!$ldap_connect) { throw new Exception("Impossible de se connecter au serveur LDAP."); }
        
            // initialisation des options du ldap
            ldap_set_option($ldap_connect, LDAP_OPT_PROTOCOL_VERSION, 3);
            
            $ldap_bind = ldap_bind($ldap_connect, $login_user, $login_pass);
            
            // test si la connexion s'est effectu�e
            if ($ldap_bind) {
                $this->ldap_connect = $ldap_connect;
                $this->server['server'] = $server;
                $this->server['port'] = $port;
                $this->server['login_domain'] = $login_domain;
                $this->server['login_user'] = $login_user;
                $this->server['login_pass'] = $login_pass;
                $this->server['ldap_ou_user'] = $ldap_ou_user;
                $this->server['ldap_ou_bat'] = $ldap_ou_bat;
                $this->server['ldap_ou_orga'] = $ldap_ou_orga;
                return true;
            } else {
                throw Exception("Connexion impossible au serveur LDAP.");
            }
        } catch (Exception $e) {
        	echo $e->getMessage();
            return false;
        }
    }
    
    /* ####################################################################################################
                                                        USER
    #################################################################################################### */
    
    // fonction de récupération d'un utilisateur par son samaccountname
    public function getUser($samaccountname) {
        
        try {
	        // recherche par le samaccountname
	        $rqc = $this->searchemptie($samaccountname,'samaccountname');
	        $ldapsearch = @ldap_search($this->ldap_connect, $this->server['ldap_ou_user'], $rqc);
	        
	        // return FALSE si pas d'entrée sinon retourne l'entr�e
	        $return = @ldap_get_entries($this->ldap_connect, $ldapsearch);
        } catch (Exception $e) {
        	echo $e->getMessage();	
        }
        
        if (!empty($return) && $return['count'] > 0) {
            $user = new User();
            $user->hydrateFromLdap($return[0]);
            return $user;
        } else {
            return false;
        }
    }
    
    // mise à jour d'un utilisateur
    public function updateUser($user) {
    	
        // vérification que le paramêtre passé est bien un objet de type User
        if ((is_object($user)) && ($user instanceof User)) {
        	
    		try {
	            $entry_modify = $user->prepareUpdateProfil();
	            $return = ldap_modify($this->ldap_connect, $user->getDn(), $entry_modify);
	            if ($return) {
	                return true;
	            } else {
	                return false;
	            }
	    	} catch (Exception $e) {
	    		echo $e->getMessage();
	    		return false;
	    	}
	    	
        } else {
            // La variable pass�e en param�tre n'est pas une classe de type User
            return false;
        }
    }
    
    // mise � jour d'un utilisateur
    public function updateUserAdmin($user) {
        // vérification que le paramêtre passé est bien un objet de type User
        if ((is_object($user)) && ($user instanceof User)) {
        	
        	try {
	            $entry_modify = $user->prepareUpdateAdmin();
	            $return = @ldap_modify($this->ldap_connect, $user->getDn(), $entry_modify);
	            if ($return) {
	                return true;
	            } else {
	                return false;
	            }
        	} catch (Exception $e) {
        		echo $e->getMessage();
        		return false;
        	}
        	
        } else {
            // La variable pass�e en param�tre n'est pas une classe de type User
            return false;
        }
    }
    
    // v�rifie qu'un user peut se connecter
    public function connectUser($user, $pass) {
    	
    	try {
    		// connexion au ldap avec les informations 
	        $ldap_connect = ldap_connect($this->server['server'], $this->server['port']) or die("Impossible de se connecter au serveur LDAP.");
            ldap_set_option($ldap_connect, LDAP_OPT_PROTOCOL_VERSION, 3);
	        $ldapbind = ldap_bind($ldap_connect, $this->server['login_domain'].'\\'.$user, $pass) or die("Connexion impossible au serveur LDAP.");
	        ldap_close($ldap_connect);
    	
    	} catch(Exception $e) {
    		echo $e->getMessage();
    	}
    	
        // vérification que la connexion s'est effectuée
        if ($ldapbind) {
            return true;
        } else {
            return false;
        }
    }
    
    // fonction de recherche d'un user par rapport � son nom, pr�nom ou nom de jeune fille
    public function searchUser($search) {
        $searchUser = array();
        
        // recherche par rapport � la valeur pass�e en param�tre
        $rqc = '(|(|'.$this->searchemptie('*'.$search.'*','sn').$this->searchemptie('*'.$search.'*', 'middlename').')'.$this->searchemptie('*'.$search.'*','givenname').')';
        $ldapsearch = @ldap_search($this->ldap_connect, $this->server['ldap_ou_user'], $rqc);
        
        @ldap_sort($this->ldap_connect, $ldapsearch, 'displayname');
        $info = @ldap_get_entries($this->ldap_connect, $ldapsearch);
        if ($info['count'] > 0) {
            for ($i = 0; $i < $info['count']; $i++) {
                $user = new User();
                $user->hydrateFromLdap($info[$i]);
                $searchUser[] = $user;
            }
            return $searchUser;
        } else {
            return false;
        }
    }
    
    // fonction de recherche des user par rapport aux codes identifiants de service
    public function getUserByOrganigramme($employeenumber, $employeeid) {
        $orgaUser = array();
        
        // recherche par rapport au samaccountname pass� en param�tre
		$rqc = '(&'.$this->searchemptie($employeenumber.'*','division').$this->searchemptie($employeeid.'*', 'employeeid').')';
        $ldapsearch = @ldap_search($this->ldap_connect, $this->server['ldap_ou_user'], $rqc);
        
        @ldap_sort($this->ldap_connect, $ldapsearch, 'displayname');
        $info = @ldap_get_entries($this->ldap_connect, $ldapsearch);
        if ($info['count'] > 0) {
            for ($i = 0; $i < $info['count']; $i++) {
                $user = new User();
                $user->hydrateFromLdap($info[$i]);
                $orgaUser[] = $user;
            }
            return $orgaUser;
        } else {
            return false;
        }
    }
    
    // fonction de recherche des user par rapport aux codes identifiants de batiment
    public function getUserBySite($site) {
        $siteUser = array();
        
        // recherche par rapport au samaccountname pass� en param�tre
		$rqc = $this->searchemptie($site,'streetaddress');
        $ldapsearch = @ldap_search($this->ldap_connect, $this->server['ldap_ou_user'], $rqc);
        
        @ldap_sort($this->ldap_connect, $ldapsearch, 'displayname');
        $info = @ldap_get_entries($this->ldap_connect, $ldapsearch);
        if ($info['count'] > 0) {
            for ($i = 0; $i < $info['count']; $i++) {
                $user = new User();
                $user->hydrateFromLdap($info[$i]);
                $siteUser[] = $user;
            }
            return $siteUser;
        } else {
            return false;
        }
    }
    
    /* ####################################################################################################
                                                ORGANIGRAMME
    #################################################################################################### */
    
    // fonction de récupération d'un organigramme par ses code service et code collectivité
	public function getOrganigramme($codeCollectivite, $codeService) {
        // recherche par le samaccountname
        $rqc = '(&'.$this->searchemptie($codeCollectivite,'employeeid').$this->searchemptie($codeService,'employeenumber').')';
        $ldapsearch = @ldap_search($this->ldap_connect, $this->server['ldap_ou_orga'], $rqc);
        
        // return FALSE si pas d'entrée sinon retourne l'entrée
        $return = @ldap_get_entries($this->ldap_connect, $ldapsearch);
        if ($return && $return['count'] > 0) {
            $orga = new Organigramme();
            $orga->hydrateFromLdap($return[0]);
            return $orga;
        } else {
            return false;
        }
    }
    
    // mise à jour d'un organigramme (admin)
    public function updateOrganigrammeAdmin($orga) {
        // vérification que le paramêtre passé est bien un objet de type Organigramme
        if ((is_object($orga)) && ($orga instanceof Organigramme)) {
            $entry_modify = $orga->prepareUpdateAdmin();
            $return = @ldap_modify($this->ldap_connect, $orga->getDn(), $entry_modify);
            if ($return) {
                return 2;
            } else {
                return 1;
            }
        } else {
            // La variable passée en paramêtre n'est pas une classe de type Organigramme
            return 0;
        }
    }
    
    // fonction de recherche d'un organigrame par rapport à son nom
    public function searchOrganigramme($search) {
    	global $config;
    	
        $searchOrganigramme = array();
        
        // recherche par rapport � la valeur pass�e en param�tre
        $rqc = $this->searchemptie('*'.$search.'*','displayname');
        $ldapsearch = @ldap_search($this->ldap_connect, $this->server['ldap_ou_orga'], $rqc);
        
        @ldap_sort($this->ldap_connect, $ldapsearch, 'displayname');
        $info = @ldap_get_entries($this->ldap_connect, $ldapsearch);
        if ($info['count'] > 0) {
            for ($i = 0; $i < $info['count']; $i++) {
            	if (!in_array($info[$i]['employeeid'][0].'-'.$info[$i]['employeenumber'][0], $config['except_services'])) {
	                $orga = new Organigramme();
	                $orga->hydrateFromLdap($info[$i]);
	                $searchOrganigramme[] = $orga;
            	}
            }
            return $searchOrganigramme;
        } else {
            return false;
        }
    }

    
    // function de récupération des organigrammes par rapport à un niveau (orga, pôle, direction, service)
	public function listOrganigramme($codeCollectivite, $codeService = '', $level = Ldap::ORGA) {
    	global $config;
    	
    	$searchOrganigramme = array();
    
    	// recherche par rapport à la valeur passée en paramêtre
    	if ($level == 0) {
    		$rqc = $this->searchemptie($codeCollectivite,'employeeid');
    	} else {
    		$rqc = '(&'.$this->searchemptie($codeCollectivite,'employeeid').$this->searchemptie(substr($codeService, 0, $level).'*','employeenumber').')';
    	}
    	$ldapsearch = @ldap_search($this->ldap_connect, $this->server['ldap_ou_orga'], $rqc);
    
    	@ldap_sort($this->ldap_connect, $ldapsearch, 'displayname');
    	$info = @ldap_get_entries($this->ldap_connect, $ldapsearch);
    	if ($info['count'] > 0) {
    		for ($i = 0; $i < $info['count']; $i++) {
    			if (strlen(@$info[$i]['employeenumber'][0]) == ($level + 2) && !in_array($info[$i]['employeeid'][0].'-'.@$info[$i]['employeenumber'][0], $config['except_services'])) {
    				$orga = new Organigramme();
    				$orga->hydrateFromLdap($info[$i]);
    				$searchOrganigramme[] = $orga;
    			}
    		}
    		return $searchOrganigramme;
    	} else {
    		return false;
    	}
    }
    
    
    /* ####################################################################################################
                                                    SITES
    #################################################################################################### */
    
    // fonction de r�cup�ration d'un organigramme par son samaccountname
    public function getSite($codeSite) {
        // recherche par le samaccountname
        $rqc = $this->searchemptie($codeSite,'cn');
        $ldapsearch = @ldap_search($this->ldap_connect, $this->server['ldap_ou_bat'], $rqc);
        
        // return FALSE si pas d'entr�e sinon retourne l'entr�e
        $return = @ldap_get_entries($this->ldap_connect, $ldapsearch);
        if ($return && $return['count'] > 0) {
            $site = new Site();
            $site->hydrateFromLdap($return[0]);
            return $site;
        } else {
            return false;
        }
    }
    
    // mise � jour d'un site (admin)
    public function updateSiteAdmin($site) {
        // v�rification que le param�tre pass� est bien un objet de type Site
        if ((is_object($site)) && ($site instanceof Site)) {
            $entry_modify = $site->prepareUpdateAdmin();
            $return = @ldap_modify($this->ldap_connect, $site->getDn(), $entry_modify);
            if ($return) {
                return 2;
            } else {
                return 1;
            }
        } else {
            // La variable pass�e en param�tre n'est pas une classe de type Site
            return 0;
        }
    }
    
    // fonction de recherche d'un user par rapport � son nom, pr�nom ou nom de jeune fille
    public function searchSite($search) {
        $searchSite = array();
        
        // recherche par rapport � la valeur pass�e en param�tre
        $rqc = $this->searchemptie('*'.$search.'*','displayname');
        $ldapsearch = @ldap_search($this->ldap_connect, $this->server['ldap_ou_bat'], $rqc);
        
        @ldap_sort($this->ldap_connect, $ldapsearch, 'displayname');
        $info = @ldap_get_entries($this->ldap_connect, $ldapsearch);
        if ($info['count'] > 0) {
            for ($i = 0; $i < $info['count']; $i++) {
                $site = new Site();
                $site->hydrateFromLdap($info[$i]);
                $searchSite[] = $site;
            }
            return $searchSite;
        } else {
            return false;
        }
    }
    
    
    // fonction de recherche d'un user par rapport � son nom, pr�nom ou nom de jeune fille
    public function getAllSites() {
        $searchSite = array();
        
        // recherche par rapport � la valeur pass�e en param�tre
        $rqc = $this->searchemptie('*','displayname');
        $ldapsearch = @ldap_search($this->ldap_connect, $this->server['ldap_ou_bat'], $rqc);
        
        @ldap_sort($this->ldap_connect, $ldapsearch, 'displayname');
        $info = @ldap_get_entries($this->ldap_connect, $ldapsearch);
        if ($info['count'] > 0) {
            for ($i = 0; $i < $info['count']; $i++) {
                $site = new Site();
                $site->hydrateFromLdap($info[$i]);
                $searchSite[] = $site;
            }
            return $searchSite;
        } else {
            return false;
        }
    }
    
    
    
    /* ####################################################################################################
                                                PRIVATE FUNCTIONS
    #################################################################################################### */
    
    
    // fonction servant au requ�tage facilit� pour le ldap
    private function searchemptie($var, $name) {
        if (empty($var) || !isset($var)) {
            return '(|('.$name.'=*)(!('.$name.'=*)))';
        } else {
            return '('.$name.'='.(strip_tags($var)).')';
        }
    }
    
    // fonction retournant un objet Organigramme � partir des donn�es de PHP-LDAP
    private function ldapToOrganigramme($data) {
        $orga = new Organigramme();
        $orga->hydrateFromLdap($data);
        return $orga;
    }
    
    // fonction retournant un objet Site � partir des donn�es de PHP-LDAP
    private function ldapToSite($data) {
        $site = new Site();
        $site->hydrateFromLdap($data);
        return $site;
    }
    
    // fonction retournant un objet User � partir des donn�es de PHP-LDAP
    private function ldapToUser($data) {
        $user = new User();
        $user->hydrateFromLdap($data);
        return $user;
    }

}

?>
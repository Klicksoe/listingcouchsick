<?php

class User {

    private $dn;
    private $nomConnexion;
    private $civilite;
    private $nom;
    private $nomJeuneFille;
    private $prenom;
    private $courriel;
    private $telephone;
    private $poste;
    private $portable;
    private $affichagePortable;
    private $fonction;
    private $nomAffiche;
    private $codeService;
    private $matricule;
    private $codeSite;
    private $localisation;
    private $photo;
    private $affichagePhoto;
    
    /* ##################################################
                    CONSTRUCTEUR
    ################################################## */
    
    public function __construct() {
        $this->dn = '';
        $this->nomConnexion = '';
        $this->civilite = '';
        $this->nom = '';
        $this->nomJeuneFille = '';
        $this->prenom = '';
        $this->courriel = '';
        $this->telephone = '';
        $this->poste = '';
        $this->portable = '';
        $this->affichagePortable = '';
        $this->fonction = '';
        $this->nomAffiche = '';
        $this->codeService = '';
        $this->matricule = '';
        $this->codeSite = '';
        $this->localisation = '';
        $this->photo = '';
        $this->affichagePhoto = '';
    }

    /* ##################################################
                    GETTERS ET SETTERS
    ################################################## */
    
    // @LDAP : dn
    public function getDn() {
        return $this->dn;
    }
    public function setDn($dn) {
        if (!empty($dn)) {
            $this->dn = $dn;
        }
    }
    
    // @LDAP : samaccountname
    public function getNomConnexion() {
        return $this->nomConnexion;
    }
    public function setNomConnexion($nomConnexion) {
        if (!empty($nomConnexion)) {
            $this->nomConnexion = $nomConnexion;
        }
    }

    // @LDAP : personaltitle
    public function getCivilite() {
        return $this->civilite;
    }
    public function setCivilite($civilite) {
        if (!empty($civilite)) {
            $this->civilite = $civilite;
        }
    }

    // @LDAP : sn
    public function getNom() {
        return $this->nom;
    }
    public function setNom($nom) {
        if (!empty($nom)) {
            $this->nom = $nom;
        }
    }
    
    // @LDAP : middlename
    public function getNomJeuneFille() {
        return $this->nomJeuneFille;
    }
    public function setNomJeuneFille($nomJeuneFille) {
        if (!empty($nomJeuneFille)) {
            $this->nomJeuneFille = $nomJeuneFille;
        }
    }

    // @LDAP : givenname
    public function getPrenom() {
        return $this->prenom;
    }
    public function setPrenom($prenom) {
        if (!empty($prenom)) {
            $this->prenom = $prenom;
        }
    }
    
    // @LDAP : mail
    public function getCourriel() {
        return $this->courriel;
    }
    public function setCourriel($courriel) {
        if (!empty($courriel)) {
            $this->courriel = $courriel;
        }
    }
    
    // @LDAP : internationalisdnnumber
    public function getTelephone() {
        return $this->telephone;
    }
    public function setTelephone($telephone) {
        if (!empty($telephone)) {
            $this->telephone = $telephone;
        }
    }
    
    // @LDAP : telephonenumber
    public function getPoste() {
        return $this->poste;
    }
    public function setPoste($poste) {
        if (!empty($poste)) {
            $this->poste = $poste;
        }
    }
    
    // @LDAP : mobile
    public function getPortable() {
        return $this->portable;
    }
    public function setPortable($portable) {
        if (!empty($portable)) {
            $this->portable = $portable;
        }
    }
    
    // @LDAP : info
    public function getAffichagePortable() {
        return $this->affichagePortable;
    }
    public function setAffichagePortable($affichagePortable) {
        if (!empty($affichagePortable)) {
            $this->affichagePortable = $affichagePortable;
        }
    }
    
    // @LDAP : businessroles
    public function getFonction() {
        return $this->fonction;
    }
    public function setFonction($fonction) {
        if (!empty($fonction)) {
            $this->fonction = $fonction;
        }
    }
    
    // @LDAP : displayname
    public function getNomAffiche() {
        return $this->nomAffiche;
    }
    public function setNomAffiche($nomAffiche) {
        if (!empty($nomAffiche)) {
            $this->nomAffiche = $nomAffiche;
        }
    }
    
    // @LDAP : division
    public function getCodeService() {
        return $this->codeService;
    }
    public function setCodeService($codeService) {
        if (!empty($codeService)) {
            $this->codeService = $codeService;
        }
    }
    
    // @LDAP : employeeid
    public function getMatricule() {
        return $this->matricule;
    }
    public function setMatricule($matricule) {
        if (!empty($matricule)) {
            $this->matricule = $matricule;
        }
    }
    
    // @LDAP : streetaddress
    public function getCodeSite() {
        return $this->codeSite;
    }
    public function setCodeSite($codeSite) {
        if (!empty($codeSite)) {
            $this->codeSite = $codeSite;
        }
    }
    
    // @LDAP : physicaldeliveryofficename
    public function getLocalisation() {
        return $this->localisation;
    }
    public function setLocalisation($localisation) {
        if (!empty($localisation)) {
            $this->localisation = $localisation;
        }
    }
    
    // @LDAP : jpegphoto
    public function getPhoto() {
        return $this->photo;
    }
    public function setPhoto($photo) {
        if (!empty($photo)) {
            $this->photo = $photo;
        }
    }
    
    // @LDAP : url - [OUI/NON]
    public function getAffichagePhoto() {
        return $this->affichagePhoto;
    }
    public function setAffichagePhoto($affichagePhoto) {
        if (!empty($affichagePhoto)) {
            $this->affichagePhoto = $affichagePhoto;
        }
    }
    
    // fonction d'hydratation de la classe par rapport aux données PHP-LDAP
    public function hydrateFromLdap($data) {
        $this->setDn(@$data['dn']);
        $this->setNomConnexion(@$data['samaccountname'][0]);
        $this->setCivilite(@$data['personaltitle'][0]);
        $this->setNom(@$data['sn'][0]);
        $this->setNomJeuneFille(@$data['middlename'][0]);
        $this->setPrenom(@$data['givenname'][0]);
        $this->setCourriel(@$data['mail'][0]);
        $this->setTelephone(@$data['internationalisdnnumber'][0]);
        $this->setPoste(@$data['telephonenumber'][0]);
        $this->setPortable(@$data['mobile'][0]);
        $this->setFonction(@$data['businessroles'][0]);
        $this->setNomAffiche(@$data['displayname'][0]);
        $this->setCodeService(@$data['division'][0]);
        $this->setMatricule(@$data['employeeid'][0]);
        $this->setCodeSite(@$data['streetaddress'][0]);
        $this->setLocalisation(@$data['physicaldeliveryofficename'][0]);
        $this->setPhoto(@$data['jpegphoto'][0]);
        $this->setAffichagePhoto(@$data['url'][0]);
        $this->setAffichagePortable(@$data['info'][0]);
    }
    
    // fonction de transformation de l'objet User en array contenant les modifications
    public function prepareUpdateProfil() {
        $data = array();
        if (!empty($this->codeSite)) { $data['streetaddress'] = array($this->codeSite); } else { $data['streetaddress'] = array(); }
        if (!empty($this->localisation)) { $data['physicaldeliveryofficename'] = array($this->localisation); } else { $data['physicaldeliveryofficename'] = array(); }
        if (!empty($this->portable)) { $data['mobile'] = array($this->portable); } else { $data['mobile'] = array(); }
        if (!empty($this->affichagePortable) && $this->affichagePortable == '1') { $data['info'] = array('1'); } else { $data['info'] = array('0'); }
        if (!empty($this->affichagePhoto) && $this->affichagePhoto == 'OUI') { $data['url'] = array('OUI'); } else { $data['url'] = array('NON'); }
        
        return $data;
    }
    
    // fonction de transformation de l'objet User en array contenant les modifications
    public function prepareUpdateAdmin() {
        $data = array();
        
        if (!empty($this->civilite)) { $data['personaltitle'] = array($this->civilite); } else { $data['personaltitle'] = array(); }
        if (!empty($this->nom)) { $data['sn'] = array($this->nom); } else { $data['sn'] = array(); }
        if (!empty($this->nomJeuneFille)) { $data['middlename'] = array($this->nomJeuneFille); } else { $data['middlename'] = array(); }
        if (!empty($this->prenom)) { $data['givenname'] = array($this->prenom); } else { $data['givenname'] = array(); }
        if (!empty($this->telephone)) { $data['internationalisdnnumber'] = array($this->telephone); } else { $data['internationalisdnnumber'] = array(); }
        if (!empty($this->poste)) { $data['telephonenumber'] = array($this->poste); } else { $data['telephonenumber'] = array(); }
        if (!empty($this->portable)) { $data['mobile'] = array($this->portable); } else { $data['mobile'] = array(); }
        if (!empty($this->affichagePortable) && $this->affichagePortable == 1) { $data['info'] = array('1'); } else { $data['info'] = array('0'); }
        if (!empty($this->courriel)) { $data['mail'] = array($this->courriel); } else { $data['mail'] = array(); }
        if (!empty($this->fonction)) { $data['businessroles'] = array($this->fonction); } else { $data['businessroles'] = array(); }
        if (!empty($this->nomAffiche)) { $data['displayname'] = array($this->nomAffiche); } else { $data['displayname'] = array(); }
        if (!empty($this->codeService)) { $data['division'] = array($this->codeService); } else { $data['division'] = array(); }
        if (!empty($this->matricule)) { $data['employeeid'] = array($this->matricule); } else { $data['employeeid'] = array(); }
        if (!empty($this->codeSite)) { $data['streetaddress'] = array($this->codeSite); } else { $data['streetaddress'] = array(); }
        if (!empty($this->localisation)) { $data['physicaldeliveryofficename'] = array($this->localisation); } else { $data['physicaldeliveryofficename'] = array(); }
        if (!empty($this->photo)) { $data['jpegphoto'] = array($this->photo); } else { $data['jpegphoto'] = array(); }
        if (!empty($this->affichagePhoto) && $this->affichagePhoto == 'OUI') { $data['url'] = array('OUI'); } else { $data['url'] = array('NON'); }
        
        return $data;
    }
    
}

?>
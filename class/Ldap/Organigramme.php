<?php

class Organigramme {

    private $dn;
    private $codeOrga;
    private $codeCollectivite;
    private $codeService;
    private $libelle;
    private $telephone;
    private $poste;
    private $fax;
    
    /* ##################################################
                    CONSTRUCTEUR
    ################################################## */
    
    public function __construct() {
        $this->dn = '';
        $this->codeOrga = '';
        $this->codeCollectivite = '';
        $this->codeService = '';
        $this->libelle = '';
        $this->telephone = '';
        $this->poste = '';
        $this->fax = '';
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
    public function getCodeOrga() {
        return $this->codeOrga;
    }
    public function setCodeOrga($codeOrga) {
        if (!empty($codeOrga)) {
            $this->codeOrga = $codeOrga;
        }
    }
    
    // @LDAP : employeeid
    public function getCodeCollectivite() {
        return $this->codeCollectivite;
    }
    public function setCodeCollectivite($codeCollectivite) {
        if (!empty($codeCollectivite)) {
            $this->codeCollectivite = $codeCollectivite;
        }
    }
    
    // @LDAP : employeenumber
    public function getCodeService() {
        return $this->codeService;
    }
    public function setCodeService($codeService) {
        if (!empty($codeService)) {
            $this->codeService = $codeService;
        }
    }

    // @LDAP : displayname
    public function getLibelle() {
        return $this->libelle;
    }
    public function setLibelle($libelle) {
        if (!empty($libelle)) {
            $this->libelle = $libelle;
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
    
    // @LDAP : facsimiletelephonenumber
    public function getFax() {
        return $this->fax;
    }
    public function setFax($fax) {
        if (!empty($fax)) {
            $this->fax = $fax;
        }
    }
    
    // fonction d'hydratation de la classe par rapport aux données PHP-LDAP
    public function hydrateFromLdap($data) {
        $this->setDn(@$data['dn']);
        $this->setCodeOrga(@$data['samaccountname'][0]);
        $this->setCodeCollectivite(@$data['employeeid'][0]);
        $this->setCodeService(@$data['employeenumber'][0]);
        $this->setLibelle(@$data['displayname'][0]);
        $this->setTelephone(@$data['internationalisdnnumber'][0]);
        $this->setPoste(@$data['telephonenumber'][0]);
        $this->setFax(@$data['facsimiletelephonenumber'][0]);
    }
    
    // fonction de transformation de l'objet User en array contenant les modifications
    public function prepareUpdateAdmin() {
        $data = array();
        
        if (!empty($this->libelle)) { $data['displayname'] = array($this->libelle); } else { $data['displayname'] = array(); }
        if (!empty($this->telephone)) { $data['internationalisdnnumber'] = array($this->telephone); } else { $data['internationalisdnnumber'] = array(); }
        if (!empty($this->poste)) { $data['telephonenumber'] = array($this->poste); } else { $data['telephonenumber'] = array(); }
        if (!empty($this->fax)) { $data['facsimiletelephonenumber'] = array($this->fax); } else { $data['facsimiletelephonenumber'] = array(); }
        
        return $data;
    }
    
}

?>
<?php
// License: GPLv3

class NewMember extends Controller{

	public function registerController($core){
		$core->registerControllerName("NewMember",$this);
	}

	public function call_register($core){
		$core->setPageTitle("S'enregistrer comme membre");
		
		include($this->getView(__CLASS__,__METHOD__));
	}

	public function call_add_save($core){
		$core->setPageTitle("Sauvegarder un membre");

		if(!$this->validate($core, $_POST)){
			return;
		}
		$_POST['userIdentifier'] = NULL;
		$_POST['memberIdentifier'] = 0;

		$item=Member::insertRow($core,"Member",$_POST);

		include($this->getView(__CLASS__,__METHOD__));
	}

	private function validate($core, $data){
		$query = 'select * from TablePrefix_Member where email =\'' . $data['email'] . '\';';
		if(!strpos($data['email'],'@') 
			or Member::findOneWithQuery($core, $query, 'Member') != null){
			echo "Le courriel est invalide ou déjà utilisé.";
			return false;
		}


		$query = 'select * from TablePrefix_Member where firstName =\'' . $data['firstName'] . '\' and dateOfBirth = \'' . $data['dateOfBirth'] . '\';';
		if(Member::findOneWithQuery($core, $query, 'Member') != null){
			echo "Vous êtes déjà enregistré. Veuillez contacter la coopérative si ce n'est pas le cas.";
			return false;
		}

		$tokens=explode("-",$_POST['dateOfBirth']);

		if(count($tokens)!=3){
			echo "La date de naissance doit être entrée dans le format aaaa-mm-jj.";
			return false;
		}

		return true;
	}
};

?>
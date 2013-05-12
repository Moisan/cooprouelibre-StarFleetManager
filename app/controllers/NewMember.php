<?php
// License: GPLv3

class NewMember extends Controller{

	public function registerController($core){
		$core->registerControllerName("NewMember",$this);
	}

	public function call_register($core){
		$core->setPageTitle("S'enregistrer comme membre");

		if(array_key_exists("lang", $_GET)){
			$lang = $_GET['lang'];
		}
		else{
			$lang = 'fr';
		}
		
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
		if(!isset($data['read_contract'])){
			echo "Vous n'avez pas valider que vous avez lu le contrat.</br>";
			echo "You did not validate that you have read the contract.";
			return false;
		}

		$query = 'select * from TablePrefix_Member where email =\'' . $data['email'] . '\';';
		if(!strpos($data['email'],'@') 
			or Member::findOneWithQuery($core, $query, 'Member') != null){
			echo "Le courriel est invalide ou déjà utilisé.</br>";
			echo "The email is already used.";
			return false;
		}


		$query = 'select * from TablePrefix_Member where firstName =\'' . $data['firstName'] . '\' and dateOfBirth = \'' . $data['dateOfBirth'] . '\';';
		if(Member::findOneWithQuery($core, $query, 'Member') != null){
			echo "Vous êtes déjà enregistré. Veuillez contacter la coopérative si ce n'est pas le cas.</br>";
			echo "You are already registered. If it's not the case, see a employee of the cooperative.";
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
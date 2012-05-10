<?php
// Author: Sébastien Boisvert
// Client: Coop Roue-Libre de l'Université Laval
// License: GPLv3

class MemberManagement extends Controller{

	public function registerController($core){
		$core->registerControllerName("MemberManagement",$this);
		$core->secureController("MemberManagement");
	}

	public function call_list($core){

		$core->setPageTitle("Voir les membres");

		$list=Member::findAll($core,"Member");

		$user=User::findOne($core,"User",$_SESSION['id']);
		$isLoaner=$user->isLoaner();
		$isManager=$user->isManager();


		include($this->getView(__CLASS__,__METHOD__));
	}

	public function call_add($core){

		$core->setPageTitle("Ajouter un membre");


		include($this->getView(__CLASS__,__METHOD__));
	}

	private function validate($data){
		if(!strpos($data['email'],'@')){
			echo "Le courriel est invalide.";
			return false;
		}

		$tokens=explode("-",$_POST['dateOfBirth']);

		if(count($tokens)!=3){
			echo "La date de naissance doit être entrée dans le format aaaa-mm-jj.";
			return false;
		}

		return true;
	}

	public function call_add_save($core){

		$core->setPageTitle("Sauvegarder un membre");

		if(!$this->validate($_POST)){
			return;
		}

		Member::insertRow($core,"Member",$_POST);
	
		include($this->getView(__CLASS__,__METHOD__));
	}

	public function call_view($core){
		$getData=$core->getGetData();
		$identifier=$getData["id"];

		$member=Member::findWithIdentifier($core,"Member",$identifier);

		$core->setPageTitle($member->getName());
		$columnNames=$member->getFieldNames();
		
		$user=User::findOne($core,"User",$_SESSION['id']);
		$isAdministrator=$user->isAdministrator();
		$isManager=$user->isManager();

		$memberLocks=$member->getLocks();

		include($this->getView(__CLASS__,__METHOD__));
	}


	public function call_edit($core){

		$core->setPageTitle("Éditer un membre");

		$member=Member::findOne($core,"Member",$_GET['id']);

		include($this->getView(__CLASS__,__METHOD__));
	}

	public function call_editSave($core){

		$user=User::findOne($core,"User",$_SESSION['id']);
		$isAdministrator=$user->isAdministrator();

		if(!$isAdministrator){
			return;
		}

		$core->setPageTitle("Sauvegarder un membre");

		Member::updateRow($core,"Member",$_POST,$_POST['id']);
	
		include($this->getView(__CLASS__,__METHOD__));
	}

	public function call_cancelLock($core){

		$core->setPageTitle("Annuler un bloquage");

		include($this->getView(__CLASS__,__METHOD__));
	}

	public function call_cancelLockSave($core){

		$core->setPageTitle("Annuler un bloquage");

		$user=User::findOne($core,"User",$_SESSION['id']);
		$isManager=$user->isManager();

		if(!$isManager){
			return;
		}

		$memberLock=MemberLock::findOne($core,"MemberLock",$_GET['id']);
		$data=$memberLock->getAttributes();

		$data['lifted']=1;
		$data['explanation']=$_POST['explanation'];
		$data['userIdentifier']=$_SESSION['id'];

		MemberLock::updateRow($core,"MemberLock",$data,$_GET['id']);

		include($this->getView(__CLASS__,__METHOD__));
	}

	public function call_addLock($core){

		$core->setPageTitle("Ajouter un bloquage");

		include($this->getView(__CLASS__,__METHOD__));
	}

	public function call_addLockSave($core){

		$core->setPageTitle("Ajouter un bloquage");

		$user=User::findOne($core,"User",$_SESSION['id']);
		$isManager=$user->isManager();

		if(!$isManager){
			return;
		}

		$_POST['memberIdentifier']=$_GET['id'];
		$_POST['lifted']=0;
		$_POST['explanation']="";
		$_POST['userIdentifier']=$_SESSION['id'];

		MemberLock::insertRow($core,"MemberLock",$_POST);

		include($this->getView(__CLASS__,__METHOD__));
	}


};

?>

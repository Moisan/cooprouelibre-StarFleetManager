<?php
// Author: Sébastien Boisvert and Thierry Moisan
// Client: Coop Roue-Libre de l'Université Laval
// License: GPLv3

class LoanManagement extends Controller
{

    public function registerController($core)
    {
        $core->registerControllerName("LoanManagement", $this);
        $core->secureController("LoanManagement");
    }


    public function call_listAll($core)
    {
        if (array_key_exists("placeIdentifier", $_GET)) {

            $item = Place::findOne($core, "Place", $_GET['placeIdentifier']);

            $core->setPageTitle("Voir les prêts pour le point de service " . $item->getName());

            $items = $item->getLoans();

        } elseif (array_key_exists("memberIdentifier", $_GET)) {

            $item = Member::findOne($core, "Member", $_GET['memberIdentifier']);

            $core->setPageTitle("Voir les prêts pour le membre " . $item->getName());

            $items = $item->getLoans();
        } elseif (array_key_exists("bikeIdentifier", $_GET)) {

            $item = Bike::findOne($core, "Bike", $_GET['bikeIdentifier']);

            $core->setPageTitle("Voir les prêts pour le vélo " . $item->getName());

            $items = $item->getLoans();


        } else {

            $core->setPageTitle("Voir tous les prêts");

            $items = Loan::findAll($core, "Loan");

        }
        include($this->getView(__CLASS__, __METHOD__));
    }


    public function call_list($core)
    {

        if (array_key_exists("placeIdentifier", $_GET)) {

            $item = Place::findOne($core, "Place", $_GET['placeIdentifier']);

            $core->setPageTitle("Voir les prêts pour le point de service " . $item->getName());

            $listReturnedLate = $item->findAllReturnedLateLoans($core);
            $listReturnedNotLate = $item->findAllReturnedNotLateLoans($core);
            $listActiveLate = $item->findAllActiveLateLoans($core);
            $listActiveNotLate = $item->findAllActiveNotLateLoans($core);

        } elseif (array_key_exists("memberIdentifier", $_GET)) {

            $item = Member::findOne($core, "Member", $_GET['memberIdentifier']);

            $core->setPageTitle("Voir les prêts pour le membre " . $item->getName());
            $listReturnedLate = $item->findAllReturnedLateLoans($core);
            $listReturnedNotLate = $item->findAllReturnedNotLateLoans($core);
            $listActiveLate = $item->findAllActiveLateLoans($core);
            $listActiveNotLate = $item->findAllActiveNotLateLoans($core);


        } elseif (array_key_exists("bikeIdentifier", $_GET)) {

            $item = Bike::findOne($core, "Bike", $_GET['bikeIdentifier']);

            $core->setPageTitle("Voir les prêts pour le vélo " . $item->getName());


            $listReturnedLate = $item->findAllReturnedLateLoans($core);
            $listReturnedNotLate = $item->findAllReturnedNotLateLoans($core);
            $listActiveLate = $item->findAllActiveLateLoans($core);
            $listActiveNotLate = $item->findAllActiveNotLateLoans($core);


        } else {

            $core->setPageTitle("Voir tous les prêts");

            $listReturnedLate = Loan::findAllReturnedLateLoans($core);
            $listReturnedNotLate = Loan::findAllReturnedNotLateLoans($core);
            $listActiveLate = Loan::findAllActiveLateLoans($core);
            $listActiveNotLate = Loan::findAllActiveNotLateLoans($core);

        }

        include($this->getView(__CLASS__, __METHOD__));
    }

    public function call_add_selectMember($core)
    {
        if (array_key_exists("placeIdentifier", $_GET)) {
            $place = Place::findOne($core, "Place", $_GET['placeIdentifier']);
        }

        $core->setPageTitle("Quel membre veut emprunter un vélo ?");

        $all = Member::getMembersThatCanLoanABike($core);
        $members = $all;

        $bikes = $place->getAvailableBikes();

        include($this->getView(__CLASS__, __METHOD__));
    }


    public function call_add_validate($core)
    {
        $core->setPageTitle("Voulez-vous faire ce prêt ?");

        $place = Place::findWithIdentifier($core, "Place", $_POST['placeIdentifier']);

        $bike = Bike::findWithIdentifier($core, "Bike", $_POST['bikeIdentifier']);

        $member = Member::findWithIdentifier($core, "Member", $_POST['memberIdentifier']);

        $startingDate = $core->getCurrentTime();

        $today = $core->getCurrentDate();

        $schedule = $place->getSchedule($today);

        $endingDate = $this->getEndingDate($place, $today, $startingDate);

        $minutes = (strtotime($endingDate) - strtotime($startingDate)) / 60;

        include($this->getView(__CLASS__, __METHOD__));
    }

    private function getEndingDate($place, $today, $startingDate)
    {
        $now = strtotime($startingDate);
        $endingDate = NULL;

        $schedule = $place->getSchedule($today);

        if ($schedule != NULL) {
            $dayOfWeek = date("N") - 1;

            $scheduledDay = $schedule->getScheduledDay($dayOfWeek);

            $length = $scheduledDay->getAttribute("loanLength") * 60 * 60;


            $final = $now + $length;
            $endingDate = date("Y-m-d H:i:s", $final);


            $eveningTime = date("Y-m-d ") . " " . $scheduledDay->getAttribute("eveningTime");

            // if this is after the eveningTime, return the bike the next day before returnTime
            if (strtotime($startingDate) >= strtotime($eveningTime)) {
                $tomorrow = date("Y-m-d", time() + 24 * 60 * 60);
                $endingDate = $this->getNextEndingDate($place, $tomorrow);
            }
        }

        return $endingDate;

    }

    private function getNextEndingDate($place, $day)
    {
        $schedule = $place->getSchedule($day);

        if ($schedule != NULL) {
            // get the day of the week
            $dayOfWeek = date("N", strtotime($day)) - 1;

            $scheduledDay = $schedule->getScheduledDay($dayOfWeek);

            $isOpened = $scheduledDay->getAttribute("opened");

            if ($isOpened) {
                // check for closed days too

                if ($place->isClosedDay($day)) {
                    $isOpened = false;
                }
            }

            if (!$isOpened) {
                $tomorrow = date("Y-m-d", strtotime($day) + 24 * 60 * 60);
                return $this->getNextEndingDate($place, $tomorrow);
            }

            return $day . " " . $scheduledDay->getAttribute("returnTime");
        }

        return NULL;
    }

    public function call_add_save($core)
    {

        $core->setPageTitle("Sauvegarde d'un prêt");

        $place = Place::findWithIdentifier($core, "Place", $_POST['placeIdentifier']);

        $bike = Bike::findWithIdentifier($core, "Bike", $_POST['bikeIdentifier']);

        $member = Member::findWithIdentifier($core, "Member", $_POST['memberIdentifier']);

        $startingDate = $core->getCurrentTime();

        $today = $core->getCurrentDate();

        $endingDate = $this->getEndingDate($place, $today, $startingDate);

        $attributes = array();

        $attributes["bikeIdentifier"] = $_POST['bikeIdentifier'];
        $attributes["userIdentifier"] = $_SESSION['id'];
        $attributes["memberIdentifier"] = $_POST['memberIdentifier'];
        $attributes["startingDate"] = $_POST['startingDate'];
        $attributes["expectedEndingDate"] = $_POST['expectedEndingDate'];
        $attributes["actualEndingDate"] = $_POST['actualEndingDate'];
        $attributes["returnUserIdentifier"] = $_SESSION['id'];
        $attributes["placeIdentifier"] = $_POST['placeIdentifier'];

        $item = Loan::insertRow($core, "Loan", $attributes);

        $id = $item->getId();
        $name = $item->getName();

        include($this->getView(__CLASS__, __METHOD__));
    }

    public function call_view($core)
    {

        $core->setPageTitle("Voir un prêt");

        $item = Loan::findWithIdentifier($core, "Loan", $_GET['id']);
        $place = $item->getPlace();
        $user = User::findOne($core, "User", $_SESSION['id']);
        $isLoaner = $place->isLoaner($user);

        $columnNames = Loan::getFieldNames();

        include($this->getView(__CLASS__, __METHOD__));
    }

    public function call_return_validate($core)
    {

        $core->setPageTitle("Voulez-vous terminer le prêt ?");

        $item = Loan::findWithIdentifier($core, "Loan", $_GET['id']);

        $columnNames = Loan::getFieldNames();

        $currentTime = $core->getCurrentTime();

        $user = User::findOne($core, "User", $_SESSION['id']);
        $isAdministrator = $user->isAdministrator();

        include($this->getView(__CLASS__, __METHOD__));
    }

    public function call_return_save($core)
    {

        $core->setPageTitle("Le prêt est terminé.");

        $item = Loan::findWithIdentifier($core, "Loan", $_GET['id']);

        $user = User::findOne($core, "User", $_SESSION['id']);

        $item->returnBike($_POST["actualEndingDate"], $user);

        $item = Loan::findWithIdentifier($core, "Loan", $_GET['id']);

        $columnNames = Loan::getFieldNames();

        $memberLock = NULL;

        if ($item->isLate()) {
            $hours = $item->getLateHours();

            $data = array();
            $data["memberIdentifier"] = $item->getAttribute("memberIdentifier");
            $now = time();

            // for each hour late, we ban one day
            $endingPoint = $now + $hours * 24 * 60 * 60;

            $data["startingDate"] = date("Y-m-d H:i:s", $now);
            $data["endingDate"] = date("Y-m-d H:i:s", $endingPoint);
            $data["lifted"] = 0;
            $data["explanation"] = "";
            $data["userIdentifier"] = $_SESSION['id'];

            $memberLock = MemberLock::insertRow($core, "MemberLock", $data);
        }

        include($this->getView(__CLASS__, __METHOD__));
    }
};

?>

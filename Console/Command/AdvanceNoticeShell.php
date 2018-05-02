<?php

class AdvanceNoticeShell extends AppShell
{
    public function main()
    {
        //App::import('Model', 'News');
        App::import('Model', 'User');
        App::import('Model', 'Reservation');
	//$News = new News();
	$users = new User();
	$reservation = new Reservation();
        //send birthday notification
        $users->send_customer_birthday_notification();

        //send reservation notification
        $reservation->send_reservation_notification();
        //$News->sendReservationNotice(); @deprecated
    }
}
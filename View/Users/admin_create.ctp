<?php

/*
 * Created 11/ November/2015
 * Channeth
 */
if ($this->request->query('role') == 'agent') {
    echo $this->element('Backend/User_type/admin_create_agent');
} else if ($this->request->query('role') == 'shop') {
    echo $this->element('Backend/User_type/admin_create_shop');
} else {
    echo $this->element('Backend/User_type/admin_create_user');
}
<?php

class Installer
{
    public function run()
    {
        $this->set_user_roles();
    }

    public function set_user_roles()
    {
        add_role('bixxs_event_employee', 'Mitarbeiter', get_role('shop_manager')->capabilities);
    }
}

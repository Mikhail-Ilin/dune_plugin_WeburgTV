<?php
/*

WeburgTV plugin (mik@mail.ru)

*/

///////////////////////////////////////////////////////////////////////////

require_once 'lib/abstract_controls_screen.php';

///////////////////////////////////////////////////////////////////////////

class WeburgTVSetupScreen extends AbstractControlsScreen
{
    const ID = 'setup';

    ///////////////////////////////////////////////////////////////////////

    public function __construct()
    {
        parent::__construct(self::ID);
	
    }

    public function do_get_control_defs(&$plugin_cookies) {
        $defs = array();

        $buffer_time = isset($plugin_cookies->buffer_time) ?
            $plugin_cookies->buffer_time : '0';

        $epg_shift = isset($plugin_cookies->epg_shift) ?
            $plugin_cookies->epg_shift : '0';

        $shift_ops = array();
        
        for ($i = -12; $i<13; $i++)
            $shift_ops[$i] = $i; 


        $this->add_label($defs,'Версия WeburgTV plugin:', WeburgTVConfig::PLUGIN_VERSION);       

        $this->add_combobox($defs,'epg_shift', 'Коррекция программы (час):',$epg_shift, $shift_ops, 0, true);
                  
   //     $this->add_text_field($defs, 'buffer_time', 'Время буфферизации', $buffer_time, true, false, false, true, 10, false, true);

        return $defs;
    }

    public function get_control_defs(MediaURL $media_url, &$plugin_cookies)
    {
        return $this->do_get_control_defs($plugin_cookies);
    }

    public function handle_user_input(&$user_input, &$plugin_cookies)
    {
        
        if ($user_input->action_type === 'confirm' || $user_input->action_type === 'apply' )
        {
            $control_id = $user_input->control_id;
            $new_value = $user_input->{$control_id};
            hd_print("Setup: changing $control_id value to $new_value");

            if ($control_id === 'buffer_time')
                $plugin_cookies->buffer_time = $new_value;
            
            if ($control_id == 'epg_shift')
                $plugin_cookies->epg_shift = $new_value;
 	   
        }

        return ActionFactory::reset_controls(
            $this->do_get_control_defs($plugin_cookies));
    }
}

///////////////////////////////////////////////////////////////////////////
?>
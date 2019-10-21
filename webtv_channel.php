<?php
/*

WeburgTV plugin (mik@mail.ru)

*/

///////////////////////////////////////////////////////////////////////////

require_once 'lib/tv/default_channel.php';

///////////////////////////////////////////////////////////////////////////

class WeburgTVChannel extends DefaultChannel
{
    private $number;
    private $past_epg_days;
    private $future_epg_days;
    ///////////////////////////////////////////////////////////////////////

    public function __construct(
        $id, $title, $icon_url, $streaming_url, $number, $past_epg_days, $future_epg_days)
    {
        parent::__construct($id, $title, $icon_url, $streaming_url);

        $this->number = $number;
        $this->past_epg_days = $past_epg_days;
        $this->future_epg_days = $future_epg_days;
        $this->playback_url_is_stream_url = false;
    }

    ///////////////////////////////////////////////////////////////////////

    public function get_number()
    { return $this->number; }

    public function get_past_epg_days()
    { return $this->past_epg_days; }

    public function get_future_epg_days()
    { return $this->future_epg_days; }
    
    public function has_archive()
    { return ($this->past_epg_days>0); }

    public function get_archive_past_sec()
    { return  $this->past_epg_days * 86400+86400; }

    public function get_archive_delay_sec()
    { return 15; }

   public function get_buffering_ms()
   { return 0;} 
   
   public function get_epg_icon_width()
   { return 35; }

   public function get_epg_icon_height()
   { return 60; }

   public function get_epg_icon_x_offset()
   { return 0; }

   public function get_epg_icon_y_offset()
   { return 0; }
}
///////////////////////////////////////////////////////////////////////////
?>

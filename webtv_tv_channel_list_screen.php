<?php
/*

WeburgTV plugin (mik@mail.ru)

*/

///////////////////////////////////////////////////////////////////////////

require_once 'lib/tv/tv_channel_list_screen.php';

class WeburgTvChannelListScreen extends TvChannelListScreen
{
    public function get_all_folder_items(MediaURL $media_url, &$plugin_cookies)
    {
        if (WeburgTVConfig::USE_M3U_FILE && !isset($media_url->group_id))
            $media_url->group_id = $this->tv->get_all_channel_group_id();

        return parent::get_all_folder_items($media_url, $plugin_cookies);
    }

   private function get_sel_item_update_action(&$user_input, &$plugin_cookies)
    {
        $parent_media_url = MediaURL::decode($user_input->parent_media_url);
        $sel_ndx = $user_input->sel_ndx;
        $group = $this->tv->get_group($parent_media_url->group_id);
        $channels = $group->get_channels($plugin_cookies);

        $items[] = $this->get_regular_folder_item($group,
            $channels->get_by_ndx($sel_ndx), $plugin_cookies);
        $range = HD::create_regular_folder_range($items,
            $sel_ndx, $channels->size());
                                                  
        return ActionFactory::update_regular_folder($range, false);
    }

    private function get_regular_folder_item($group, $c, &$plugin_cookies)
    {
        return array
        (
            PluginRegularFolderItem::media_url =>
                MediaURL::encode(
                    array(
                        'channel_id' => $c->get_id(),
                        'group_id' => $group->get_id())),
            PluginRegularFolderItem::caption => $c->get_title(),
            PluginRegularFolderItem::view_item_params => array
            (
                ViewItemParams::icon_path => $c->get_icon_url(),
                ViewItemParams::item_detailed_icon_path => $c->get_icon_url(),
            ),
            PluginRegularFolderItem::starred =>
                $this->tv->is_favorite_channel_id(
                    $c->get_id(), $plugin_cookies),
        );
    }

    public function handle_user_input(&$user_input, &$plugin_cookies)
    {
       if ($user_input->control_id == 'add_favorite')
        {
            if (!isset($user_input->selected_media_url))
                return null;

            $media_url = MediaURL::decode($user_input->selected_media_url);
            $channel_id = $media_url->channel_id;

            $is_favorite = $this->tv->is_favorite_channel_id($channel_id, $plugin_cookies);
            if ($is_favorite)
            {
                return ActionFactory::show_title_dialog(
                    'Канал уже находится в Избранном',
                    $this->get_sel_item_update_action(
                        $user_input, $plugin_cookies));
            }
            else
            {
                $this->tv->change_tv_favorites(PLUGIN_FAVORITES_OP_ADD,
                    $channel_id, $plugin_cookies);

                return ActionFactory::show_title_dialog(
                    'Канал добавлен в Избранное',
                    $this->get_sel_item_update_action(
                        $user_input, $plugin_cookies));
            }
        }

        return null;
    }


}

///////////////////////////////////////////////////////////////////////////
?>

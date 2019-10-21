<?php
/*

WeburgTV plugin (mik@mail.ru)

*/

///////////////////////////////////////////////////////////////////////////

require_once 'lib/default_dune_plugin_fw.php';

require 'webtv_plugin.php';

//////////////////////////////////////////////////////////////////////////

function throw_m3u_error($m3u_file)
{
    throw new DuneException(
        'M3U access error', 0,
        ActionFactory::show_error(
            true,
            'Ошибка M3U',
            array(
                'Не возможно загрузить файл:',
                $m3u_file,
		'Проверьте правильность настроек')));
}
///////////////////////////////////////////////////////////////////////////

setlocale(LC_ALL, "ru_RU.UTF-8");

DefaultDunePluginFw::$plugin_class_name = 'WeburgTVPlugin';

///////////////////////////////////////////////////////////////////////////
?>

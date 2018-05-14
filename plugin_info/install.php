<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */
require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';


function Teleinfo_install() {
    message::add('Téléinfo', 'Installation du plugin Téléinfo en cours...', null, null);
    $core_version = '1.1.1';
    if (!file_exists(dirname(__FILE__) . '/info.json')) {
        log::add('Teleinfo','warning','Pas de fichier info.json');
        goto step2;
    }
    $data = json_decode(file_get_contents(dirname(__FILE__) . '/info.json'), true);
    if (!is_array($data)) {
        log::add('Teleinfo','warning','Impossible de décoder le fichier info.json');
        goto step2;
    }
    try {
        $core_version = $data['pluginVersion'];
    } catch (\Exception $e) {

    }
    step2:
    if (Teleinfo::deamonRunning()) {
        Teleinfo::deamon_stop();
    }
    $cron = cron::byClassAndFunction('Teleinfo', 'calculateOtherStats');
    if (!is_object($cron)) {
        $cron = new cron();
        $cron->setClass('Teleinfo');
        $cron->setFunction('calculateOtherStats');
        $cron->setEnable(1);
        $cron->setDeamon(0);
        $cron->setSchedule('10 00 * * *');
        $cron->save();
    }

    $crontoday = cron::byClassAndFunction('Teleinfo', 'calculateTodayStats');
    if (!is_object($crontoday)) {
        $crontoday = new cron();
        $crontoday->setClass('Teleinfo');
        $crontoday->setFunction('calculateTodayStats');
        $crontoday->setEnable(1);
        $crontoday->setDeamon(0);
        $crontoday->setSchedule('*/5 * * * *');
        $crontoday->save();
    }
    message::removeAll('Téléinfo');
    message::add('Téléinfo', 'Installation du plugin Téléinfo terminée, vous êtes en version ' . $core_version . '.', null, null);
    //cache::set('Teleinfo::current_core','2.610', 0);
}

function Teleinfo_update() {
    log::add('Teleinfo','debug','Teleinfo_update');
    $core_version = '1.1.1';
    if (!file_exists(dirname(__FILE__) . '/info.json')) {
        log::add('Teleinfo','warning','Pas de fichier info.json');
        goto step2;
    }
    $data = json_decode(file_get_contents(dirname(__FILE__) . '/info.json'), true);
    if (!is_array($data)) {
        log::add('Teleinfo','warning','Impossible de décoder le fichier info.json');
        goto step2;
    }
    try {
        $core_version = $data['pluginVersion'];
    } catch (\Exception $e) {
        log::add('Teleinfo','warning','Pas de version de plugin');
    }
    step2:
    if (Teleinfo::deamonRunning()) {
        Teleinfo::deamon_stop();
    }
    message::add('Téléinfo', 'Mise à jour du plugin Téléinfo en cours...', null, null);
    log::add('Teleinfo','info','*****************************************************');
    log::add('Teleinfo','info','*********** Mise à jour du plugin Teleinfo **********');
    log::add('Teleinfo','info','*****************************************************');
    log::add('Teleinfo','info','**        Core version    : '. $core_version. '                  **');
    log::add('Teleinfo','info','*****************************************************');

    $cron = cron::byClassAndFunction('Teleinfo', 'CalculateOtherStats');
    if (is_object($cron)) {
        $cron->remove();
    }
    $crontoday = cron::byClassAndFunction('Teleinfo', 'CalculateTodayStats');
    if (is_object($crontoday)) {
        $crontoday->remove();
    }

    $cron = cron::byClassAndFunction('Teleinfo', 'calculateOtherStats');
    if (!is_object($cron)) {
        $cron = new cron();
        $cron->setClass('Teleinfo');
        $cron->setFunction('calculateOtherStats');
        $cron->setEnable(1);
        $cron->setDeamon(0);
        $cron->setSchedule('10 00 * * *');
        $cron->save();
    }
    else{
        $cron->setSchedule('10 00 * * *');
        $cron->save();
    }
    $cron->stop();

    $crontoday = cron::byClassAndFunction('Teleinfo', 'calculateTodayStats');
    if (!is_object($crontoday)) {
        $crontoday = new cron();
        $crontoday->setClass('Teleinfo');
        $crontoday->setFunction('calculateTodayStats');
        $crontoday->setEnable(1);
        $crontoday->setDeamon(0);
        $crontoday->setSchedule('*/5 * * * *');
        $crontoday->save();
    }
    $crontoday->stop();
    message::removeAll('Téléinfo');
    message::add('Téléinfo', 'Mise à jour du plugin Téléinfo terminée, vous êtes en version ' . $core_version . '.', null, null);
    Teleinfo::cron();
}

function Teleinfo_remove() {
    if (Teleinfo::deamonRunning()) {
        Teleinfo::deamon_stop();
    }
    $cron = cron::byClassAndFunction('Teleinfo', 'CalculateOtherStats');
    if (is_object($cron)) {
        $cron->remove();
    }
    $crontoday = cron::byClassAndFunction('Teleinfo', 'CalculateTodayStats');
    if (is_object($crontoday)) {
        $crontoday->remove();
    }
    message::removeAll('Téléinfo');
    message::add('Téléinfo', 'Désinstallation du plugin Téléinfo terminée, vous pouvez de nouveau relever les index à la main ;)', null, null);
}

<?php
if (!isConnect('admin')) {
    throw new Exception('Error 401 Unauthorized');
}
$plugin = plugin::byId('Teleinfo');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());
try {
	$result = Teleinfo::deamon_info();
	if (isset($result['state'])) {
		$controlerState = $result['state'];
	}
} catch (Exception $e) {
	$controlerState = null;
}
switch ($controlerState) {
	case 'ok':
		// event::add('jeedom::alert', array(
		// 	'level' => 'warning',
		// 	'page' => 'Teleinfo',
		// 	'message' => __('Le réseau Z-Wave est en cours de démarrage sur le serveur', __FILE__),
		// ));
		break;
	case 'nok':
		event::add('jeedom::alert', array(
			'level' => 'danger',
			'page' => 'Teleinfo',
			'message' => __('Le deamon téléinfo ne semble pas démaré, vérifiez la configuration du port.', __FILE__),
		));
		break;
}
//$deamonRunning = false;
//$deamonRunning = Teleinfo::deamonRunning();
?>

<div class="row row-overflow">

	<div class="col-lg-2 col-md-3 col-sm-4">
        <div class="bs-sidebar">
            <ul id="ul_eqLogic" class="nav nav-list bs-sidenav">
                <a class="btn btn-default eqLogicAction" style="width : 100%;margin-top : 5px;margin-bottom: 5px;" data-action="add"><i class="fa fa-plus-circle"></i> {{Ajouter}}</a>
                <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
                <?php
                foreach ($eqLogics as $eqLogic) {
                    echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $eqLogic->getId() . '"><a>' . $eqLogic->getHumanName(true) . '</a></li>';
                }
                ?>
            </ul>
        </div>
    </div>

	<div class="col-lg-10 col-md-9 col-sm-8 eqLogicThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">
		<legend>{{Gestion}}</legend>
		<div class="eqLogicThumbnailContainer">
			<!--<div class="cursor" id="bt_cout" style="background-color : #ffffff; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
			  <center>
				<i class="fa fa-eur" style="font-size : 5em;color:#767676;"></i>
			  </center>
			  <span style="font-size : 1.1em;position:relative; top : 23px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676"><center>{{Coût}}</center></span>
			</div>-->

			<div class="cursor" id="bt_info_daemon" style="background-color : #ffffff; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
			  <center>
				<i class="fa fa-heartbeat" style="font-size : 5em;color:#767676;"></i>
			  </center>
			  <span style="font-size : 1.1em;position:relative; top : 23px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676"><center>{{Info Modem}}</center></span>
			</div>

			<div class="cursor eqLogicAction" data-action="gotoPluginConf" style="background-color : #ffffff; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;">
				<center>
					<i class="fa fa-wrench" style="font-size : 5em;color:#767676;"></i>
				</center>
			<span style="font-size : 1.1em;position:relative; top : 23px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676"><center>Configuration</center></span>
			</div>


		</div>

        <legend>{{Mes Modules de Téléinformation}}</legend>
        <?php
        /*if (count($eqLogics) == 0) {
            echo "<br/><br/><br/><center><span style='color:#767676;font-size:1.2em;font-weight: bold;'>{{Vous n'avez pas encore de module Téléinformation, cliquez sur Ajouter pour commencer}}</span></center>";
        } else {*/
            ?>
            <div class="eqLogicThumbnailContainer">

				<div class="cursor eqLogicAction" data-action="add" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
					<center>
						<i class="fa fa-plus-circle" style="font-size : 7em;color:#33B8CC;"></i>
					</center>
					<span style="font-size : 1.1em;position:relative; top : 23px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#4F81BD"><center>Ajouter</center></span>
				</div>

                <?php
                // foreach ($eqLogics as $eqLogic) {
                //     echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
                //     echo "<center>";
                //     echo '<img src="plugins/Teleinfo/docs/images/Teleinfo_icon.png" height="105" width="95" />';
                //     echo "</center>";
                //     echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $eqLogic->getHumanName(true, true) . '</center></span>';
                //     echo '</div>';
                // }
                foreach ($eqLogics as $eqLogic) {
                	$opacity = ($eqLogic->getIsEnable()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
                	echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="text-align: center; background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '" >';
                	echo '<img src="' . $plugin->getPathImgIcon() . '" height="105" width="95" />';
                	echo "<br>";
                	echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;">' . $eqLogic->getHumanName(true, true) . '</span>';
                	echo '</div>';
                }
                ?>
            </div>
        <?php /*}*/ ?>
    </div>



    <div class="col-lg-10 col-md-9 col-sm-8 eqLogic" style="border-left: solid 1px #EEE; padding-left: 25px;display: none;">
		<a class="btn btn-success eqLogicAction pull-right" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
		<a class="btn btn-danger eqLogicAction pull-right" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
    <a class="btn btn-default eqLogicAction pull-right" data-action="configure"><i class="fa fa-cogs"></i> Configuration avancée</a>

		<ul class="nav nav-tabs" role="tablist">
      <li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fa fa-arrow-circle-left"></i></a></li>
			<li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-tachometer"></i> Equipement</a></li>
			<li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i> Commandes</a></li>
		</ul>

		<div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
			<div role="tabpanel" class="tab-pane active" id="eqlogictab">
      </br>
      <div class="row">
          <div class="col-lg-6">
              <form class="form-horizontal">
                  <fieldset>
                      <div class="form-group">
                          <label class="col-lg-4 control-label">{{Nom de l'équipement}} :</label>
                          <div class="col-lg-4">
                              <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
                              <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement}}"/>
                          </div>
                          <div class="col-lg-4">
                          </div>
                      </div>
                      <div class="form-group">
                          <label class="col-lg-4 control-label" >{{Objet parent :}}</label>
                          <div class="col-lg-4">
                              <select class="eqLogicAttr form-control" data-l1key="object_id">
                                  <option value="">{{Aucun}}</option>
                                  <?php
                                  foreach (object::all() as $object) {
                                      echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
                                  }
                                  ?>
                              </select>
                          </div>
                          <div class="col-lg-4">
                          </div>
                      </div>
                      <div class="form-group">
                          <label class="col-lg-4 control-label">{{Identifiant Compteur}} :</label>
                          <div class="col-lg-4">
                              <input type="text" class="eqLogicAttr form-control tooltips" title="{{Identifiant du compteur aussi connu sous le nom ADCO.}}" data-l1key="logicalId" placeholder="{{ADCO du compteur}}"/>
                          </div>
                          <div class="col-lg-4">
                          </div>
                      </div>
                      <div class="form-group" style="display:none">
                          <label class="col-lg-4 control-label">{{Catégorie}} :</label>
                          <div class="col-lg-8">
                              <!--<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="energy" checked/>-->
                              <?php
                              /*foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
                              echo '<label class="checkbox-inline">';
                              echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
                              echo '</label>';
                          }*/
                              ?>
                          </div>
                      </div>
                      <div class="form-group">
                          <label class="col-lg-4 control-label">{{Etat de l'objet}} :</label>
                          <div class="col-lg-8">
                              <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
                              <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
                          </div>
                      </div>
                  </fieldset>
              </form>
          </div>
          <div class="col-lg-6">
              <form class="form-horizontal">
                  <fieldset>
                      <!--<legend>{{Paramètres}}</legend>-->
                      <div class="form-group">
                          <label class="col-lg-3 control-label pull-left">{{Votre abonnement :}}</label>
                          <div class="col-lg-4">
                              <select class="eqLogicAttr form-control tooltips" title="{{Abonnement présent sur le compteur}}" data-l1key="configuration" data-l2key="abonnement">
                                  <option value="">Aucun</option>
                                  <option value="base">Base (HP)</option>
                                  <option value="basetri">Base triphasé</option>
                                  <option value="bleu">Bleu (HP/HC)</option>
                                  <option value="bleutri">Bleu triphasé</option>
                                  <option value="tempo">Tempo / EJP</option>
                                  <option value="tempotri">Tempo triphasé</option>
                              </select>
                          </div>
                          <div class="col-lg-5">
                          </div>
                      </div>
                      <div class="form-group">
                          <label class="col-lg-3 control-label pull-left">{{Création des commandes}} <sup><i class="fa fa-question-circle tooltips" title="{{Créer automatiquement les commandes envoyées par le compteur}}" style="font-size : 1em;color:grey;"></i></sup></label>
                          <div class="col-lg-7 tooltips">
                              <input type="checkbox" id="AutoCreateFromCompteur" class="eqLogicAttr configKey" data-l1key="configuration" data-l2key="AutoCreateFromCompteur"/>
                              <label for="AutoCreateFromCompteur">  </label>
                          </div>
                          <div class="col-lg-2">
                          </div>
                          <!--<div class="col-md-2">
                          <input class="eqLogicAttr" style="display:none" type="checkbox"  data-l1key="configuration" data-l2key="AutoGenerateFields" id="checkbox-autocreate"/>
                          <a class="btn btn-info btn-sm eqLogicAction tooltips"  id="create_data_Teleinfo" title="{{Permet de créer automatiquement les commandes nécessaires.}}" id="createcmd"><i class="fa fa-plus-circle"></i> {{Créer}}</a><br/><br/>
                          </div>-->
                      </div>
                      <div class="form-group">
                          <label class="col-lg-3 control-label pull-left">{{Vérifier}} <sup><i class="fa fa-question-circle tooltips" title="{{Obtenir des informations sur la réception des données}}" style="font-size : 1em;color:grey;"></i></sup></label>
                          <div class="col-lg-3">
                              <a class="btn btn-info tooltips"  id="bt_teleinfoHealth"><i class="fa fa-medkit"></i>{{ Santé}}</a>
                          </div>
                          <div class="col-lg-6">
                          </div>
                          <!--<div class="col-md-2">
                          <a class="btn btn-info btn-sm eqLogicAction tooltips"  data-action="save" title="{{Applique le template}}"><i class="fa fa-plus-circle"></i> {{Appliquer}}</a><br/><br/>
                          </div>-->
                      </div>
                      <div class="form-group">
                          <div class="col-lg-12">
                              <div class="alert alert-info globalRemark" style="">{{Attention, il est nécessaire d'activer l'historisation des index pour utiliser les statistiques}}</div>
                          </div>
                      </div>
                  </fieldset>
              </form>
          </div>
      </div>
  </div>
  <div role="tabpanel" class="tab-pane" id="commandtab">
  </br>
  <a class="btn btn-success btn-sm cmdAction" id="addDataToTable"><i class="fa fa-plus-circle"></i> {{Ajouter une donnée}}</a> &nbsp;
  <a class="btn btn-success btn-sm cmdAction expertModeVisible" id="addStatToTable"><i class="fa fa-plus-circle"></i> {{Ajouter une statistique}}</a><br/><br/>
  <table id="table_cmd" class="table table-bordered table-condensed">
      <thead>
          <tr>
              <th style="width: 50px;">#</th>
              <th style="width: 150px;">{{Nom}}</th>
              <th style="width: 110px;">{{Sous-Type}}</th>
              <th style="width: 200px;">{{Donnée}}</th>
              <th style="width: 150px;">{{Paramètres}}</th>
              <th style="width: 150px;"></th>
          </tr>
      </thead>
      <tbody>
      </tbody>
  </table>
  <form class="form-horizontal">
      <fieldset>
          <div class="form-actions">
          </div>
      </fieldset>
  </form>
</div>
		</div>
	</div>
</div>

<?php include_file('desktop', 'Teleinfo', 'js', 'Teleinfo'); ?>
<?php include_file('desktop', 'Teleinfo', 'css', 'Teleinfo'); ?>
<?php include_file('core', 'plugin.template', 'js'); ?>

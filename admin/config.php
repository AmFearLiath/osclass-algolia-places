<?php 
if (!defined('OC_ADMIN') || OC_ADMIN!==true) exit('Access is not allowed.');
$activated = false; $languages = false; $countries = false;
$algolia = new algolia;

if (Params::getParam('algolia_places') == 'save') {
    
    $data = array(
        'activated' => Params::getParam('activate'),
        'appid' => Params::getParam('appid'),
        'appkey' => Params::getParam('appkey'),
        'languages' => Params::getParam('language'),
        'countries' => Params::getParam('country'),
        'countrycodes' => Params::getParam('countrycodes'),
        'languagecodes' => serialize(Params::getParam('languagecodes')),
        'standardLanguage' => Params::getParam('standardLanguage'));
    
    if ($algolia->_save($data)) {
        $opt = $data;    
    }       
}  else {
    $opt = array(
        'activated' => $algolia->_get('activated'),
        'appid' => $algolia->_get('appid'),
        'appkey' => $algolia->_get('appkey'),
        'languages' => $algolia->_get('languages'),
        'countries' => $algolia->_get('countries'),
        'countrycodes' => $algolia->_get('countrycodes'),
        'languagecodes' => $algolia->_get('languagecodes'),
        'standardLanguage' => $algolia->_get('standardLanguage')
    );
}

if ($opt['activated'] == '1') {
    $activated = true;
} if ($opt['languages'] == '1') {
    $languages = true;
} if ($opt['countries'] == '1') {
    $countries = true;
}
?>
<div class="algolia_help">
    <form action="<?php echo osc_admin_render_plugin_url('algolia_places/admin/config.php');; ?>" method="POST">
        <input type="hidden" name="page" value="plugins" />
        <input type="hidden" name="action" value="renderplugin" />
        <input type="hidden" name="file" value="<?php echo osc_plugin_folder(__FILE__); ?>config.php" />
        <input type="hidden" name="algolia_places" value="save" />

        <div class="algolia_content">
        
            <div class="form-group">
                <h3><strong><?php _e('Activate Algolia Places', 'algolia_places'); ?></strong></h3>
                <label>
                    <input type="checkbox" name="activate" id="activate" value="1" <?php if ($activated) { echo 'checked="checked"'; } ?> />
                    <?php _e('Check this box to activate the plugin and make use of algolia places', 'algolia_places'); ?>
                    <p><small class="info"><?php _e('If you are using the autocomplete from your theme or osclass, you should disable it, before using this plugin.', 'algolia_places'); ?></small></p>
                </label>
                <br />
                <h3>
                    <strong><?php _e('API Status', 'algolia_places'); ?></strong><br />
                    <small><?php _e('<a target="_blank" href="https://www.algolia.com/dashboard/status">Here</a> you can see your Statistiks on Algolia Places API Credentials', 'algolia_places'); ?></small>
                </h3>
                <h3>
                    <strong><?php _e('API Credentials', 'algolia_places'); ?></strong><br />
                    <small><?php _e('Enter here your <a target="_blank" href="https://www.algolia.com/users/sign_up/places">API Credentials</a> if you want to increase you free amount of searches from 1.000/day to 100.000/month.', 'algolia_places'); ?></small>
                </h3>
                <div class="half-row">
                    <label><?php _e('Application ID', 'algolia_places'); ?></label><br />
                    <input style="width: 95%;" type="text" name="appid" id="appid" value="<?php echo $opt['appid'] ?>"  />
                </div>
                <div class="half-row">
                    <label><?php _e('API Key', 'algolia_places'); ?></label><br />
                    <input style="width: 95%;" type="text" name="appkey" id="appkey" value="<?php echo $opt['appkey'] ?>"  />
                </div>
                <div style="clear: both;"></div>
            </div>
        
            <div class="half-row">
                <div class="form-group">
                    <h3><strong><?php _e('Language Support', 'algolia_places'); ?></strong></h3>
                    <label>
                        <input type="checkbox" name="language" id="language" value="1" <?php if ($languages) { echo 'checked="checked"'; } ?> />
                        <?php _e('Check this box to activate the language support.', 'algolia_places'); ?><br />
                        <small><?php _e('This option shows the results in the user language', 'algolia_places'); ?></small>
                    </label>
                    <div id="supLanguages" <?php if (!$languages) { echo 'style="display: none;"'; } ?>>
                        <h3><strong><?php _e('<a href="https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes#Table">ISO 639-1</a> Language Codes', 'algolia_places'); ?></strong></h3>
                        <label><?php _e('Enter here the supported language codes', 'algolia_places'); ?></label><br />
                        <div id="supportedLanguages">
                        <?php                
                        $langs = @unserialize($opt['languagecodes']);
                        foreach(osc_get_locales() as $v) {
                            $code = $v['pk_c_code'];
                            echo '
                            <div style="width: 150px; float: left; margin-right: 15px;">'.$v['s_name'].'</div>
                            <input type="text" name="languagecodes[\''.$code.'\']" id="languagecodes" value="'.$langs['\''.$code.'\''].'"  /><br />';
                        }
                        ?>
                        </div>
                    </div>
                    <div id="standardLanguageCode" <?php if ($languages) { echo 'style="display: none;"'; } ?>>
                        <h3><strong><?php _e('<a href="https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes#Table">ISO 639-1</a> Standard Language Code', 'algolia_places'); ?></strong></h3>
                        <label><?php _e('Enter here the standard language code', 'algolia_places'); ?></label><br />
                        <input type="text" name="standardLanguage" id="standardLanguage" value="<?php echo $opt['standardLanguage'] ?>"  />
                    </div>
                </div> 
            </div> 
        
            <div class="half-row">
                <div class="form-group">
                    <h3><strong><?php _e('Country Support', 'algolia_places'); ?></strong></h3>
                    <label>
                        <input type="checkbox" name="country" id="country" value="1" <?php if ($countries) { echo 'checked="checked"'; } ?> />
                        <?php _e('Check this box to restrict the results to the countrys used in osclass', 'algolia_places'); ?><br />
                        <small><?php _e('With this option only results from selected countries are shown.', 'algolia_places'); ?></small>
                    </label>
                    <div id="supCountries" <?php if (!$countries) { echo 'style="display: none;"'; } ?>>
                        <h3><strong><?php _e('<a href="https://en.wikipedia.org/wiki/ISO_3166-1#Officially_assigned_code_elements">ISO 3166-1</a> Country Codes', 'algolia_places'); ?></strong></h3>
                        <label for="countrycodes"><?php _e('Enter here the supported country codes (separated by ,)', 'algolia_places'); ?></label><br />
                        <input type="text" name="countrycodes" id="countrycodes" placeholder="e.g. (1,3,7,14...)" value="<?php echo $opt['countrycodes']; ?>"  />
                    </div>
                </div> 
            </div> 
            
            <div style="clear: both;"></div>
            
            
            <br />            
            <div>
                <button class="btn btn-submit" type="submit"><?php _e('Save', 'algolia_places'); ?></button>
            </div>
            
            <script>
            $(document).ready(function(){
                $("#country").on("change", function(){
                    if($("#country").is(':checked')) {
                        $("#supCountries").slideDown("slow");    
                    } else {
                        $("#supCountries").slideUp("slow");    
                    }
                });
                $("#language").on("change", function(){
                    if($("#language").is(':checked')) {
                        $("#standardLanguageCode").slideUp("slow", function(){
                            $("#supLanguages").slideDown("slow");    
                        });                            
                    } else {
                        $("#supLanguages").slideUp("slow", function(){
                            $("#standardLanguageCode").slideDown("slow");    
                        });    
                    }
                });    
            });
            </script>
        </div>            
    </form>
</div>
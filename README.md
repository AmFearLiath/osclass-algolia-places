# osclass-algolia-places
This plugin will add functionality for easily use of Algolia Places on your OSClass installation

Before this plugin can work, you have to disable the used autocomplete script for locations in your theme!

in index.php maybe you have to modify some container names in the used javascript, to work with your theme

```
<script>
$(document).ready(function(){    
    $("#sCountry").on("change", function(event){        
        var selCountry = $(this).val().toLowerCase();
        var parent = $("#sCity").parent("span");            
        $("#sCity").val("");
        
        loadPlaces("'.$locale.'", (selCountry.length >=1 ? selCountry : "'.($countries ? $countries : 'none').'")'.(!empty($appid) && !empty($appkey) ? ', "'.$appid.','.$appkey.'"' : '').');      
    });        
    loadPlaces("'.$locale.'", "'.($countries ? $countries : 'none').'"'.(!empty($appid) && !empty($appkey) ? ', "'.$appid.','.$appkey.'"' : '').');    
});
</script>
``

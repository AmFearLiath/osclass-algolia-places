function loadPlaces(language, country, appdata) {        
    var options = {
        container: document.querySelector('#sCity'),
        type: 'city',
        templates: {
            value: function(suggestion) {
                return suggestion.name;
            }
        }
    };
    
    if (language != 'none') { options["language"] = language; }    
    if (country != 'none') { options["countries"] = country.split(','); }
    
    if (appdata.length > 1) {
        app = appdata.split(',');
        options["appId"] = app[0]; 
        options["apiKey"] = app[1]; 
    }
    
    if (window.placesAutocomplete) window.placesAutocomplete.destroy();    
    window.placesAutocomplete = places(options);
    
    window.placesAutocomplete.on('change', function resultSelected(e) {
        var region  = e.suggestion.administrative,
            country = e.suggestion.countryCode.toUpperCase(),
            parent  = $('#sCountry').parent("div"),
            button  = parent.children("button");

        if (region) { $("#sRegion").val(region); }
        else { $("#sRegion").val(""); }
        
        if (country) { 
            $("#sCountry").val(country);
            $('#sCountry option[value='+country+']').attr('selected','selected');
            $(button).children('span.filter-option.pull-left').html($("#sCountry option:selected").text());  
        }
    });
}
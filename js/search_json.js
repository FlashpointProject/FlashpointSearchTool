window.onload = function()
{
    const baseurl = 'http://infinity.unstable.life/Flashpoint/Data/Images';
    var settings = JSON.parse(localStorage.getItem('settings')) || {};
    let search;
    $.fn.form = function() {
        return Object.fromEntries(new FormData(this[0]));
    }
    function refresh() {
        if($('#search .form-control').val().length > 2) {
            var data = {...$('#search').form(), ...settings};
            search = $.post('search_json.php', data, function(data) {
                var parsedData = "";
                for(var curation in data) {
                    parsedData += `<div class='game'>\
                        ${data[curation].isExtreme ? '&#x1F51E; ' : ''}<a href='#' data-toggle='collapse' data-target='#game-${data[curation].id}'>[${data[curation].platform}] ${data[curation].title}</a>\
                        <div class='game-details collapse' id='game-${data[curation].id}' data-id='${data[curation].id}'>Loading...</div>\
                    </div>`
                }
    
                $('.results').html(parsedData);
            });
        }
    }
    $('.setting').each(function() {
        var val = settings[this.name];
        if (this.type == 'checkbox') {
            $(this).prop('checked', !!val);
        } else {
            $(this).val(val);
        }
    });
    $('#settings-dialog').on('hidden.bs.modal', function() {
        settings = $('#settings').form();
        localStorage.setItem('settings', JSON.stringify(settings));
        refresh();
    });
    $('#search').on('submit', function(e) {
        e.preventDefault();
    });
    $('#search .form-control').on('input', refresh);
    
    $(document).on('show.bs.collapse', '.game-details', function() {
        var id = $(this).data('id');
        view = $.get(`view_json.php?id=${id}`, function(data) {
            var parsedData = "";
            parsedData += `<img class="thumb ${data.isExtreme ? 'blur' : ''}" src="${baseurl}/Logos/${data.id.substring(0,2)}/${data.id.substring(2,4)}/${data.id}.png"><img class="thumb ${data.isExtreme ? 'blur' : ''}" src="${baseurl}/Screenshots/${data.id.substring(0,2)}/${data.id.substring(2,4)}/${data.id}.png"><pre>UUID: ${data.id}`;
            if (data.alternateTitles) parsedData += `\nAlternate Titles: ${data.alternateTitles}`;
            if (data.series) parsedData += `\nSeries: ${data.series}`;
            if (data.developer) parsedData += `\nDeveloper: ${data.developer}`;
            if (data.publisher) parsedData += `\nPublisher: ${data.publisher}`;
            if (data.language) parsedData += `\nLanguages: ${data.language}`;
            parsedData += `\nApplication Path: ${data.applicationPath}\nLaunch Command: ${data.launchCommand}</pre>`;
            
            $('#game-'+data.id).html(parsedData);
        });
    });
    
    $(document).on('click', '.blur', function() {
        $(this).removeClass('blur');
    });
    
}

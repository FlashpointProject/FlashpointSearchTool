window.onload = function()
{
    const baseurl = 'http://infinity.unstable.life/Flashpoint/Data/Images';
    var settings = JSON.parse(localStorage.getItem('settings')) || {};
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
            parsedData += `<pre>UUID: ${data.id}`;
            if (data.alternateTitles) parsedData += `\nAlternate Titles: ${data.alternateTitles}`;
            if (data.series) parsedData += `\nSeries: ${data.series}`;
            if (data.developer) parsedData += `\nDeveloper: ${data.developer}`;
            if (data.publisher) parsedData += `\nPublisher: ${data.publisher}`;
            if (data.language) parsedData += `\nLanguages: ${data.language}`;
            parsedData += `\nApplication Path: ${data.applicationPath}\nLaunch Command: ${data.launchCommand}</pre>`;
            
            for (app in data.additionalApps) {
                parsedData += `<div class="app">
                    ${data.additionalApps[app].applicationPath != ':extras:' ? `&#x270E; ${data.additionalApps[app].name}` : `&#128193; ${data.additionalApps[app].launchCommand}`}`;
                if (data.additionalApps[app].applicationPath != ':extras:') {
                    parsedData += `<form class='app-edit'>
                        <div class='form-row'>`;
                    if (data.additionalApps[app].applicationPath != ':message:') {
                        parsedData += `<div class='col-sm-3'>\
                                    <input class='form-control' type='text' name='app-name' id='app-name-${data.additionalApps[app].id}' placeholder='Name' value='${data.additionalApps[app].name}' />
                                </div>
                                <div class='col-sm-3'>
                                    <input class='form-control' list='app-path' type='text' name='app-path' id='app-path-${data.additionalApps[app].id}' placeholder='App path' value='${data.additionalApps[app].applicationPath}' />
                                </div>`;
                    }
                    parsedData += `<div class='col-sm-${data.additionalApps[app].applicationPath != ':message:' ? 3 : 9 }'>
                                        <input class='form-control' type='text' name='app-lc' id='app-lc--${data.additionalApps[app].id}' placeholder='${data.additionalApps[app].applicationPath  == ':message:' ? 'Message content' : data.additionalApps[app].applicationPath != ':extras:' ? 'Launch Command' : ''}' value='${data.additionalApps[app].launchCommand}'/>
                                    </div>
                                    <div class='col-sm-3'>
                                        <button type='button' class='btn btn-primary form-control' onclick='createEdit("${data.additionalApps[app].id}")'>Create Edit</button>
                                    </div>
                                </div>
                            </form>`
                }
                parsedData += '</div>';
            }
            parsedData += `
                <div class="new">
                    &#10133; New App
                    <form class="app-new">
                        <div class="form-row" style="margin-bottom: 5px;">
                            <div class="col-sm-3">
                                <select class="form-control" id="new-type-${data.id}">
                                    <option value="alt">Alt Version</option>
                                    <option value=":message:">Message</option>
                                    <option value=":extras:">Extras</option>
                                </select>
                            </div>
                            <div class="col-sm-3" style="align-self: self-end;">
                                <label><input type="checkbox" name="replacemain" id="new-replc-${data.id}"></input> Become the main app</label>
                            </div>
                            <div class="col-sm-6">
                                <input class="form-control" type="text" id="new-altnm-${data.id}" placeholder="Name for the former main app"/>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-sm-3">
                                <input class="form-control" type="text" name="new-name" id="new-name-${data.id}" placeholder="Name"/>
                            </div>
                            <div class="col-sm-3">
                                <input class="form-control" list="app-path" type="text" name="app-path" id="new-path-${data.id}" placeholder="App path" value="FPSoftware\\Flash\\flashplayer_32_sa.exe" />
                            </div>
                            <div class="col-sm-3">
                                <input class="form-control" type="text" name="new-lc" id="new-lc-${data.id}" placeholder="LC/Extras/Message content"/>
                            </div>
                            <div class="col-sm-3">
                                <button type="button" class="btn btn-success form-control" onclick="createApp('${data.id}')">New App</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <pre></pre>`;
            

            $('#game-'+data.id).html(parsedData);
        });
    });
}

function createEdit(app_id) {
    var app_name = document.getElementById("app-name-" + app_id);
    var app_path = document.getElementById("app-path-" + app_id);
    var app_lc   = document.getElementById("app-lc-" + app_id);
    var changes = []
    if(app_name && app_name["value"] != app_name.getAttribute("value")) changes.push(`name = '${app_name['value']}'`);
    if(app_path && app_path["value"] != app_path.getAttribute("value")) changes.push(`applicationPath = '${app_path['value']}'`);
    if(app_lc && app_lc["value"] != app_lc.getAttribute("value")) changes.push(`applicationPath = '${app_lc['value']}'`);

    if (changes.length) {
        sql = `UPDATE additional_app SET ${changes.join(", ")} WHERE id = '${app_id}';`;
        saveAs(new File([sql], "edit.sql", {type: "text/plain;charset=utf-8"}));
    }
    else{
        alert("It seems there are missing fields from that app.");
    }
}

function createApp(app_id) {
    var uuid = '';
    var app_option = document.getElementById("new-type-" + app_id)["value"];
    var app_replac = document.getElementById("new-replc-" + app_id).checked;
    var app_altnm  = document.getElementById("new-altnm-" + app_id)["value"];
    var app_name   = (app_option == ':message:') ? 'Message' : (app_option == ':extras:') ? 'Extras' : document.getElementById("new-name-" + app_id)["value"];
    var app_path   = (app_option == ':message:' || app_option == ':extras:') ? app_option : document.getElementById("new-path-" + app_id)["value"];
    var app_lc     = document.getElementById("new-lc-" + app_id)["value"];
    var valid = app_lc ? (app_option == 'alt' ? ((!app_name || !app_path) ? false : (!app_replac || (!!app_altnm ? app_altnm : false))) : true) : false;
    
    uuid = UUID.generate();

    if (valid) {
        sql = (app_option == 'alt' && app_replac)
            ? `UPDATE game SET applicationPath = '${app_path}', launchCommand = '${app_lc}' WHERE id = '${app_id}';\n`
            : `INSERT INTO additional_app VALUES '${uuid}', '${app_path}', '${+(app_path == ':message:' || app_path == ':extras:')}', '${app_lc}', '${app_name}', '${+(app_path == ':message:' || app_path == ':extras:')}', '${app_id}';`;
        saveAs(new File([sql], "newapp.sql", {type: "text/plain;charset=utf-8"}));
    }
    else{
        alert("It seems there are missing fields from that app.");
    }
}
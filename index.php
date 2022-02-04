<!DOCTYPE html>
<html>
<meta charset="utf-8">
<title>Flashpoint Browser</title>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
<style>
td { padding: 0 1em; }
.thumb { object-fit: contain; width: 250px; max-height: 250px }
</style>
<div class="container" id="main">
    <h1>Flashpoint Browser</h1>
    <form id="search">
        <div class="form-row">
            <div class="col">
                <input class="form-control" type="text" name="q" placeholder="Type here..." />
            </div>
            <div class="col-sm-2">
                <select class="form-control" name="by">
                    <option>Best match</option>
                    <option value="keywords">Keywords</option>
                    <option value="host">Hostname</option>
                </select>
            </div>
            <div class="col-sm-1">
                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#settings-dialog">
                    <i aria-hidden="true" class="las la-cog la-lg"></i>
                </button>
            </div>
        </div>
    </form>
    <div class="results"></div>
</div>
<div id="settings-dialog" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Settings</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <div class="modal-body">
            <form id="settings">
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input setting" name="extreme" id="extreme">
                        <label class="custom-control-label" for="extreme">Show extreme games</label>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>
<script>
var settings = JSON.parse(localStorage.getItem('settings')) || {};
let search;
$.fn.form = function() {
    return Object.fromEntries(new FormData(this[0]));
}
function refresh() {
    var data = {...$('#search').form(), ...settings};
    search = $.post('search.php', data, function(data) {
        $('.results').html(data);
        $('.game-details').on('show.bs.collapse', function() {
            search.abort();
            var id = $(this).data('id');
            $(this).load(`view.php?id=${id}`);
        });
    });
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
$('#search .form-control').on('input change', refresh);
</script>

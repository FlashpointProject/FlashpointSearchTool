<!DOCTYPE html>
<html>
<meta charset="utf-8">
<title>Flashpoint Browser</title>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
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
        </div>
    </form>
    <div class="results"></div>
</div>
<script>
$('#search').on('submit', function(e) {
    e.preventDefault();
});
$('#search .form-control').on('input change', function(e) {
    $.post('search.php', $('#search').serialize(), function(data) {
        $('.results').html(data);
        $('.game-details').on('show.bs.collapse', function() {
            var id = $(this).data('id');
            $(this).load(`view.php?id=${id}`);
        });
    });
});
</script>

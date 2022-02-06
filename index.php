<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		
		<title>Flashpoint Browser</title>
		
		<script src="node_modules/jquery/dist/jquery.min.js"></script>
		<script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
		<script src="js/search.js"></script>
		
		<link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css" />
		<link rel="stylesheet" href="node_modules/line-awesome/dist/line-awesome/css/line-awesome.min.css" />
		<link rel="stylesheet" href="css/style.css" />
	</head>

	<body>
		<div class="container" id="main">
			<h1>Flashpoint Browser</h1>
			<form id="search">
				<div class="form-row">
					<div class="col">
						<input class="form-control" type="text" name="q" placeholder="Type here..." />
					</div>
					<div class="col-sm-2">
						<select class="custom-select form-control" name="by">
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
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input setting" name="fpurl" id="fpurl">
									<label class="custom-control-label" for="fpurl">Show <em>flashpoint://</em> shortcuts</label>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>

<!DOCTYPE html>
<html>

<head>
	<title>Simple Apache CPanel</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" href="https://www.w3schools.com/lib/w3-colors-win8.css">
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<style>
		.loader {
			border: 16px solid #f3f3f3;
			border-radius: 50%;
			border-top: 16px solid blue;
			border-right: 16px solid green;
			border-bottom: 16px solid red;
			border-left: 16px solid pink;
			width: 120px;
			height: 120px;
			-webkit-animation: spin 2s linear infinite;
			animation: spin 2s linear infinite;
		}

		@-webkit-keyframes spin {
			0% { -webkit-transform: rotate(0deg); }
			100% { -webkit-transform: rotate(360deg); }
		}

		@keyframes spin {
			0% { transform: rotate(0deg); }
			100% { transform: rotate(360deg); }
		}
	</style>
</head>

<body>

<div class="w3-container w3-win8-crimson w3-center">
	<h1>Websites of <?php echo $_SERVER['REMOTE_ADDR']; ?></h1>
</div>

<div style="position:absolute;top:11px;right:5%;">
	<button onClick="showAddDomainModal();" class="w3-button w3-large w3-circle w3-xlarge w3-ripple w3-white w3-card-4" style="z-index:0">+</button>
</div>

<div class="w3-row">
	<div class="w3-col m1">&nbsp;</div>
	<div class="w3-col m10">
		<ul class="w3-ul w3-card-4" id="all-domains" style="font-size:large;"></ul>
	</div>
	<div class="w3-col m1">&nbsp;</div>
</div>

<div id="add-domain" class="w3-modal">
	<div class="w3-modal-content w3-animate-top w3-card-4">
		<header class="w3-container w3-win8-crimson"> 
			<span onclick="hideAddDomainModal();" class="w3-button w3-display-topright">&times;</span>
			<h2>Add Domain</h2>
		</header>
		<div class="w3-container">
			<p>
				<label>Server Name</label>
				<input class="w3-input" type="text" id="server_name" placeholder="www.mydomain.com or sub.mydomain.com">
			</p>
			<p>
				<label>Server Alias</label>
				<input class="w3-input" type="text" id="server_alias" placeholder="mydomain.com (optional)">
			</p>
			<p>
				<input class="w3-check" type="checkbox" checked="checked" id="wordpress" value="true">
				<label for="wordpress">Install Wordpress</label>
			</p>
		</div>
		<footer class="w3-container w3-win8-crimson">
			<p class="w3-center">
				<button class="w3-btn w3-white" onClick="hideAddDomainModal();">Cancel</button>
				<button class="w3-btn w3-white" onClick="addDomainToDB()">Add</button>
			</p>
		</footer>
	</div>
</div>

<div id="db-info" class="w3-modal">
	<div class="w3-modal-content w3-animate-top w3-card-4">
		<header class="w3-container w3-win8-crimson"> 
			<span onclick="hideDbInfo();" class="w3-button w3-display-topright">&times;</span>
			<h2>Database Info</h2>
		</header>
		<div class="w3-container">
			<p>Database: <span id="db-name" style="font-weight:bold;"></span></p>
			<p>User: <span id="db-user" style="font-weight:bold;"></span></p>
			<p>Password: <span id="db-password" style="font-weight:bold;"></span></p>
		</div>
		<footer class="w3-container w3-win8-crimson">
			<p class="w3-center">
				<button class="w3-btn w3-white" onClick="hideDbInfo();">Close</button>
			</p>
		</footer>
	</div>
</div>

<div id="loading" style="position:absolute;top:0px;left:0px;width:100%;height:100%;background-color:#FFFFFF;opacity:0.75;">
	<div style="position:absolute;top:50%;left:50%;margin-top:-60px;margin-left:-60px;">
		<div class="loader"></div>
	</div>
</div>

<script>
	$(document).ready(function() {
		getAllDomains();
	});

	function getAllDomains() {
		$('#loading').show();
		$.get("all-domains.php", function(all_domains) {
			for (var x=0; x<all_domains.length; x++) {
				$('#all-domains').html(all_domains);
			}
			$('#loading').hide();
		});
	}

	function addDomainToDB() {
		if ($('#server_name').val().length==0) {
			alert('Server name (domain name) is required in creating a website.');
			return;
		}
		var data = {
			'server_name':  	$('#server_name').val(),
			'server_alias':  	$('#server_alias').val(),
			'wordpress':  		($('#wordpress').is(":checked")?'true':'false'),
			'writable':  		'true',
			'done':  			'false',
		};
		hideAddDomainModal();
		$.post("add-domain.php", data, function(all_domains) {
			getAllDomains();
		});
	}

	function deleteDomain(domain_id) {
		if (confirm('Are you sure you want to delete this domain?')) {
			$.post("delete-domain.php", {'id':domain_id}, function(all_domains) {
				getAllDomains();
			});
		}
	}

	function changeWritable(value, domain_id) {
		$.post("update-make-writable.php", {'id':domain_id, 'writable':value}, function(all_domains) {
			getAllDomains();
		});
	}

	function hideAddDomainModal() {
		document.getElementById('add-domain').style.display='none';
	}

	function showAddDomainModal() {
		document.getElementById('add-domain').style.display='block';
	}

	function hideDbInfo() {
		document.getElementById('db-info').style.display='none';
	}

	function showDbInfo(domain, db_password) {
		var db_name = domain.replace(/\./g,'_');
		$('#db-name').html(db_name);
		$('#db-user').html(db_name+"_user");
		$('#db-password').html(db_password);
		document.getElementById('db-info').style.display='block';
	}
</script>

</body>
</html>
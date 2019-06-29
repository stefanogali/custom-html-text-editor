<?php
	$is_staging = true;
?>
<?php
	$ini_array = parse_ini_file("includes/text_var.ini");
	include 'includes/service.php';
?>
<html>
	<head>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<?php if ($is_staging){ ?>
			<script src="js/script_class.js"></script> 
			<script>
				$( document ).ready(function() {
				var editor = new clsWies({ selector:'[data-wies-enabled="1"]', popup:'[data-popup="popup-1"]', confirmButton: '#button-wrap-inner .btn'});
				editor.init();
			});
			</script>
		<?php } ?>
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body>
		<div class="popup" data-popup="popup-1">
			<div class="popup-inner">
				<h2>Are you sure you want to save the changes?</h2>
				<div id="button-wrap-inner">
					<a id="save-change" class="btn">Save</a>
					<a id="dischard-change" class="btn">Cancel</a>
				</div>
			</div>
		</div>
		<div>
			<h1 data-wies-enabled="1" data-attribute="title"><?php echo json_decode(getData($ini_array['title']))->message;?></h1><span class = "response-value" style="display:none"><?php echo json_decode(getData($ini_array['title']))->response;?></span>
		</div>
		<div>
			<p data-wies-enabled="1" data-attribute="paragraph"><?php echo json_decode(getData($ini_array['paragraph']))->message;?></p><span class = "response-value" style="display:none"><?php echo json_decode(getData($ini_array['paragraph']))->response;?></span>
		</div>
		<div>
			<h1 data-wies-enabled="1" data-attribute="title-2"><?php echo json_decode(getData($ini_array['title-2']))->message;?></h1><span class = "response-value" style="display:none"><?php echo json_decode(getData($ini_array['title-2']))->response;?></span>
		</div>
		<div>
			<p data-wies-enabled="1" data-attribute="paragraph-2"><?php echo json_decode(getData($ini_array['paragraph-2']))->message;?></p><span class = "response-value" style="display:none"><?php echo json_decode(getData($ini_array['paragraph-2']))->response;?></span>
		</div>
		<div>
			<h1 data-wies-enabled="1" data-attribute="title-3"><?php echo json_decode(getData($ini_array['title-3']))->message;?></h1><span class = "response-value" style="display:none"><?php echo json_decode(getData($ini_array['title-3']))->response;?></span>
		</div>
		<div>
			<p data-wies-enabled="1" data-attribute="paragraph-3"><?php echo json_decode(getData($ini_array['paragraph-3']))->message;?></p><span class = "response-value" style="display:none"><?php echo json_decode(getData($ini_array['paragraph-3']))->response;?></span>
		</div>
		<div>
			<h1 data-wies-enabled="1" data-attribute="title-4"><?php echo json_decode(getData($ini_array['title-4']))->message;?></h1><span class = "response-value" style="display:none"><?php echo json_decode(getData($ini_array['title-4']))->response;?></span>
		</div>
		<div>
			<p data-wies-enabled="1" data-attribute="paragraph-4"><?php echo json_decode(getData($ini_array['paragraph-4']))->message;?></p><span class = "response-value" style="display:none"><?php echo json_decode(getData($ini_array['paragraph-4']))->response;?></span>
		</div>
	</body>
</html>
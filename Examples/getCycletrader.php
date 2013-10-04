<!DOCTYPE html>
<html>
<head>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){
      data = 
      make = 'Yamaha';
      model = 'Yzf600r';
      $.ajax({
        'url' : 'price.php',
        'dataType': 'json',
        data: data,
        success: function(data) {
          $('#price').val(data['Average Cycletrader.com Price']);
          console.log(data);
        }
      });
      //$('#processingRequest').fadeOut('fast');
      //$.mobile.changePage('#page2', {transition: 'pop'});
	});

	</script>
</head>
<body>
	<div id="price">getDetails</div>
</body>
</html>
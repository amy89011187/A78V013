<!DOCTYPE HTML>

<html>

	<head>

		<title>註冊模塊</title>

		<meta charset="utf-8">

		<style>

			#vcode {

				height: 35px;

				width: 40%;

				font-size: 15pt;

				margin-left: 15%;

				border-radius: 5px;

				border: 1;

				padding-left: 8px;

			}

			

			#code {

				color: #ffffff;

				/*字體顏色白色*/

				background-color: #000000;

				font-size: 20pt;

				font-family: "華康娃娃體W5";

				padding: 5px 35px 10px 35px;

				margin-left: 5%;

				cursor: pointer;

			}

			

			#search_pass_link {

				width: 70%;

				text-align: right;

				margin: 0 auto;

				padding: 5px;

			}

			.btns {

				width: 30%;

				margin-left: 13%;

				height: 40px;

				border: 0;

				font-size: 14pt;

				font-family;

				"微軟雅黑";

				background-color: #FC5628;

				color: #ffffff;

				cursor: pointer;

				/*設置指針鼠標的樣式*/

				border-radius: 20px;

				/*設置圓角樣式*/

				border: 0;
			}

			

			

		

		</style>

	</head>



	<body leftmargin="0" οnlοad="changeImg()">

		<div class="main_bar">



			<form action="login.html" οnsubmit="return check()">



				<input type="text" id="vcode" placeholder="驗證碼" value="驗證碼" οnfοcus="this.value=''" οnblur="if(this.value=='')this.value='驗證碼'" /><span
				 id="code" title="看不清，換一張"></span>

				<div id="search_pass_link">


				</div>

				<input type="submit" id="submit" value="登錄" class="btns" οnmοuseοver="this.style.backgroundColor='#FF8D00'"
				 οnmοuseοut="this.style.backgroundColor='#FC5628'">



				<input type="reset" value="取消" class="btns" οnmοuseοver="this.style.backgroundColor='#FF8D00'" οnmοuseοut="this.style.backgroundColor='#FC5628'">

			</form>

		</div>

	</body>

	<script type="text/javascript">
		var code; //聲明一個變量用於存儲生成的驗證碼

		document.getElementById("code").onclick = changeImg;



		function changeImg() {

			var arrays = new Array(

				'1', '2', '3', '4', '5', '6', '7', '8', '9', '0',

				'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j',

				'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't',

				'u', 'v', 'w', 'x', 'y', 'z',

				'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',

				'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',

				'U', 'V', 'W', 'X', 'Y', 'Z'

			);

			code = ''; //重新初始化驗證碼

			//alert(arrays.length);

			//隨機從數組中獲取四個元素組成驗證碼

			for (var i = 0; i < 4; i++) {

				//隨機獲取一個數組的下標

				var r = parseInt(Math.random() * arrays.length);

				code += arrays[r];

			}

			document.getElementById('code').innerHTML = code; //將驗證碼寫入指定區域

		}



		//效驗驗證碼(表單被提交時觸發)

		function check() {

			//獲取用戶輸入的驗證碼

			var input_code = document.getElementById('vcode').value;

			if (input_code.toLowerCase() == code.toLowerCase()) {

				//驗證碼正確(表單提交)

				return true;

			}

			alert("請輸入正確的驗證碼!");

			//驗證碼不正確,表單不允許提交

			return false;
		}
	</script>



	<ml>
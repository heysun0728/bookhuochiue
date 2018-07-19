// JavaScript Document
//必填驗證
function validate_required(field,alerttxt)
{
with (field)
  {
  if (value==null||value=="")
    {alert(alerttxt);return false}
  else {return true}
  }
}
//錯誤訊息控制
function validate_form(thisform)
{
with (thisform)
  {
	  //帳號驗證訊息
	  if (validate_required(inputid,"帳號欄位必填!")==false){inputid.focus();return false}
	  if (validate_id(inputid,"帳號應為英文字母數字組合\n3~15字元")==false){inputid.focus();return false}
	  //密碼驗證訊息
	  if (validate_required(inputpwd,"密碼欄位是必填!")==false){inputpwd.focus();return false}
	  if (validate_required(chkpwd,"密碼欄位是必填!")==false){chkpwd.focus();return false}
	  if (validate_pwd(inputpwd,"密碼格式錯誤!")==false){inputpwd.focus();return false}
	  if (!validate_chkpwd(inputpwd.value,chkpwd.value,"密碼兩次輸入不一樣喔!\n請重新輸入")){chkpwd.focus();return false}
	  //身分證字號驗證訊息
	  if(validate_required(inputIDnumber,"身分證字號必填")==false){inputIDnumber.focus();return false}
	  if(validate_IDNumber(inputIDnumber,"身分證字號格式錯誤！\n第一個字母要大寫")==false){inputIDnumber.focus();return false}
	  if(validate_checkTwID(inputIDnumber.value,"身分證字號輸入錯誤！")==false){inputIDnumber.focus();return false}
	  //電話驗證訊息
	  if(validate_required(inputphone,"電話必填！")==false){inputphone.focus();return false}
	  if(validate_tel(inputphone,"電話格式錯誤")==false){inputphone.focus();return false}
	  //Email驗證訊息
	  if (validate_required(inputemail,"Email必填")==false){inputemail.focus();return false}
	  if (validate_email(inputemail,"Email格式錯誤")==false){inputemail.focus();return false}
	  //監護人姓名驗證
	  if(validate_required(inputpname,"監護人姓名必填！")==false){inputpname.focus();return false}
	  //監護人電話驗證
	  if(validate_required(inputpphone,"電話必填！")==false){inputpphone.focus();return false}
	  if(validate_tel(inputpphone,"電話格式錯誤")==false){inputpphone.focus();return false}
	  //監護人關係驗證
	  if(validate_required(inputprelationship,"電話必填！")==false){inputprelationship.focus();return false}
  }
}
//帳號驗證
function validate_id(field,alerttxt)
{
	with (field)
	{
		var len=value.length
		if(len<3||len>15)
		{
			alert(alerttxt);
			return false
		}else{return true}

	}
}
//密碼驗證
function validate_pwd(field,alerttxt)
{
	with (field)
	{
		var len=value.length
		if(len<3||len>15)
		{
			alert(alerttxt);
			return false
		}else{return true}

	}
}

function validate_chkpwd(a,b,alerttxt)
{
	if(a==b){
		return true
	}
	else
	{
		alert(alerttxt);
		return false
	}
}
//Email驗證
function validate_email(field,alerttxt)
{
	with (field)
	{
		apos=value.indexOf("@")
		dotpos=value.lastIndexOf(".")
		if (apos<1||dotpos-apos<2)
  		{
			alert(alerttxt);
			return false
		}
		else
		{
			return true}

	}
}
//身分證字號驗證
function validate_IDNumber(field,alerttxt)
{
	with (field)
	{
		var len=value.length
		var c=value.substr(0,1)
		if(len==10&&(c>='A'&&c<='Z'))
		{
			return true
		}else{
			alert(alerttxt);
			return false
		}

	}
}
function validate_checkTwID(id,alerttxt){
	//建立字母分數陣列(A~Z)
	var city = new Array(1,10,19,28,37,46,55,64,39,73,82, 2,11,20,48,29,38,47,56,65,74,83,21, 3,12,30)
	id = id.toUpperCase();
	// 使用「正規表達式」檢驗格式
	if (id.search(/^[A-Z](1|2)\d{8}$/i) == -1) {
		alert(alerttxt);
		return false;
	} else {
		//將字串分割為陣列(IE必需這麼做才不會出錯)
		id = id.split('');
		//計算總分
		var total = city[id[0].charCodeAt(0)-65];
		for(var i=1; i<=8; i++){
			total += eval(id[i]) * (9 - i);
		}
		//補上檢查碼(最後一碼)
		total += eval(id[9]);
		//檢查比對碼(餘數應為0);
		if((total%10==0)){return true}else{alert(alerttxt);return false}
	}
}

//電話驗證
function validate_tel(field,alerttxt)
{
	with (field)
	{
		var len=value.length
		if(len==10)
		{
			return true
		}else{
			alert(alerttxt);
			return false
		}

	}
}
}

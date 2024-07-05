with(document.registro){
	onsubmit = function(e){
		e.preventDefault();
		ok = true;
		if(ok && confirm_password.value==""){
			ok=false;
			alert("Debe reconfirmar su password");
			confirm_password.focus();
		}

		if(ok && password.value!= confirm_password.value){
			ok=false;
			alert("Los passwords no coinciden");
			confirm_password.focus();
		}


		if(ok){ submit(); }
	}
}

(() => {
const form=document.getElementById('formRegistro');if(!form)return;
form.addEventListener('submit',event=>{const p=document.getElementById('password').value,c=document.getElementById('confirmarPassword').value;if(p.length<8){event.preventDefault();alert('La contraseña debe tener al menos 8 caracteres.')}else if(p!==c){event.preventDefault();alert('Las contraseñas no coinciden.')}});
})();

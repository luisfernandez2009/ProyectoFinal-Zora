(() => {
const form=document.getElementById('formRegistro'); if(!form)return;
form.addEventListener('submit',event=>{
 const email=document.getElementById('correo').value.trim(), p=document.getElementById('password').value, c=document.getElementById('confirmarPassword').value;
 if(!/^[^\s@]+@(?:gmail\.com|hotmail\.com)$/i.test(email)){event.preventDefault();alert('El correo debe ser válido y terminar en @gmail.com o @hotmail.com.');return;}
 if(p.length<8){event.preventDefault();alert('La contraseña debe tener al menos 8 caracteres.');return;}
 if(p!==c){event.preventDefault();alert('Las contraseñas no coinciden.');}
});
})();

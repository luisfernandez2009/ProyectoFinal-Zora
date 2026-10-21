(() => {
const form=document.getElementById('formLogin'); if(!form)return;
form.addEventListener('submit',event=>{
 const email=document.getElementById('correo').value.trim();
 if(!/^[^\s@]+@(?:gmail\.com|hotmail\.com)$/i.test(email)){event.preventDefault();alert('Ingresá un correo válido que termine en @gmail.com o @hotmail.com.');}
});
})();

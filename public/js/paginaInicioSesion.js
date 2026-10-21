const formulario = document.getElementById("formLogin");
const carrito = document.querySelector("#carritoHeader");
const mensaje = document.querySelector("#mensajeCarritoHeader");

carrito.addEventListener("click",(e)=>{
    e.preventDefault();
    mensaje.textContent = "🛒 El carrito estará disponible en la próxima entrega.";
    mensaje.style.opacity = "1";
    setTimeout(()=>{
        mensaje.style.opacity = "0";
    },3000);
});

formulario.addEventListener("submit", function(event){
    event.preventDefault();
    const correo = document.getElementById("correo").value.trim();
    const password = document.getElementById("password").value;
    const usuario = JSON.parse(localStorage.getItem("usuario"));
    if(usuario === null){
        alert("No existe ninguna cuenta registrada.");
        return;
    }
    if(correo !== usuario.correo){
        alert("Correo incorrecto.");
        return;
    }
    if(password !== usuario.password){
        alert("Contraseña incorrecta.");
        return;
    }
    alert("¡Bienvenido, " + usuario.nombre + "!");
    setTimeout(function(){
        window.location.href = "../index.html";
    },1500);
});
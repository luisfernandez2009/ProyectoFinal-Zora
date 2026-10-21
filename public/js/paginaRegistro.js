const formulario = document.getElementById("formRegistro");
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
    const nombre = document.getElementById("nombre").value.trim();
    const apellido = document.getElementById("apellido").value.trim();
    const correo = document.getElementById("correo").value.trim();
    const password = document.getElementById("password").value;
    const confirmarPassword = document.getElementById("confirmarPassword").value;
    if(
        nombre === "" ||
        apellido === "" ||
        correo === "" ||
        password === "" ||
        confirmarPassword === ""
    ){
        alert("Debes completar todos los campos.");
        return;
    }
    if(password !== confirmarPassword){

        alert("Las contraseñas no coinciden.");

        return;
    }
    const usuario = {
        nombre: nombre,
        apellido: apellido,
        correo: correo,
		password: password
    };
    localStorage.setItem("usuario", JSON.stringify(usuario));
    alert("✔ Cuenta creada correctamente. Redirigiendo al inicio de sesión...");
    setTimeout(() => {
		window.location.href = "paginaInicioSesion.php";
	}, 1500);
});
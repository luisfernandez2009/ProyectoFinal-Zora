const imagenPrincipal = document.querySelector(".imagen-principal");
const talles = document.querySelectorAll(".talle");
const miniaturas = document.querySelectorAll(".miniatura");
const botonCarrito = document.querySelector("#carrito-compra");
const mensaje = document.querySelector("#mensajeCarrito");
const carrito = document.querySelector("#carritoHeader");
const mensajeHeader = document.querySelector("#mensajeCarritoHeader");

miniaturas.forEach((miniatura)=>{
    miniatura.addEventListener("click",()=>{
        imagenPrincipal.src = miniatura.src;
        miniaturas.forEach((img)=>{
            img.classList.remove("activa");
        });
        miniatura.classList.add("activa");
    });
});

talles.forEach((talle)=>{
    talle.addEventListener("click",()=>{
        talles.forEach((boton)=>{
            boton.classList.remove("activo");
        });
        talle.classList.add("activo");
    });
});

botonCarrito.addEventListener("click",()=>{
    const talleSeleccionado = document.querySelector(".talle.activo");
    if(!talleSeleccionado){
        mensaje.style.color="red";
        mensaje.textContent="⚠ Selecciona un talle antes de agregar el producto.";
        mensaje.style.opacity=1;
        return;
    }
    mensaje.style.color="green";
    mensaje.textContent=`✔ Nike Air Max 270 - Talle ${talleSeleccionado.textContent} agregado al carrito.`;
    mensaje.style.opacity=1;
    setTimeout(()=>{
        mensaje.style.opacity=0;
    },3000);

});

carrito.addEventListener("click",(e)=>{
    e.preventDefault();
    mensajeHeader.textContent = "🛒 El carrito estará disponible en la próxima entrega.";
    mensajeHeader.style.opacity = "1";
    setTimeout(()=>{
        mensajeHeader.style.opacity = "0";
    },3000);
});
const elementos = document.querySelectorAll(".oculto");
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

const observador = new IntersectionObserver((entradas) => {
    entradas.forEach((entrada) => {
        if(entrada.isIntersecting){
            entrada.target.classList.add("mostrar");
        }
    });
});

elementos.forEach((elemento)=>{
    observador.observe(elemento);
});


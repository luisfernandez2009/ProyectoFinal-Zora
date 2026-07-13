const buscador = document.querySelector("#buscador");
const botones = document.querySelectorAll(".filtros button");
const productos = document.querySelectorAll(".producto");
const cantidad = document.querySelector(".cantidad-productos");
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

botones.forEach((boton)=>{
    boton.addEventListener("click",()=>{
        botones.forEach((b)=>{
            b.classList.remove("activo");
        });
        boton.classList.add("activo");
        actualizarProductos();
    });
});

buscador.addEventListener("input",()=>{
    actualizarProductos();
});

function actualizarProductos(){
    const texto = buscador.value.toLowerCase();
    const filtroActivo =
    document.querySelector(".filtros .activo")
    .dataset.filtro;
    let visibles = 0;
    productos.forEach((producto)=>{
        const nombre = producto
        .querySelector("h3")
        .textContent
        .toLowerCase();
        const marca = producto.dataset.marca;
        const coincideTexto =
        nombre.includes(texto);
        const coincideFiltro =
        filtroActivo==="todos" ||
        marca===filtroActivo;
        if(coincideTexto && coincideFiltro){
            producto.style.display="";
            visibles++;
        }else{
            producto.style.display="none";
        }
    });
    cantidad.textContent =
    `Mostrando ${visibles} productos`;

}

actualizarProductos();
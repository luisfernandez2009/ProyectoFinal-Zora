// ============================================
// DATOS DE LOS PRODUCTOS
// ============================================

const productos = {
    "nike-airmax270": {
        nombre: "Nike Air Max 270",
        precio: "$6.590",
        imagen: "../img/productos/nike-airmax270.jpg",
        descripcion: "Las Nike Air Max 270 combinan comodidad, estilo y tecnología Air para el uso diario.",
        descripcionCompleta: "Las Nike Air Max 270 destacan por su diseño moderno y su unidad Air Max ubicada en el talón.",
        caracteristicas: [
            "Producto original.",
            "Material transpirable.",
            "Suela Air Max.",
            "Diseño deportivo.",
            "Excelente comodidad."
        ]
    },

    "nike-revolution-7": {
        nombre: "Nike Revolution 7",
        precio: "$4.390",
        imagen: "../img/productos/nike-revolution-7.jpg"
    },

    "nike-pegasus-41": {
        nombre: "Nike Pegasus 41",
        precio: "$7.990",
        imagen: "../img/productos/nike-pegasus-41.jpg"
    },

    "adidas-ultraboost-light": {
        nombre: "Adidas Ultraboost Light",
        precio: "$8.490",
        imagen: "../img/productos/adidas-ultraboost-light.jpg"
    },

    "adidas-duramo-sl": {
        nombre: "Adidas Duramo SL",
        precio: "$4.290",
        imagen: "../img/productos/adidas-duramo-sl.jpg"
    },

    "adidas-runfalcon-5": {
        nombre: "Adidas Runfalcon 5",
        precio: "$4.990",
        imagen: "../img/productos/adidas-runfalcon-5.jpg"
    },

    "puma-rs-x-bw": {
        nombre: "Puma RS-X B&W",
        precio: "$5.290",
        imagen: "../img/productos/puma_rs-x_b&w.jpg"
    },

    "puma-caven-2": {
        nombre: "Puma Caven 2.0",
        precio: "$4.690",
        imagen: "../img/productos/puma-caven-2.0.jpg"
    },

    "puma-velocity-nitro": {
        nombre: "Puma Velocity Nitro",
        precio: "$7.590",
        imagen: "../img/productos/puma-velocity-nitro.jpg"
    },

    "new-balance-574": {
        nombre: "New Balance 574",
        precio: "$5.990",
        imagen: "../img/productos/new-balance-574.jpg"
    },

    "new-balance-530": {
        nombre: "New Balance 530",
        precio: "$6.490",
        imagen: "../img/productos/new-balance-530.jpg"
    },

    "new-balance-fresh-foam-x": {
        nombre: "New Balance Fresh Foam X",
        precio: "$8.290",
        imagen: "../img/productos/new-balance-fresh-foam-x.jpg"
    }
};


// ============================================
// SELECCIONAR LOS ELEMENTOS DEL HTML
// ============================================

const imagenPrincipal = document.querySelector(".imagen-principal");
const miniaturas = document.querySelectorAll(".miniatura");
const contenedorMiniaturas = document.querySelector(".miniaturas");

const tituloProducto = document.querySelector(".info-producto h1");
const precioProducto = document.querySelector(".precio");
const descripcionProducto = document.querySelector(".descripcion");

const descripcionCompleta = document.querySelector(
    ".descripcion-completa p"
);

const listaCaracteristicas = document.querySelector(
    ".descripcion-completa ul"
);

const talles = document.querySelectorAll(".talle");
const botonCarrito = document.querySelector("#carrito-compra");
const mensaje = document.querySelector("#mensajeCarrito");

const carrito = document.querySelector("#carritoHeader");
const mensajeHeader = document.querySelector("#mensajeCarritoHeader");


// ============================================
// OBTENER EL PRODUCTO DE LA DIRECCIÓN
// ============================================

const parametrosURL = new URLSearchParams(window.location.search);

const identificadorProducto = parametrosURL.get("producto");

const productoSeleccionado = productos[identificadorProducto];


// ============================================
// MOSTRAR LOS DATOS DEL PRODUCTO
// ============================================

if (productoSeleccionado) {

    tituloProducto.textContent = productoSeleccionado.nombre;
    precioProducto.textContent = productoSeleccionado.precio;

    imagenPrincipal.src = productoSeleccionado.imagen;
    imagenPrincipal.alt = productoSeleccionado.nombre;

    document.title = productoSeleccionado.nombre + " | Zora";

    descripcionProducto.textContent =
        productoSeleccionado.descripcion ||
        productoSeleccionado.nombre +
        " es uno de los modelos disponibles en Zora. Consultá la disponibilidad de talles antes de comprar.";

    descripcionCompleta.textContent =
        productoSeleccionado.descripcionCompleta ||
        "Conocé el modelo " + productoSeleccionado.nombre +
        " y elegí el calzado que mejor se adapte a tus necesidades.";

    const caracteristicas =
        productoSeleccionado.caracteristicas || [
            "Diseño deportivo.",
            "Modelo disponible en Zora.",
            "Seleccioná tu talle antes de comprar."
        ];

    listaCaracteristicas.innerHTML = "";

    caracteristicas.forEach((caracteristica) => {
        const elemento = document.createElement("li");

        elemento.textContent = "✔ " + caracteristica;

        listaCaracteristicas.appendChild(elemento);
    });

    // La galería de tres imágenes solo existe para Nike Air Max 270.

    if (identificadorProducto === "nike-airmax270") {

        miniaturas.forEach((miniatura) => {
            miniatura.classList.remove("activa");
        });

        if (miniaturas.length > 0) {
            miniaturas[0].classList.add("activa");
        }

    } else {

        contenedorMiniaturas.style.display = "none";
    }

} else {

    // Si no hay un identificador válido, volvemos al catálogo.

    alert("No se encontró el producto seleccionado.");

    window.location.href = "paginaListadoProductos.html";
}


// ============================================
// CAMBIAR LA IMAGEN PRINCIPAL
// ============================================

miniaturas.forEach((miniatura) => {

    miniatura.addEventListener("click", () => {

        imagenPrincipal.src = miniatura.src;

        miniaturas.forEach((imagen) => {
            imagen.classList.remove("activa");
        });

        miniatura.classList.add("activa");
    });
});


// ============================================
// SELECCIONAR UN TALLE
// ============================================

talles.forEach((talle) => {

    talle.addEventListener("click", () => {

        talles.forEach((boton) => {
            boton.classList.remove("activo");
        });

        talle.classList.add("activo");
    });
});


// ============================================
// BOTÓN AGREGAR AL CARRITO
// ============================================

botonCarrito.addEventListener("click", () => {

    const talleSeleccionado = document.querySelector(".talle.activo");

    if (!talleSeleccionado) {

        mensaje.style.color = "red";

        mensaje.textContent =
            "⚠ Seleccioná un talle antes de agregar el producto.";

        mensaje.style.opacity = 1;

        return;
    }

    mensaje.style.color = "green";

    mensaje.textContent =
        "✔ " + productoSeleccionado.nombre +
        " - Talle " + talleSeleccionado.textContent +
        " agregado al carrito.";

    mensaje.style.opacity = 1;

    setTimeout(() => {
        mensaje.style.opacity = 0;
    }, 3000);
});


// ============================================
// MENSAJE DEL CARRITO DEL ENCABEZADO
// ============================================

carrito.addEventListener("click", (evento) => {

    evento.preventDefault();

    mensajeHeader.textContent =
        "🛒 El carrito estará disponible en la próxima entrega.";

    mensajeHeader.style.opacity = "1";

    setTimeout(() => {
        mensajeHeader.style.opacity = "0";
    }, 3000);
});
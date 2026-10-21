
/* ==========================================
   DATOS DE LOS 12 PRODUCTOS
========================================== */

const productos = {
    "nike-airmax270": {
        nombre: "Nike Air Max 270",
        precio: "$6.590",
        imagenes: [
            "../img/productos/nike-airmax270.jpg",
            "../img/productos/nike-airmax270-lado.jpg",
            "../img/productos/nike-airmax270-suela.jpg"
        ],
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
        imagenes: [
            "../img/productos/nike-revolution-7.jpg",
            "../img/productos/nike-revolution-7-lado.jpg",
            "../img/productos/nike-revolution-7-suela.jpg"
        ]
    },

    "nike-pegasus-41": {
        nombre: "Nike Pegasus 41",
        precio: "$7.990",
        imagenes: [
            "../img/productos/nike-pegasus-41.jpg",
            "../img/productos/nike-pegasus-41-lado.jpg",
            "../img/productos/nike-pegasus-41-suela.jpg"
        ]
    },

    "adidas-ultraboost-light": {
        nombre: "Adidas Ultraboost Light",
        precio: "$8.490",
        imagenes: [
            "../img/productos/adidas-ultraboost-light.jpg",
            "../img/productos/adidas-ultraboost-light-lado.jpg",
            "../img/productos/adidas-ultraboost-light-suela.jpg"
        ]
    },

    "adidas-duramo-sl": {
        nombre: "Adidas Duramo SL",
        precio: "$4.290",
        imagenes: [
            "../img/productos/adidas-duramo-sl.jpg",
            "../img/productos/adidas-duramo-sl-lado.jpg",
            "../img/productos/adidas-duramo-sl-suela.jpg"
        ]
    },

    "adidas-runfalcon-5": {
        nombre: "Adidas Runfalcon 5",
        precio: "$4.990",
        imagenes: [
            "../img/productos/adidas-runfalcon-5.jpg",
            "../img/productos/adidas-runfalcon-5-suela-lado.jpg",
            "../img/productos/adidas-runfalcon-5-suela.jpg"
        ]
    },

    "puma-rs-x-bw": {
        nombre: "Puma RS-X B&W",
        precio: "$5.290",
        imagenes: [
            "../img/productos/puma_rs-x_b&w.jpg",
            "../img/productos/puma_rs-x_b&w-lado.jpg",
            "../img/productos/puma_rs-x_b&w-suela.jpg"
        ]
    },

    "puma-caven-2": {
        nombre: "Puma Caven 2.0",
        precio: "$4.690",
        imagenes: [
            "../img/productos/puma-caven-2.0.jpg",
            "../img/productos/puma-caven-2.0-lado.jpg",
            "../img/productos/puma-caven-2.0-suela.jpg"
        ]
    },

    "puma-velocity-nitro": {
        nombre: "Puma Velocity Nitro",
        precio: "$7.590",
        imagenes: [
            "../img/productos/puma-velocity-nitro.jpg",
            "../img/productos/puma-velocity-nitro-lado.jpg",
            "../img/productos/puma-velocity-nitro-suela.jpg"
        ]
    },

    "new-balance-574": {
        nombre: "New Balance 574",
        precio: "$5.990",
        imagenes: [
            "../img/productos/new-balance-574.jpg",
            "../img/productos/new-balance-574-lado.jpg",
            "../img/productos/new-balance-574-suela.jpg"
        ]
    },

    "new-balance-530": {
        nombre: "New Balance 530",
        precio: "$6.490",
        imagenes: [
            "../img/productos/new-balance-530.jpg",
            "../img/productos/new-balance-530-lado.jpg",
            "../img/productos/new-balance-530-suela.jpg"
        ]
    },

    "new-balance-fresh-foam-x": {
        nombre: "New Balance Fresh Foam X",
        precio: "$8.290",
        imagenes: [
            "../img/productos/new-balance-fresh-foam-x.jpg",
            "../img/productos/new-balance-fresh-foam-x-lado.jpg",
            "../img/productos/new-balance-fresh-foam-x-suela.jpg"
        ]
    }
};


/* ==========================================
   ELEMENTOS DEL HTML
========================================== */

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


/* ==========================================
   IDENTIFICAR EL PRODUCTO SELECCIONADO
========================================== */

const parametrosURL = new URLSearchParams(window.location.search);
const identificadorProducto = parametrosURL.get("producto");
const productoSeleccionado = productos[identificadorProducto];


/* ==========================================
   MOSTRAR LOS DATOS Y LAS IMÁGENES
========================================== */

if (!productoSeleccionado) {
    alert("No se encontró el producto seleccionado.");
    window.location.href = "paginaListadoProductos.html";
} else {

    tituloProducto.textContent = productoSeleccionado.nombre;
    precioProducto.textContent = productoSeleccionado.precio;

    imagenPrincipal.src = productoSeleccionado.imagenes[0];
    imagenPrincipal.alt = productoSeleccionado.nombre;

    document.title = productoSeleccionado.nombre + " | Zora";

    descripcionProducto.textContent =
        productoSeleccionado.descripcion ||
        productoSeleccionado.nombre +
        " es uno de los modelos disponibles en Zora. Consultá su disponibilidad y elegí tu talle.";

    descripcionCompleta.textContent =
        productoSeleccionado.descripcionCompleta ||
        "Conocé el modelo " + productoSeleccionado.nombre +
        " y descubrí si es el calzado que estás buscando.";

    const caracteristicas =
        productoSeleccionado.caracteristicas || [
            "Diseño deportivo.",
            "Modelo disponible en Zora.",
            "Consultá la disponibilidad de talles."
        ];

    listaCaracteristicas.innerHTML = "";

    caracteristicas.forEach((caracteristica) => {
        const elemento = document.createElement("li");

        elemento.textContent = "✔ " + caracteristica;

        listaCaracteristicas.appendChild(elemento);
    });

    contenedorMiniaturas.style.display = "flex";

    miniaturas.forEach((miniatura, indice) => {

        const imagen = productoSeleccionado.imagenes[indice];

        if (imagen) {

            miniatura.src = imagen;

            if (indice === 0) {
                miniatura.alt =
                    productoSeleccionado.nombre + " - vista principal";
            } else if (indice === 1) {
                miniatura.alt =
                    productoSeleccionado.nombre + " - vista lateral";
            } else {
                miniatura.alt =
                    productoSeleccionado.nombre + " - vista de la suela";
            }

            miniatura.style.display = "block";
            miniatura.classList.remove("activa");
			
			if (indice === 0) {
                miniatura.classList.add("activa");
            }

        } else {
            miniatura.style.display = "none";
        }
    });

    miniaturas.forEach((miniatura) => {

        miniatura.addEventListener("click", () => {

            if (miniatura.style.display === "none") {
                return;
            }

            imagenPrincipal.src = miniatura.src;
            imagenPrincipal.alt = miniatura.alt;

            miniaturas.forEach((imagen) => {
                imagen.classList.remove("activa");
            });

            miniatura.classList.add("activa");
        });
    });

    talles.forEach((talle) => {

        talle.addEventListener("click", () => {

            talles.forEach((boton) => {
                boton.classList.remove("activo");
            });

            talle.classList.add("activo");
        });
    });

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
}

/* ==========================================
   MENSAJE DEL CARRITO DEL ENCABEZADO
========================================== */

carrito.addEventListener("click", (evento) => {

    evento.preventDefault();

    mensajeHeader.textContent =
        "🛒 El carrito estará disponible en la próxima entrega.";

    mensajeHeader.style.opacity = "1";

    setTimeout(() => {
        mensajeHeader.style.opacity = "0";
    }, 3000);
});

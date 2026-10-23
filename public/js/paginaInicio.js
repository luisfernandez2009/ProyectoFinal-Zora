const elementos = document.querySelectorAll(".oculto");
if ("IntersectionObserver" in window) {
  const observador = new IntersectionObserver((entradas) => {
    entradas.forEach((entrada) => {
      if (entrada.isIntersecting) {
        entrada.target.classList.add("mostrar");
        observador.unobserve(entrada.target);
      }
    });
  });
  elementos.forEach((elemento) => observador.observe(elemento));
} else {
  elementos.forEach((elemento) => elemento.classList.add("mostrar"));
}
// El carrito navega normalmente mediante el enlace del header.
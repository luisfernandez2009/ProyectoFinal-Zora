(() => {
const image=document.querySelector('.imagen-principal'), thumbs=document.querySelectorAll('.miniatura'), sizes=document.querySelectorAll('.talle'), add=document.querySelector('#carrito-compra'), message=document.querySelector('#mensajeCarrito');
if(!image||!add)return;
thumbs.forEach(t=>t.addEventListener('click',()=>{image.src=t.src;image.alt=t.alt;thumbs.forEach(x=>x.classList.remove('activa'));t.classList.add('activa')}));
sizes.forEach(b=>b.addEventListener('click',()=>{sizes.forEach(x=>x.classList.remove('activo'));b.classList.add('activo')}));
add.addEventListener('click',()=>{
 const size=document.querySelector('.talle.activo');
 if(!size){message.textContent='Seleccioná un talle antes de agregar el producto.';message.style.color='red';return}
 const item={producto_id:Number(add.dataset.productoId),talle_id:Number(size.dataset.talleId),talle:size.textContent.trim(),nombre:add.dataset.nombre,precio:Number(add.dataset.precio),imagen:add.dataset.imagen,cantidad:1};
 let cart=[];try{cart=JSON.parse(localStorage.getItem('zora_carrito')||'[]')}catch(e){}
 const existing=cart.find(x=>x.producto_id===item.producto_id&&x.talle_id===item.talle_id);
 if(existing){if(existing.cantidad>=Number(size.dataset.stock)){message.textContent='No podés agregar más unidades que el stock disponible.';message.style.color='red';return}existing.cantidad++}else cart.push(item);
 localStorage.setItem('zora_carrito',JSON.stringify(cart));message.textContent=item.nombre+' (talle '+item.talle+') agregado al carrito.';message.style.color='green';
});
})();

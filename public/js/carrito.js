(() => {
const list=document.getElementById('listaCarrito'),empty=document.getElementById('carritoVacio'),totalEl=document.getElementById('totalCarrito'),form=document.getElementById('formPedido'),payload=document.getElementById('carritoJson');
if(!list||!form||!payload)return;
let cart=[];try{cart=JSON.parse(localStorage.getItem('zora_carrito')||'[]')}catch(e){}
const money=n=>'$'+Number(n).toLocaleString('es-UY',{maximumFractionDigits:0});
function render(){list.innerHTML='';if(empty)empty.hidden=cart.length>0;let total=0;
cart.forEach((item,i)=>{const line=document.createElement('div');line.className='producto-carrito';const label=document.createElement('p');label.textContent=item.nombre+' · Talle '+item.talle+' · '+money(item.precio)+' × '+item.cantidad;
const button=(text,fn)=>{const b=document.createElement('button');b.type='button';b.textContent=text;b.addEventListener('click',fn);return b};
line.append(label,button('−',()=>{item.cantidad--;if(item.cantidad<=0)cart.splice(i,1);save()}),button('+',()=>{if(item.cantidad<20)item.cantidad++;save()}),button('Quitar',()=>{cart.splice(i,1);save()}));list.appendChild(line);total+=Number(item.precio)*Number(item.cantidad)});
if(totalEl)totalEl.textContent='Subtotal estimado: '+money(total)+' (el total definitivo se calcula en el servidor).';
payload.value=JSON.stringify(cart.map(x=>({producto_id:Number(x.producto_id),talle_id:Number(x.talle_id),cantidad:Number(x.cantidad)})));
const submit=document.getElementById('confirmarCompra');if(submit)submit.disabled=!cart.length;
}
function save(){localStorage.setItem('zora_carrito',JSON.stringify(cart));render()}
form.addEventListener('submit',event=>{payload.value=JSON.stringify(cart.map(x=>({producto_id:Number(x.producto_id),talle_id:Number(x.talle_id),cantidad:Number(x.cantidad)})));if(!cart.length){event.preventDefault();alert('Tu carrito está vacío.')}});
render();
})();

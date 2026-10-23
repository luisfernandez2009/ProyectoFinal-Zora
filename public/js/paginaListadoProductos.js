(() => {
const search=document.querySelector('#buscador'), products=[...document.querySelectorAll('.producto')], count=document.querySelector('.cantidad-productos');
if(!search||!products.length)return;
function update(){const term=search.value.toLocaleLowerCase('es');let visible=0;products.forEach(p=>{const show=p.textContent.toLocaleLowerCase('es').includes(term);p.hidden=!show;if(show)visible++});if(count)count.textContent='Mostrando '+visible+' productos'}
search.addEventListener('input',update);update();
})();

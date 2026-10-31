// Prueba el editor real con Cropper.js y un formulario aislado, sin acceder a reportes de usuarios.
// Uso: node tests/browser/pins0302-anotaciones.mjs <carpeta con cropper.min.js y cropper.min.css>
import assert from 'node:assert/strict';
import fs from 'node:fs';
import http from 'node:http';
import path from 'node:path';
import puppeteer from 'puppeteer';

const vendor = process.argv[2];
const formularioId = process.argv[3] || 'FOR-PINS-03_02';
assert(/^FOR-PINS-\d{2}_\d{2}$/.test(formularioId), 'ID de formulario no válido.');
assert(vendor, 'Indique la carpeta temporal que contiene Cropper.js 1.5.12.');
const html = modo => `<!doctype html><html><head><link rel="stylesheet" href="/cropper.css">
<style>.d-none{display:none!important}.img-container{width:760px;height:400px}.image-preview img{width:100px}canvas{max-width:640px}button{margin:4px}</style></head><body>
<form id="${formularioId}" data-anotaciones-fotos data-modo="${modo}">
<div id="image-container-0"><div class="form-group"><div class="image-preview" id="image0-preview"></div>
<input class="image-input" id="image0" type="file"><input type="hidden" id="image0-base64" name="images_base64[0]">
<textarea name="comments[0]">Comentario conservado</textarea></div></div>
<div id="cropperModal"><div class="img-container"><img id="cropperImage"></div><div class="modal-footer">
<button type="button" id="cropImageBtn">Recortar</button><button type="button" id="saveWithoutCropBtn">Guardar</button>
<button type="button" id="rotateLeftBtn">Rotar</button><button type="button" id="rotateRightBtn">Rotar</button></div></div></form>
<script src="/cropper.js"></script><script>
// Adaptador de carga del fixture: entrega al editor la misma instancia Cropper que los scripts del reporte.
document.addEventListener('DOMContentLoaded', () => {
 const c=document.createElement('canvas');c.width=320;c.height=240;
 const x=c.getContext('2d');x.fillStyle='white';x.fillRect(0,0,320,240);
 window.fotoPrueba=c.toDataURL('image/png');
 if (${JSON.stringify(modo)}==='edit') document.getElementById('image0-preview').innerHTML='<img src="'+window.fotoPrueba+'">';
 document.addEventListener('change', e=>{
  if (!e.target.matches('.image-input') || !e.target.files[0]) return;
  window.cropperListo=false;const lector=new FileReader();
  lector.onload=()=>{const img=document.getElementById('cropperImage');if(img.cropper)img.cropper.destroy();
   img.src=lector.result;new Cropper(img,{viewMode:1,ready(){window.cropperListo=true;}});};
  lector.readAsDataURL(e.target.files[0]);
 });
});</script><script src="/anotaciones.js"></script></body></html>`;

const server = http.createServer((req, res) => {
    const archivos = {
        '/cropper.js': [path.join(vendor, 'cropper.min.js'), 'application/javascript'],
        '/cropper.css': [path.join(vendor, 'cropper.min.css'), 'text/css'],
        '/anotaciones.js': ['public/js/Reportes_Anotaciones_Imagenes_06_B_01.js', 'application/javascript'],
    };
    if (archivos[req.url]) {
        res.setHeader('Content-Type', archivos[req.url][1]);
        return res.end(fs.readFileSync(archivos[req.url][0]));
    }
    res.setHeader('Content-Type', 'text/html; charset=utf-8');
    res.end(html(req.url.includes('edit') ? 'edit' : 'create'));
});
await new Promise(resolve => server.listen(0, '127.0.0.1', resolve));
let browser;
try {
    browser = await puppeteer.launch({headless: true});
    for (const [modo, origen] of [['create', 'nueva'], ['edit', 'guardada'], ['edit', 'reemplazo']]) {
        const page = await browser.newPage();
        await page.setViewport({width: 1100, height: 950});
        const errores = [];
        page.on('pageerror', error => errores.push(error.message));
        await page.goto(`http://127.0.0.1:${server.address().port}/${modo}`, {waitUntil: 'networkidle0'});
        if (modo === 'edit' && origen === 'guardada') {
            await page.click('[data-anotar-imagen-guardada]');
        } else {
            await page.evaluate(async () => {
                const blob = await (await fetch(window.fotoPrueba)).blob();
                const transferencia = new DataTransfer();
                transferencia.items.add(new File([blob], 'foto.png', {type: 'image/png'}));
                const input = document.getElementById('image0');
                input.files = transferencia.files;
                input.dispatchEvent(new Event('change', {bubbles: true}));
            });
        }
        await page.waitForFunction(() => window.cropperListo);
        await page.click('#prepararAnotacionImagenBtn');
        const canvas = await page.$('[data-lienzo-anotaciones]');
        const rect = await canvas.boundingBox();
        await page.mouse.move(rect.x + rect.width * .2, rect.y + rect.height * .2);
        await page.mouse.down();
        await page.mouse.move(rect.x + rect.width * .7, rect.y + rect.height * .7, {steps: 8});
        await page.mouse.up();
        await page.type('[data-texto-anotacion]', 'DEFECTO');
        await page.click('[data-herramienta-texto]');
        await canvas.click({offset: {x: 30, y: 50}});
        await page.click('[data-guardar-anotacion]');
        const resultado = await page.evaluate(async () => {
            const base64 = document.getElementById('image0-base64').value;
            const img = new Image();img.src = base64;await img.decode();
            const c = document.createElement('canvas');c.width=img.width;c.height=img.height;
            const ctx=c.getContext('2d');ctx.drawImage(img,0,0);const pix=ctx.getImageData(0,0,c.width,c.height).data;
            let rojos=0;for(let i=0;i<pix.length;i+=4) if(pix[i]>180&&pix[i+1]<100&&pix[i+2]<100)rojos++;
            return {base64,rojos,width:img.width,height:img.height,comentario:document.querySelector('textarea').value,
                preview:document.querySelector('.image-preview img').src};
        });
        assert(resultado.base64.startsWith('data:image/png;base64,'));
        assert(resultado.rojos > 30, 'La imagen guardada debe contener las marcas rojas.');
        assert.equal(resultado.width, 320);assert.equal(resultado.height, 240);
        assert.equal(resultado.preview, resultado.base64);
        assert.equal(resultado.comentario, 'Comentario conservado');
        assert.deepEqual(errores, []);
        console.log(`OK ${modo} (${origen}): flecha, texto, PNG a resolución original y comentario conservado`);
        await page.close();
    }
} finally {
    if (browser) await browser.close();
    await new Promise(resolve => server.close(resolve));
}

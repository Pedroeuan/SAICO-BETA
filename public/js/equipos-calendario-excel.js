/**
 * Genera un XLSX anual con JSZip, ya cargado por las pantallas de inventario.
 * No escribe en la BD. Los estados son una instantánea al descargar el libro.
 * Las hojas, relaciones, dibujos y estilos siguen la estructura OOXML de Excel.
 */
window.exportarCalendarioEquipos = async ({ anio, eventos, estado, titulo, logoUrl }) => {
    if (!window.JSZip) throw new Error('JSZip no disponible');
    let logo = null;
    if (logoUrl) {
        const respuesta = await fetch(logoUrl, { credentials: 'same-origin' });
        if (!respuesta.ok) throw new Error('No se pudo cargar el logo de AICO');
        logo = await respuesta.arrayBuffer();
    }
    // Limpiar caracteres no válidos y escapar XML antes de insertar datos del usuario.
    const esc = v => String(v ?? '').replace(/[\x00-\x08\x0B\x0C\x0E-\x1F]/g, '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    const col = i => { let s = ''; for (let n = i + 1; n; n = Math.floor((n - 1) / 26)) s = String.fromCharCode(65 + (n - 1) % 26) + s; return s; };
    // Los días se guardan como números; IDs y series como texto, preservando ceros iniciales
    // y evitando que un valor iniciado con "=" se ejecute como fórmula.
    const cell = (r, c, v, s = 0) => typeof v === 'number' ? `<c r="${col(c)}${r}" s="${s}"><v>${v}</v></c>` : `<c r="${col(c)}${r}" s="${s}" t="inlineStr"><is><t xml:space="preserve">${esc(v)}</t></is></c>`;
    const ns = 'http://schemas.openxmlformats.org/spreadsheetml/2006/main';
    const rel = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships';
    // Exportar el año completo, independientemente de los filtros visuales de la pantalla.
    const datos = eventos.filter(e => e.fecha && Number(e.fecha.slice(0, 4)) === anio).sort((a,b) => a.fecha.localeCompare(b.fecha) || String(a.numero).localeCompare(String(b.numero)));
    const estiloEstado = list => list.some(e => estado(e) === 'Vencido') ? 4 : list.some(e => estado(e) === 'Próximo a vencer') ? 5 : list.length ? 6 : 3;
    const filas = new Map(), altos = new Map(), merges = [], enlaces = [];
    const poner = (r,c,v,s=0,h=25) => { if (!filas.has(r)) filas.set(r,[]); filas.get(r).push(cell(r,c,v,s)); altos.set(r,Math.max(altos.get(r)||0,h)); };
    const banda = (r,c,fin,v,s,h) => { poner(r,c,v,s,h); merges.push(`${col(c)}${r}:${col(fin)}${r}`); };
    banda(1,0,logo?17:22,`SAICO  /  CALENDARIO ANUAL ${anio}`,7,46);
    banda(2,0,logo?17:22,titulo,1,30);
    if(logo){poner(1,18,'',0,46);merges.push('S1:W2');}
    [[`${datos.length}\nACTIVIDADES PROGRAMADAS`,8],[`${datos.filter(e=>estado(e)==='Vencido').length}\nVENCIDAS`,9],[`${datos.filter(e=>estado(e)==='Próximo a vencer').length}\nPRÓXIMAS A VENCER`,10],[`${datos.filter(e=>estado(e)==='Vigente').length}\nVIGENTES`,11]].forEach(([v,s],i)=>banda(4,i*6,Math.min(i*6+4,22),v,s,58));
    banda(6,0,6,'ROJO · Vencido',4,26); banda(6,8,14,'AMARILLO · Vence dentro de 30 días',5,26); banda(6,16,22,'VERDE · Vigente',6,26);
    banda(7,0,22,'SELECCIONA UN DÍA CON COLOR PARA IR AL DETALLE  ·  Todos los eventos del año  ·  Los estados corresponden a la fecha de exportación',0,26);
    // Distribucion fija de 3 x 4 meses, incluidas seis semanas y meses sin actividades.
    for(let m=1;m<=12;m++){
        const r=9+Math.floor((m-1)/3)*10,c=((m-1)%3)*8,p=`${anio}-${String(m).padStart(2,'0')}`;
        banda(r,c,c+6,`${String(m).padStart(2,'0')}  /  ${new Date(anio,m-1,1).toLocaleDateString('es-MX',{month:'long'}).toLocaleUpperCase('es-MX')}`,1,32);
        ['LUN','MAR','MIÉ','JUE','VIE','SÁB','DOM'].forEach((d,i)=>poner(r+1,c+i,d,2));
        const inicio=(new Date(anio,m-1,1).getDay()+6)%7, dias=new Date(anio,m,0).getDate();
        for(let i=0;i<42;i++){
            const d=i-inicio+1,rf=r+2+Math.floor(i/7),cf=c+i%7;
            if(d<1||d>dias){poner(rf,cf,'',2,34);continue;}
            const fecha=`${p}-${String(d).padStart(2,'0')}`,items=datos.filter(e=>e.fecha===fecha);
            poner(rf,cf,items.length ? `${d}\n${items.length} act.` : d,estiloEstado(items),34);
            if(items.length){
                const destino=datos.findIndex(e=>e.fecha===fecha)+6;
                enlaces.push(`<hyperlink ref="${col(cf)}${rf}" location="'Detalle de actividades'!A${destino}" tooltip="${esc(items.map(e=>`${e.numero} · ${e.nombre} · ${e.tipo}`).join(' | ').slice(0,250))}"/>`);
            }
        }
        banda(r+8,c,c+6,`${datos.filter(e=>e.fecha.startsWith(p)).length} actividades programadas`,2,25);
    }
    const hoja=(body,widths,mergeList,filter='',zoom=90)=>`<?xml version="1.0" encoding="UTF-8"?><worksheet xmlns="${ns}"><sheetPr><tabColor rgb="FF123F79"/><pageSetUpPr fitToPage="1"/></sheetPr><sheetViews><sheetView workbookViewId="0" showGridLines="0" zoomScale="${zoom}"><pane ySplit="${filter?5:8}" topLeftCell="A${filter?6:9}" activePane="bottomLeft" state="frozen"/></sheetView></sheetViews><cols>${widths.map((w,i)=>`<col min="${i+1}" max="${i+1}" width="${w}" customWidth="1"/>`).join('')}</cols><sheetData>${body}</sheetData>${filter}<mergeCells count="${mergeList.length}">${mergeList.map(v=>`<mergeCell ref="${v}"/>`).join('')}</mergeCells>${!filter && enlaces.length ? `<hyperlinks>${enlaces.join('')}</hyperlinks>` : ''}<printOptions horizontalCentered="1" verticalCentered="${filter?0:1}"/><pageMargins left="0.25" right="0.25" top="0.4" bottom="0.4" header="0.2" footer="0.2"/><pageSetup orientation="landscape" paperSize="8" fitToWidth="1" fitToHeight="${filter?0:1}"/><headerFooter><oddFooter>&amp;LSAICO · ${anio}&amp;RPágina &amp;P de &amp;N</oddFooter></headerFooter><ignoredErrors><ignoredError sqref="${filter?`A6:I${Math.max(6,datos.length+5)}`:'A1:W48'}" numberStoredAsText="1"/></ignoredErrors>${logo ? '<drawing xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" r:id="rIdLogo"/>' : ''}</worksheet>`;
    const calendario=hoja([...filas].sort((a,b)=>a[0]-b[0]).map(([r,c])=>`<row r="${r}" ht="${altos.get(r)}" customHeight="1">${c.join('')}</row>`).join(''),Array.from({length:23},(_,i)=>i===7||i===15?2.5:7.5),merges);
    const cab=['Fecha','ID / Económico','Nombre del equipo','Actividad','Estado','Marca','Modelo','Serie','Certificado'];
    const registros=[cab,...datos.map(e=>[e.fecha,e.numero,e.nombre,e.tipo,estado(e),e.marca,e.modelo,e.serie,e.certificado])];
    const row=(r,c,h)=>`<row r="${r}" ht="${h}" customHeight="1">${c}</row>`;
    const detalle=hoja(row(1,cell(1,0,`SAICO  /  ACTIVIDADES ${anio}`,7),44)+row(2,cell(2,0,titulo,1),30)+row(3,cell(3,0,`${datos.length} actividades · Filtra por columna · Estados calculados al exportar`,2),26)+registros.map((v,i)=>row(i+5,v.map((x,c)=>cell(i+5,c,x,!i?1:c===4?estiloEstado([datos[i-1]]):i%2?12:13)).join(''),i?44:32)).join(''),[16,22,48,22,24,22,24,25,30],logo?['A1:G1','A2:G2','H1:I2','A3:I3']:['A1:I1','A2:I2','A3:I3'],`<autoFilter ref="A5:I${registros.length+4}"/>`,85);
    // Los índices de estos estilos se usan al construir celdas: mantener su orden.
    const specs = [
        ['FFFFFF',0,'left',0], ['123F79',1,'left',2], ['F1F4F8',0,'center',0], ['FFFFFF',3,'center',1],
        ['FBE9ED',4,'center',1], ['FFF2CD',5,'center',1], ['E4F3EB',6,'center',1], ['123F79',2,'left',2],
        ['EAF0F9',7,'center',0], ['FBE9ED',4,'center',0], ['FFF2CD',5,'center',0], ['E4F3EB',6,'center',0],
        ['FFFFFF',0,'left',3], ['F4F7FB',0,'left',3]
    ];
    const fonts = [[11,'243B53',false],[12,'FFFFFF',true],[24,'FFFFFF',true],[12,'334E68',true],[12,'A51F36',true],[12,'755600',true],[12,'176442',true],[14,'123F79',true]];
    const styles=`<?xml version="1.0"?><styleSheet xmlns="${ns}"><fonts count="8">${fonts.map(([size,color,bold])=>`<font>${bold?'<b/>':''}<sz val="${size}"/><color rgb="FF${color}"/><name val="Calibri"/></font>`).join('')}</fonts><fills count="16"><fill><patternFill patternType="none"/></fill><fill><patternFill patternType="gray125"/></fill>${specs.map(([color])=>`<fill><patternFill patternType="solid"><fgColor rgb="FF${color}"/><bgColor indexed="64"/></patternFill></fill>`).join('')}</fills><borders count="4"><border/><border><left style="thin"><color rgb="FFE3EAF2"/></left><right style="thin"><color rgb="FFE3EAF2"/></right><top style="thin"><color rgb="FFE3EAF2"/></top><bottom style="thin"><color rgb="FFE3EAF2"/></bottom></border><border><bottom style="medium"><color rgb="FFC62837"/></bottom></border><border><bottom style="hair"><color rgb="FFDCE4EE"/></bottom></border></borders><cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs><cellXfs count="14">${specs.map(([,font,align,border],i)=>`<xf numFmtId="0" fontId="${font}" fillId="${i+2}" borderId="${border}" xfId="0" applyAlignment="1" applyFill="1" applyFont="1" applyBorder="1"><alignment horizontal="${align}" vertical="center" indent="${align==='left'?1:0}" wrapText="1"/></xf>`).join('')}</cellXfs></styleSheet>`;
    // Empaquetar el libro y declarar cada parte y relación para que Excel pueda resolverlas.
    const zip=new window.JSZip();
    zip.file('[Content_Types].xml',`<?xml version="1.0"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/>${logo ? '<Default Extension="png" ContentType="image/png"/><Override PartName="/xl/drawings/drawing1.xml" ContentType="application/vnd.openxmlformats-officedocument.drawing+xml"/><Override PartName="/xl/drawings/drawing2.xml" ContentType="application/vnd.openxmlformats-officedocument.drawing+xml"/>' : ''}<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/><Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>${[1,2].map(i=>`<Override PartName="/xl/worksheets/sheet${i}.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>`).join('')}</Types>`);
    const relationships=v=>`<?xml version="1.0"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">${v}</Relationships>`;
    zip.file('_rels/.rels',relationships(`<Relationship Id="rId1" Type="${rel}/officeDocument" Target="xl/workbook.xml"/>`));
    zip.file('xl/workbook.xml',`<?xml version="1.0"?><workbook xmlns="${ns}" xmlns:r="${rel}"><sheets><sheet name="Calendario ${anio}" sheetId="1" r:id="rId1"/><sheet name="Detalle de actividades" sheetId="2" r:id="rId2"/></sheets><definedNames><definedName name="_xlnm.Print_Area" localSheetId="0">'Calendario ${anio}'!$A$1:$W$47</definedName><definedName name="_xlnm.Print_Area" localSheetId="1">'Detalle de actividades'!$A$1:$I${Math.max(5,datos.length+5)}</definedName></definedNames></workbook>`);
    zip.file('xl/_rels/workbook.xml.rels',relationships([1,2].map(i=>`<Relationship Id="rId${i}" Type="${rel}/worksheet" Target="worksheets/sheet${i}.xml"/>`).join('')+`<Relationship Id="rId3" Type="${rel}/styles" Target="styles.xml"/>`));
    zip.file('xl/styles.xml',styles);zip.file('xl/worksheets/sheet1.xml',calendario);zip.file('xl/worksheets/sheet2.xml',detalle);
    // Un único PNG compartido por dos dibujos; dimensiones en EMU, conservando la proporción.
    if (logo) {
        zip.file('xl/media/aico.png', logo);
        for (const i of [1,2]) {
            const anchorCol = i === 1 ? 18 : 7;
            zip.file(`xl/worksheets/_rels/sheet${i}.xml.rels`, relationships(`<Relationship Id="rIdLogo" Type="${rel}/drawing" Target="../drawings/drawing${i}.xml"/>`));
            zip.file(`xl/drawings/_rels/drawing${i}.xml.rels`, relationships(`<Relationship Id="rIdImage" Type="${rel}/image" Target="../media/aico.png"/>`));
            zip.file(`xl/drawings/drawing${i}.xml`, `<?xml version="1.0"?><xdr:wsDr xmlns:xdr="http://schemas.openxmlformats.org/drawingml/2006/spreadsheetDrawing" xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main"><xdr:oneCellAnchor><xdr:from><xdr:col>${anchorCol}</xdr:col><xdr:colOff>95250</xdr:colOff><xdr:row>0</xdr:row><xdr:rowOff>38100</xdr:rowOff></xdr:from><xdr:ext cx="1714500" cy="965372"/><xdr:pic><xdr:nvPicPr><xdr:cNvPr id="1" name="Logo SAICO"/><xdr:cNvPicPr><a:picLocks noChangeAspect="1"/></xdr:cNvPicPr></xdr:nvPicPr><xdr:blipFill><a:blip xmlns:r="${rel}" r:embed="rIdImage"/><a:stretch><a:fillRect/></a:stretch></xdr:blipFill><xdr:spPr><a:xfrm><a:off x="0" y="0"/><a:ext cx="1714500" cy="965372"/></a:xfrm><a:prstGeom prst="rect"><a:avLst/></a:prstGeom></xdr:spPr></xdr:pic><xdr:clientData/></xdr:oneCellAnchor></xdr:wsDr>`);
        }
    }
    // Liberar la URL después de iniciar la descarga para no retener memoria en la página.
    const blob=await zip.generateAsync({type:'blob',mimeType:'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',compression:'DEFLATE'});
    const url=URL.createObjectURL(blob),link=document.createElement('a');link.href=url;link.download=`SAICO_${/mantenimiento/i.test(titulo)?'Mantenimientos':'Calibraciones_Verificaciones'}_${anio}.xlsx`;document.body.append(link);link.click();link.remove();setTimeout(()=>URL.revokeObjectURL(url),10000);
};

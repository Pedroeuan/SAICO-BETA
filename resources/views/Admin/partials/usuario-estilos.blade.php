<style>
    .usuario-content { 
        padding-top: 36px; 
        padding-bottom: 32px; 
    }
    .usuario-card { 
        max-width: 1200px; 
        margin: 0 auto; 
        border: 1px solid #e2e8f0; 
        border-radius: 14px; 
        overflow: hidden; 
        box-shadow: 0 8px 28px rgba(30, 50, 80, .07); 
    }
    .usuario-card > 
    .card-body { 
        padding: 0; 
        margin: 0; 
    }
    .usuario-identidad { 
        padding: 40px 24px; 
        background: #f7f9fc; 
        border-right: 1px solid #e2e8f0; 
    }
    .usuario-identidad h2 { 
        font-size: 23px; 
        line-height: 1.35; 
        color: #233b56; 
        margin-bottom: 24px; 
    }
    .usuario-identidad img { 
        width: 230px; 
        height: auto; 
        max-width: 100%; 
    }
    .usuario-editor { 
        padding: 28px; 
        min-width: 0; 
    }
    .usuario-tabs { 
        display: flex; 
        gap: 6px; 
        border-bottom: 1px solid #e2e8f0; 
        margin-bottom: 26px; 
    }
    .usuario-tab { 
        flex: 1; 
        border: 0; 
        border-bottom: 3px solid transparent; 
        background: transparent; 
        color: #64748b; 
        padding: 13px 8px; 
        font-size: 14px; 
        font-weight: 600; 
        cursor: pointer; 
    }
    .usuario-tab i { 
        margin-right: 5px; 
    }
    .usuario-tab.active { 
        color: #1764ad; 
        border-bottom-color: #1764ad; 
        background: #f1f7fd; 
        border-radius: 7px 7px 0 0; 
    }
    .usuario-tab:focus-visible { 
        outline: 2px solid #1764ad; 
        outline-offset: -2px; 
    }
    .usuario-panel[hidden] { 
        display: none !important; 
    }
    .usuario-panel { 
        min-height: 380px; 
    }
    .usuario-campos { 
        display: grid; 
        grid-template-columns: repeat(2, minmax(0, 1fr)); 
        column-gap: 20px; 
    }
    .usuario-editor .form-group { 
        margin-bottom: 20px; 
        min-width: 0; 
    }
    .usuario-editor label { 
        font-size: 13px; 
        font-weight: 600; 
        color: #334155; 
        margin-bottom: 7px; 
    }
    .usuario-editor .form-control { 
        border-radius: 6px; 
        border-color: #d6dfeb; 
        min-height: 39px; 
        font-size: 14px; 
    }
    .usuario-editor input[type=file] { 
        height: auto; 
        padding: 7px; 
    }
    .usuario-editor h5 { 
        font-size: 17px; 
        font-weight: 600; 
        color: #233b56; 
        margin-bottom: 18px; 
    }
    .usuario-acciones { 
        border-top: 1px solid #e2e8f0; 
        padding-top: 20px; 
        margin-top: 24px; 
        display: flex; 
        justify-content: flex-end; 
    }
    .usuario-acciones .btn { 
        padding: 10px 28px; 
        border-radius: 7px; 
    }
    .usuario-archivo-actual { 
        display: flex; 
        align-items: center; 
        justify-content: space-between; 
        flex-wrap: wrap; 
        gap: 10px; 
        padding: 12px 14px; 
        margin-bottom: 12px; 
        border: 1px solid #e2e8f0; 
        background: #f8fafc; 
        border-radius: 8px; 
        color: #52657b; 
        font-size: 13px; 
    }
    .usuario-archivo-actual span i { 
        margin-right: 8px; 
        color: #8996a7; 
    }
    .usuario-editor .usuario-boton-archivo { 
        display: inline-flex; 
        align-items: center; 
        justify-content: center; 
        gap: 8px; 
        padding: 8px 12px; 
        border: 1px solid #d7dee8; 
        border-radius: 6px; 
        background: #fff; 
        color: #42546b; 
        text-decoration: none; 
        font-size: 13px; 
        font-weight: 600; 
        line-height: 1.4; 
        cursor: pointer; 
    }
    .usuario-editor 
    .usuario-boton-archivo:hover { 
        background: #edf1f6; 
        border-color: #b9c6d5; 
        color: #233b56; 
        text-decoration: none; 
    }
    .usuario-editor 
    .usuario-boton-archivo:focus-visible { 
        outline: 2px solid #1764ad; 
        outline-offset: 2px; 
    }
    .usuario-documentos { 
        border-top: 1px solid #e2e8f0; 
        margin-top: 26px; 
        padding-top: 22px; 
    }
    .usuario-documentos-encabezado { 
        display: flex; 
        align-items: center; 
        justify-content: space-between; 
        flex-wrap: wrap; 
        gap: 14px; 
        margin-bottom: 18px; 
    }
    .usuario-documentos-encabezado h6 { 
        margin: 0 0 5px; 
        color: #334155; 
        font-weight: 600; 
        font-size: 15px; 
    }
    .usuario-documentos-encabezado p { 
        margin: 0; 
        font-size: 13px; 
    }
    .usuario-documentos-vacio { 
        display: flex; 
        align-items: center; 
        gap: 15px; 
        padding: 24px 18px; 
        border: 1px dashed #dce3ec; 
        border-radius: 8px; 
        color: #52657b; 
        font-size: 13px; 
    }
    .usuario-documentos-vacio[hidden] { 
        display: none; 
    }
    .usuario-documentos-vacio > i { 
        font-size: 26px; 
        color: #94a3b8; 
    }
    .usuario-documentos-vacio small { 
        display: block; 
        margin-top: 5px; 
    }
    .usuario-documentos-nota { 
        display: block; 
        margin-top: 12px; 
        font-size: 12px; 
    }
    .usuario-documentos-nota i { 
        margin-right: 5px; 
    }
    .usuario-pdf-desplegable { 
        border: 1px solid #e2e8f0; 
        border-radius: 8px; 
        margin-bottom: 16px; 
        overflow: hidden; 
    }
    .usuario-pdf-desplegable summary { 
        display: flex; 
        align-items: center; 
        gap: 10px; 
        padding: 14px 16px; 
        background: #f8fafc; 
        color: #42546b; 
        font-size: 13px; 
        font-weight: 600; 
        cursor: pointer; 
        list-style: none; 
    }
    .usuario-pdf-desplegable summary::-webkit-details-marker { 
        display: none; 
    }
    .usuario-pdf-desplegable summary:focus-visible, 
    .usuario-pdf-lista:focus-visible { 
        outline: 2px solid #1764ad; 
        outline-offset: -2px; 
    }
    .usuario-pdf-contador { 
        display: inline-block; 
        margin-left: 6px; 
        padding: 2px 8px; 
        border-radius: 12px; 
        background: #e7edf5; 
        color: #52657b; 
        font-size: 12px; 
    }
    .usuario-pdf-flecha { 
        margin-left: auto; 
        font-size: 11px; 
        transition: transform .15s; 
    }
    .usuario-pdf-desplegable[open] 
    .usuario-pdf-flecha { 
        transform: rotate(180deg); 
    }
    .usuario-pdf-lista { 
        max-height: 360px; 
        overflow-y: auto; 
        padding: 12px; 
        border-top: 1px solid #e2e8f0; 
    }
    .usuario-documentos p, 
    .usuario-documentos small { 
        color: #64748b; 
        line-height: 1.55; 
    }
    .usuario-documento-guardado { 
        display: flex; 
        flex-wrap: wrap; 
        align-items: center; 
        gap: 8px 12px; 
        background: #f8fafc; 
    }
    .usuario-documento-titulo { 
        width: 100%; 
        display: flex; 
        align-items: center; 
        justify-content: space-between; 
        gap: 12px; 
    }
    .usuario-documento-titulo strong { 
        overflow-wrap: anywhere; 
        min-width: 0; 
        color: #334155; 
        font-size: 13px; 
    }
    .usuario-documento-titulo strong i { 
        margin-right: 6px; 
        color: #8996a7; 
    }
    .usuario-documento-titulo a { 
        flex-shrink: 0; 
    }
    .usuario-documento-guardado > span { 
        font-size: 12px; 
        color: #52657b; 
    }
    .usuario-documento-guardado label { 
        margin: 0 0 0 auto; 
        font-size: 12px; 
    }
    .usuario-documento-nuevo { 
        background: #f8fafc; 
    }
    .usuario-documento-nuevo .usuario-campos { 
        gap: 16px; 
    }
    .usuario-documento-nuevo .usuario-campo-archivo { 
        grid-column: 1 / -1; 
    }
    @media (max-width: 991px) {
        .usuario-identidad { 
            padding: 24px 16px; 
        }
        .usuario-identidad img { 
            width: 160px; 
        }
        .usuario-editor { 
            padding: 22px; 
        }
        .usuario-campos { 
            grid-template-columns: 1fr; 
        }
    }
    @media (max-width: 767px) {
        .usuario-content { 
            padding-top: 18px; 
        }
        .usuario-identidad { 
            border-right: 0; 
            border-bottom: 1px solid #e2e8f0; 
        }
        .usuario-identidad h2 { 
            font-size: 20px; 
            margin-bottom: 12px; 
        }
        .usuario-identidad img { 
            width: 105px; 
        }
        .usuario-editor { 
            padding: 18px; 
        }
        .usuario-tab { 
            font-size: 12px; 
            padding: 12px 4px; 
        }
        .usuario-tab i { 
            display: block; 
            margin: 0 0 6px; 
        }
        .usuario-panel { 
            min-height: 0; 
        }
    }
</style>
